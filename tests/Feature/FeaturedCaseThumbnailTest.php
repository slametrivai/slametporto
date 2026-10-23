<?php

namespace Tests\Feature;

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FeaturedCaseThumbnailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
        Storage::fake('public');
    }

    private function featured(): Project
    {
        return Project::orderBy('sort_order')->where('is_featured', true)->first();
    }

    public function test_featured_thumbnail_links_to_the_case_study(): void
    {
        $project = $this->featured();
        $project->update(['cover_image' => UploadedFile::fake()->image('cover.jpg')->store('projects', 'public')]);

        $this->get('/')->assertOk()
            ->assertSeeInOrder([
                'href="' . route('projects.show', $project->slug) . '" tabindex="-1"',
                'src="' . asset('storage/' . $project->cover_image) . '"',
            ], false);
    }

    public function test_missing_cover_file_falls_back_to_gallery_then_hides(): void
    {
        $project = $this->featured();
        $project->update(['cover_image' => 'projects/does-not-exist.svg']);

        $this->get('/')->assertOk()->assertDontSee('storage/projects/does-not-exist.svg', false);

        $gallery = UploadedFile::fake()->image('shot.jpg')->store('projects/gallery', 'public');
        $project->images()->create(['path' => $gallery, 'sort_order' => 0]);

        $this->get('/')->assertOk()->assertSee('src="' . asset('storage/' . $gallery) . '"', false);
    }
}
