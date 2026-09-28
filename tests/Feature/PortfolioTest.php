<?php

namespace Tests\Feature;

use App\Models\Career;
use App\Models\Category;
use App\Models\Certification;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\Post;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PortfolioTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_landing_page_renders_with_authentic_data(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Slamet Rivai');
        $response->assertSee('Operations &amp; Systems Leader', false);
        $response->assertSee('Bekasi, Indonesia');
        $response->assertSee('Customer Operations');
        $response->assertSee('CRM Architecture');
        $response->assertSee('Workflow Automation');
        $response->assertSee('People Development');
    }

    public function test_resume_page_renders_successfully(): void
    {
        $response = $this->get('/resume');

        $response->assertStatus(200);
        $response->assertSee('Slamet Rivai');
        $response->assertSee('Print / Save as PDF');
    }

    public function test_case_study_detail_page_renders_with_slug(): void
    {
        $project = Project::first();

        $response = $this->get('/projects/' . $project->slug);

        $response->assertStatus(200);
        $response->assertSee($project->title);
        $response->assertSee($project->role);
        $response->assertSee('The Engineered Systems Solution');
    }

    public function test_projects_index_and_search_and_filter(): void
    {
        $response = $this->get('/projects');
        $response->assertStatus(200);
        $response->assertSee('Featured Systems &amp; Case Studies', false);
        $response->assertSee('Operational Engineering');

        // Filter by category
        $project = Project::first();
        $responseCat = $this->get('/projects?category=' . urlencode($project->category));
        $responseCat->assertStatus(200);
        $responseCat->assertSee($project->title);

        // Search by keyword
        $responseSearch = $this->get('/projects?q=' . urlencode(substr($project->title, 0, 8)));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee($project->title);
    }

    public function test_blog_index_and_search_and_filter(): void
    {
        $response = $this->get('/blog');
        $response->assertStatus(200);
        $response->assertSee('Knowledge Base');
        $response->assertSee('Operations & Systems Notes', false);

        // Filter by category
        $responseCat = $this->get('/blog?category=workflow-automation');
        $responseCat->assertStatus(200);

        // Search
        $responseSearch = $this->get('/blog?q=OCR');
        $responseSearch->assertStatus(200);
    }

    public function test_blog_reader_shows_article_and_increments_views(): void
    {
        $post = Post::published()->first();
        $initialViews = $post->views_count;

        $response = $this->get('/blog/' . $post->slug);

        $response->assertStatus(200);
        $response->assertSee($post->title);
        $this->assertEquals($initialViews + 1, $post->fresh()->views_count);
    }

    public function test_contact_form_submission(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'subject' => 'Workflow Automation Inquiry',
            'message' => 'Halo Slamet, saya ingin mendiskusikan integrasi OCR invoice untuk perusahaan kami.',
        ];

        $response = $this->postJson('/contact', $data);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('inquiries', [
            'email' => 'johndoe@example.com',
            'subject' => 'Workflow Automation Inquiry',
            'status' => 'new',
        ]);
    }

    public function test_unauthenticated_user_cannot_access_admin(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_admin_can_access_dashboard_and_datatables(): void
    {
        $admin = User::first();

        // 1. Dashboard
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Studi kasus terbaru');

        // 2. Clients Yajra DataTables AJAX response
        $responseDt = $this->actingAs($admin)->getJson('/admin/clients', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $responseDt->assertStatus(200);
        $responseDt->assertJsonStructure(['data', 'recordsTotal', 'recordsFiltered']);

        // 3. Projects Yajra DataTables AJAX response
        $responseProj = $this->actingAs($admin)->getJson('/admin/projects', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $responseProj->assertStatus(200);
        $responseProj->assertJsonStructure(['data', 'recordsTotal', 'recordsFiltered']);

        // 4. Inquiries Yajra DataTables AJAX response
        $responseInq = $this->actingAs($admin)->getJson('/admin/inquiries', [
            'X-Requested-With' => 'XMLHttpRequest'
        ]);
        $responseInq->assertStatus(200);
        $responseInq->assertJsonStructure(['data', 'recordsTotal', 'recordsFiltered']);
    }

    public function test_admin_can_manage_inquiry_status_and_export(): void
    {
        $admin = User::first();
        $inquiry = Inquiry::first();

        // Patch status
        $patchRes = $this->actingAs($admin)->patchJson('/admin/inquiries/' . $inquiry->id . '/status', [
            'status' => 'responded'
        ]);
        $patchRes->assertStatus(200);
        $this->assertEquals('responded', $inquiry->fresh()->status);

        // Export CSV, with public form input neutralised against spreadsheet formula injection
        $inquiry->update(['name' => '=HYPERLINK("http://evil.test")']);
        $exportRes = $this->actingAs($admin)->get('/admin/inquiries/export');
        $exportRes->assertStatus(200);
        $exportRes->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString("'=HYPERLINK", $exportRes->streamedContent());
    }
}
