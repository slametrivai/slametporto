<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_cannot_access_categories(): void
    {
        $response = $this->get('/admin/categories');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_categories_index_and_datatable(): void
    {
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/categories');
        $response->assertStatus(200);
        $response->assertSee('Kategori konten');
        $response->assertSee('Studi Kasus');

        // Test DataTables AJAX response
        $ajaxResponse = $this->actingAs($admin)->getJson('/admin/categories', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $ajaxResponse->assertStatus(200);
        $ajaxResponse->assertJsonStructure([
            'draw',
            'recordsTotal',
            'recordsFiltered',
            'data',
        ]);

        // Test type filtered AJAX
        $projectAjaxResponse = $this->actingAs($admin)->getJson('/admin/categories?type=project', [
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $projectAjaxResponse->assertStatus(200);
    }

    public function test_admin_can_create_post_and_project_category_with_seo(): void
    {
        $admin = User::first();

        // 1. Create Post Category with custom SEO
        $postCatResponse = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'Event-Driven Microservices',
            'type' => 'post',
            'description' => 'Arsitektur event-driven dan microservices asynchronous.',
            'seo_title' => 'Event-Driven Microservices — Best Practices & Insights',
            'seo_description' => 'Panduan lengkap arsitektur event-driven berbasis Kafka dan RabbitMQ.',
            'seo_robots' => 'index, follow',
        ]);

        $postCatResponse->assertRedirect();
        $postCatResponse->assertSessionHas('success');

        $postCategory = Category::where('name', 'Event-Driven Microservices')->first();
        $this->assertNotNull($postCategory);
        $this->assertEquals('post', $postCategory->type);
        $this->assertEquals('event-driven-microservices', $postCategory->slug);
        $this->assertNotNull($postCategory->seo);
        $this->assertEquals('Event-Driven Microservices — Best Practices & Insights', $postCategory->seo->title);

        // Test SEO dynamic data
        $dynamicSeo = $postCategory->getDynamicSEOData();
        $this->assertEquals('Event-Driven Microservices — Best Practices & Insights', $dynamicSeo->title);

        // 2. Create Project Category with auto SEO
        $projCatResponse = $this->actingAs($admin)->post('/admin/categories', [
            'name' => 'IoT & Edge Computing',
            'type' => 'project',
            'description' => 'Sistem pemrosesan sensor di edge hardware.',
        ]);

        $projCatResponse->assertRedirect();
        $projCategory = Category::where('name', 'IoT & Edge Computing')->first();
        $this->assertNotNull($projCategory);
        $this->assertEquals('project', $projCategory->type);
        $this->assertEquals('iot-edge-computing', $projCategory->slug);

        // Fallback SEO title check
        $dynamicProjSeo = $projCategory->getDynamicSEOData();
        $this->assertStringContainsString('IoT & Edge Computing', $dynamicProjSeo->title);
    }

    public function test_updating_project_category_cascades_to_existing_projects(): void
    {
        $admin = User::first();

        $projectCategory = Category::where('type', 'project')->first();
        $oldCategoryName = $projectCategory->name;

        // Ensure at least one project uses this category
        $project = Project::first();
        $project->update(['category' => $oldCategoryName]);

        $newCategoryName = 'Advanced Systems & Automation';

        $updateResponse = $this->actingAs($admin)->put('/admin/categories/' . $projectCategory->id, [
            'name' => $newCategoryName,
            'slug' => 'advanced-systems-automation',
            'type' => 'project',
            'description' => 'Updated category description.',
            'seo_title' => 'Advanced Systems & Automation Case Studies',
        ]);

        $updateResponse->assertRedirect();
        $updateResponse->assertSessionHas('success');

        $this->assertEquals($newCategoryName, $projectCategory->fresh()->name);
        $this->assertEquals('advanced-systems-automation', $projectCategory->fresh()->slug);

        // Assert cascading update to projects table!
        $this->assertEquals($newCategoryName, $project->fresh()->category);
    }

    public function test_cannot_delete_category_with_active_relations(): void
    {
        $admin = User::first();

        // Try deleting a post category that has posts
        $postCategory = Category::where('type', 'post')->has('posts')->first();
        if (!$postCategory) {
            $post = Post::first();
            $postCategory = $post->category;
        }

        $deleteResponse = $this->actingAs($admin)->deleteJson('/admin/categories/' . $postCategory->id);
        $deleteResponse->assertStatus(422);
        $deleteResponse->assertJson(['success' => false]);
        $this->assertDatabaseHas('categories', ['id' => $postCategory->id]);

        // Try deleting a project category that has projects
        $project = Project::first();
        $projectCategory = Category::where('name', $project->category)->first();

        $deleteProjResponse = $this->actingAs($admin)->deleteJson('/admin/categories/' . $projectCategory->id);
        $deleteProjResponse->assertStatus(422);
        $deleteProjResponse->assertJson(['success' => false]);
        $this->assertDatabaseHas('categories', ['id' => $projectCategory->id]);
    }

    public function test_can_delete_unused_category(): void
    {
        $admin = User::first();

        $unusedCategory = Category::create([
            'name' => 'Temporary Category',
            'slug' => 'temporary-category',
            'type' => 'post',
        ]);

        $deleteResponse = $this->actingAs($admin)->deleteJson('/admin/categories/' . $unusedCategory->id);
        $deleteResponse->assertStatus(200);
        $deleteResponse->assertJson(['success' => true]);
        $this->assertDatabaseMissing('categories', ['id' => $unusedCategory->id]);
    }

    public function test_project_and_post_create_forms_load_dynamic_categories(): void
    {
        $admin = User::first();

        // Check project create form
        $projCreateResponse = $this->actingAs($admin)->get('/admin/projects/create');
        $projCreateResponse->assertStatus(200);
        $projectCategories = Category::forProjects()->pluck('name');
        foreach ($projectCategories as $catName) {
            $projCreateResponse->assertSee(e($catName), false);
        }

        // Check post create form
        $postCreateResponse = $this->actingAs($admin)->get('/admin/posts/create');
        $postCreateResponse->assertStatus(200);
        $postCategories = Category::forPosts()->pluck('name');
        foreach ($postCategories as $catName) {
            $postCreateResponse->assertSee(e($catName), false);
        }
    }
}
