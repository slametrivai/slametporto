<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Gallery Pipeline',
            'category' => 'Automation & OCR',
            'role' => 'Lead Architect',
            'challenge' => 'Manual data entry.',
            'solution' => 'OCR pipeline.',
            'impact_metrics' => ['2 days → 30 min'],
            'impact_labels' => ['Processing time'],
            'workflow_steps' => ['Upload', 'OCR'],
        ], $overrides);
    }

    public function test_admin_can_upload_multiple_gallery_images_on_create(): void
    {
        $this->actingAs(User::first())->post('/admin/projects', $this->payload([
            'gallery_images' => [
                UploadedFile::fake()->image('one.jpg'),
                UploadedFile::fake()->image('two.png'),
            ],
        ]))->assertRedirect('/admin/projects');

        $project = Project::where('title', 'Gallery Pipeline')->firstOrFail();

        $this->assertCount(2, $project->images);
        $this->assertSame([1, 2], $project->images->pluck('sort_order')->all());
        $project->images->each(fn ($image) => Storage::disk('public')->assertExists($image->path));
    }

    public function test_update_removes_checked_images_and_appends_new_ones(): void
    {
        $project = Project::first();
        $keep = $project->images()->create(['path' => UploadedFile::fake()->image('keep.jpg')->store('projects/gallery', 'public'), 'sort_order' => 1]);
        $drop = $project->images()->create(['path' => UploadedFile::fake()->image('drop.jpg')->store('projects/gallery', 'public'), 'sort_order' => 2]);

        $this->actingAs(User::first())->put('/admin/projects/' . $project->id, $this->payload([
            'remove_images' => [$drop->id],
            'gallery_images' => [UploadedFile::fake()->image('new.webp')],
        ]))->assertRedirect('/admin/projects');

        $images = $project->fresh()->images;
        $this->assertSame($keep->id, $images->first()->id);
        $this->assertCount(2, $images);
        $this->assertSame(2, $images->last()->sort_order);
        $this->assertModelMissing($drop);
        Storage::disk('public')->assertMissing($drop->path);
    }

    public function test_update_ignores_image_ids_from_other_projects(): void
    {
        [$project, $other] = Project::take(2)->get();
        $foreign = $other->images()->create(['path' => 'projects/gallery/foreign.jpg']);

        $this->actingAs(User::first())->put('/admin/projects/' . $project->id, $this->payload([
            'remove_images' => [$foreign->id],
        ]));

        $this->assertModelExists($foreign);
    }

    public function test_gallery_rejects_non_images(): void
    {
        $this->actingAs(User::first())->post('/admin/projects', $this->payload([
            'gallery_images' => [UploadedFile::fake()->create('notes.pdf', 10, 'application/pdf')],
        ]))->assertSessionHasErrors('gallery_images.0');
    }

    public function test_public_page_renders_gallery_with_lightbox(): void
    {
        $project = Project::first();
        $project->images()->create(['path' => 'projects/gallery/a.jpg', 'title' => 'OCR review queue', 'description' => 'Low-confidence documents wait here.', 'sort_order' => 1]);
        $project->images()->create(['path' => 'projects/gallery/b.jpg', 'sort_order' => 2]);

        $this->get('/projects/' . $project->slug)
            ->assertOk()
            ->assertSee('Screens from the live system')
            ->assertSee('View all 2 screens')
            ->assertSee('x-ref="lightbox"', false)
            ->assertSeeInOrder(['storage/projects/gallery/a.jpg', 'storage/projects/gallery/b.jpg'])
            ->assertSeeInOrder(['OCR review queue', 'Screen 02'])
            ->assertSee('Low-confidence documents wait here.');
    }

    public function test_more_than_three_images_collapse_into_a_more_overlay(): void
    {
        $project = Project::first();
        foreach (range(1, 5) as $i) {
            $project->images()->create(['path' => "projects/gallery/{$i}.jpg", 'sort_order' => $i]);
        }

        $this->get('/projects/' . $project->slug)->assertOk()->assertSee('+2')->assertSee('more screens');
    }

    public function test_update_saves_image_captions(): void
    {
        $project = Project::first();
        $image = $project->images()->create(['path' => 'projects/gallery/a.jpg', 'sort_order' => 1]);

        $this->actingAs(User::first())->put('/admin/projects/' . $project->id, $this->payload([
            'image_titles' => [$image->id => 'ERP posting log'],
            'image_descriptions' => [$image->id => '  '],
        ]))->assertRedirect('/admin/projects');

        $image->refresh();
        $this->assertSame('ERP posting log', $image->title);
        $this->assertNull($image->description);
    }

    public function test_store_saves_story_fields_metric_details_and_step_descriptions(): void
    {
        $this->actingAs(User::first())->post('/admin/projects', $this->payload([
            'summary' => 'Short pitch.',
            'result' => 'Cycle time fell.',
            'volume' => '5,000+ documents / month',
            'components' => 'OCR, , Webhooks ',
            'impact_details' => ['from 48 hours'],
            'step_descriptions' => ['Files arrive.', ''],
        ]))->assertRedirect('/admin/projects');

        $project = Project::where('title', 'Gallery Pipeline')->firstOrFail();
        $this->assertSame('Short pitch.', $project->teaser);
        $this->assertSame(['OCR', 'Webhooks'], $project->components);
        $this->assertSame('from 48 hours', $project->impact_highlights[0]['detail']);
        $this->assertSame([
            ['name' => 'Upload', 'description' => 'Files arrive.'],
            ['name' => 'OCR', 'description' => ''],
        ], $project->steps);
    }

    public function test_edit_page_shows_story_fields_and_caption_inputs(): void
    {
        $project = Project::first();
        $image = $project->images()->create(['path' => 'projects/gallery/a.jpg', 'title' => 'OCR review queue', 'sort_order' => 1]);

        $this->actingAs(User::first())->get('/admin/projects/' . $project->id . '/edit')
            ->assertOk()
            ->assertSee('name="summary"', false)
            ->assertSee('name="components"', false)
            ->assertSee('name="image_titles[' . $image->id . ']"', false)
            ->assertSee('value="OCR review queue"', false);
    }

    public function test_steps_accessor_reads_legacy_string_steps(): void
    {
        $project = new Project(['workflow_steps' => ['Inbound', 'Router']]);

        $this->assertSame([['name' => 'Inbound', 'description' => ''], ['name' => 'Router', 'description' => '']], $project->steps);
    }

    public function test_public_page_hides_gallery_without_images(): void
    {
        $project = Project::first();

        $this->get('/projects/' . $project->slug)->assertOk()->assertDontSee('Screens from the live system');
    }

    public function test_deleting_project_removes_gallery_files(): void
    {
        $project = Project::first();
        $image = $project->images()->create(['path' => UploadedFile::fake()->image('x.jpg')->store('projects/gallery', 'public')]);

        $this->actingAs(User::first())->delete('/admin/projects/' . $project->id);

        Storage::disk('public')->assertMissing($image->path);
        $this->assertModelMissing($image);
    }
}
