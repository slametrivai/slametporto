<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryUrlTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_project_category_has_a_clean_url_and_old_query_redirects(): void
    {
        $name = Project::value('category');
        $url = Project::categoryUrl($name);

        $this->assertStringNotContainsString('?', $url);
        $this->get($url)->assertOk()->assertSee($name . ' Case Studies');
        $this->get('/projects?category=' . rawurlencode($name) . '&q=ocr')->assertStatus(301)->assertRedirect($url . '?q=ocr');
        $this->get('/projects/category/nope')->assertNotFound();
    }

    public function test_blog_topic_has_a_clean_url_and_old_query_redirects(): void
    {
        $slug = Category::forPosts()->whereHas('posts')->value('slug');

        $this->get('/blog/topic/' . $slug)->assertOk();
        $this->get('/blog?category=' . $slug)->assertStatus(301)->assertRedirect('/blog/topic/' . $slug);
        $this->get('/blog/topic/nope')->assertNotFound();
    }
}
