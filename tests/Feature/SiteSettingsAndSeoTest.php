<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteSettingsAndSeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_guest_cannot_access_site_settings(): void
    {
        $response = $this->get('/admin/settings');
        $response->assertRedirect('/login');
    }

    public function test_admin_can_view_and_update_site_settings(): void
    {
        Storage::fake('public');
        $admin = User::first();

        $response = $this->actingAs($admin)->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('Identitas &amp; SEO', false);
        $response->assertSee('Pratinjau hasil pencarian');

        // Test updating settings with logo and favicon
        $logoFile = UploadedFile::fake()->image('custom-logo.png', 200, 200);
        $faviconFile = UploadedFile::fake()->image('favicon.ico', 32, 32);

        $updateResponse = $this->actingAs($admin)->post('/admin/settings', [
            'site_name' => 'Slamet Rivai Systems',
            'site_tagline' => 'Enterprise Architecture & Operations',
            'site_title' => 'Slamet Rivai Systems — Scalable Enterprise Architecture',
            'meta_description' => 'Custom meta description for testing SEO metadata pipeline.',
            'meta_keywords' => 'systems, architecture, ocr, crm',
            'site_author' => 'Slamet Rivai',
            'site_twitter' => '@slametrivai',
            'site_logo' => $logoFile,
            'site_favicon' => $faviconFile,
        ]);

        $updateResponse->assertRedirect('/admin/settings');
        $updateResponse->assertSessionHas('success');

        $this->assertEquals('Slamet Rivai Systems', Setting::get('site_name'));
        $this->assertEquals('Enterprise Architecture & Operations', Setting::get('site_tagline'));

        // Verify public home page reflects updated branding and SEO
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('Slamet Rivai Systems');
        $homeResponse->assertSee('Custom meta description for testing SEO metadata pipeline.');
    }

    public function test_contact_links_come_from_settings_and_hide_when_empty(): void
    {
        $admin = User::first();
        $base = ['site_name' => 'Slamet Rivai'];

        // Empty settings: no dead contact links on the public site
        $this->actingAs($admin)->post('/admin/settings', $base + ['contact_email' => '', 'whatsapp_number' => '', 'linkedin_url' => '']);
        $this->get('/')->assertOk()->assertDontSee('wa.me/', false)->assertDontSee('mailto:', false);

        // Local number format is normalised for wa.me
        $this->actingAs($admin)->post('/admin/settings', $base + [
            'contact_email' => 'kontak@example.com',
            'whatsapp_number' => '0811-2222-333',
            'linkedin_url' => 'https://www.linkedin.com/in/contoh',
        ])->assertSessionHasNoErrors();

        $this->assertEquals('628112222333', Setting::get('whatsapp_number'));
        $this->get('/')->assertOk()
            ->assertSee('https://wa.me/628112222333', false)
            ->assertSee('mailto:kontak@example.com', false)
            ->assertSee('https://www.linkedin.com/in/contoh', false);
        $this->get('/resume')->assertOk()->assertSee('linkedin.com/in/contoh');

        $this->actingAs($admin)->post('/admin/settings', $base + ['whatsapp_number' => 'bukan nomor'])
            ->assertSessionHasErrors('whatsapp_number');
    }

    public function test_post_creation_with_tinymce_content_and_custom_seo(): void
    {
        $admin = User::first();
        $category = Category::first();

        $createPage = $this->actingAs($admin)->get('/admin/posts/create');
        $createPage->assertStatus(200);
        $createPage->assertSee('vendor/tinymce/tinymce.min.js');
        $createPage->assertSee('Pratinjau hasil pencarian');

        $postData = [
            'category_id' => $category->id,
            'title' => 'Building High Throughput Pipelines in Laravel',
            'excerpt' => 'A detailed breakdown of queuing and OCR batch processing in production.',
            'content' => '<p>Here is <strong>TinyMCE</strong> formatted content with custom tags.</p>',
            'status' => 'published',
            'seo_title' => 'High Throughput Laravel Pipelines | Expert Guide',
            'seo_description' => 'Learn how to architect high throughput background pipelines.',
            'seo_robots' => 'index, follow',
            'seo_canonical_url' => 'https://slametrivai.host/blog/building-high-throughput-pipelines-in-laravel',
        ];

        $storeResponse = $this->actingAs($admin)->post('/admin/posts', $postData);
        $storeResponse->assertRedirect('/admin/posts');

        $post = Post::where('title', 'Building High Throughput Pipelines in Laravel')->first();
        $this->assertNotNull($post);
        $this->assertEquals('<p>Here is <strong>TinyMCE</strong> formatted content with custom tags.</p>', $post->content);

        // Verify custom polymorphic SEO was created
        $this->assertNotNull($post->seo);
    }

    public function test_public_blog_reader_renders_custom_seo_tags(): void
    {
        $post = Post::first();
        $post->seo()->updateOrCreate([], [
            'title' => 'Custom Blog Post Title For SEO Verification',
            'description' => 'Custom Blog Post Description For SEO Verification.',
            'robots' => 'index, follow',
            'canonical_url' => 'https://slametrivai.host/blog/' . $post->slug,
        ]);

        $response = $this->get('/blog/' . $post->slug);
        $response->assertStatus(200);
        $response->assertSee('Custom Blog Post Title For SEO Verification');
        $response->assertSee('Custom Blog Post Description For SEO Verification.');
    }

    public function test_post_edit_and_update_with_tinymce_and_custom_seo(): void
    {
        $admin = User::first();
        $category = Category::first();
        $post = Post::first();

        // Test editing the post with TinyMCE & SEO
        $editPage = $this->actingAs($admin)->get('/admin/posts/' . $post->id . '/edit');
        $editPage->assertStatus(200);
        $editPage->assertSee('vendor/tinymce/tinymce.min.js');
        $editPage->assertSee('Pratinjau hasil pencarian');

        // Update post with modified content
        $updateResponse = $this->actingAs($admin)->put('/admin/posts/' . $post->id, [
            'category_id' => $category->id,
            'title' => 'Updated Post Title For Testing',
            'excerpt' => 'Updated excerpt summary for testing.',
            'content' => '<p>Updated TinyMCE content with revision 2.</p>',
            'status' => 'published',
            'seo_title' => 'Updated Custom SEO Title',
            'seo_description' => 'Updated custom SEO description.',
            'seo_robots' => 'index, follow',
            'seo_canonical_url' => '',
        ]);

        $updateResponse->assertRedirect('/admin/posts');
        $post->refresh();
        $this->assertEquals('<p>Updated TinyMCE content with revision 2.</p>', $post->content);
        $this->assertEquals('Updated Custom SEO Title', $post->seo->title);
    }
}
