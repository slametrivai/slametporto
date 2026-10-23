<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Project;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkBioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_link_in_bio_shows_featured_case_study_latest_article_and_contact_form(): void
    {
        $featured = Project::orderBy('sort_order')->where('is_featured', true)->first();
        $latestPost = Post::published()->latest('published_at')->first();

        $this->get('/linkbio')->assertOk()
            ->assertSee('Contact me')
            ->assertSee('id="contact-dialog"', false)
            ->assertSee(route('projects.show', $featured->slug), false)
            ->assertSee(route('blog.show', $latestPost->slug), false)
            ->assertSee(route('resume.show'), false);
    }

    public function test_social_links_come_from_settings_and_hide_when_empty(): void
    {
        foreach (['contact_email', 'whatsapp_number', 'linkedin_url', 'github_url'] as $key) {
            Setting::set($key, null);
        }

        $this->get('/linkbio')->assertOk()
            ->assertDontSee('wa.me/', false)
            ->assertDontSee('mailto:', false)
            ->assertDontSee('github.com', false);

        $this->actingAs(User::first())->post('/admin/settings', [
            'site_name' => 'Slamet Rivai',
            'contact_email' => 'kontak@example.com',
            'whatsapp_number' => '0811-2222-333',
            'linkedin_url' => 'https://www.linkedin.com/in/contoh',
            'github_url' => 'https://github.com/contoh',
        ])->assertSessionHasNoErrors();

        $this->get('/linkbio')->assertOk()
            ->assertSee('mailto:kontak@example.com', false)
            ->assertSee('https://wa.me/628112222333', false)
            ->assertSee('https://www.linkedin.com/in/contoh', false)
            ->assertSee('https://github.com/contoh', false);
    }
}
