<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCoverWordTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_thumbnail_uses_cover_word_and_falls_back_to_last_title_word(): void
    {
        $post = Post::published()->latest('published_at')->first();
        $post->update(['title' => 'Why Your Ads Get Leads but Not Sales', 'cover_word' => null, 'cover_image' => null]);

        $this->assertSame('Sales', $post->fresh()->thumbnail_word);
        $this->get('/')->assertOk()->assertSee('>Sales</span>', false);

        $post->update(['cover_word' => 'Leads']);

        $this->assertSame('Leads', $post->fresh()->thumbnail_word);
        $this->get('/blog')->assertOk()->assertSee('>Leads</span>', false);
    }

    public function test_admin_can_save_cover_word(): void
    {
        $post = Post::first();

        $this->actingAs(User::first())->put(route('admin.posts.update', $post), [
            'title' => $post->title,
            'category_id' => $post->category_id,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'status' => $post->status,
            'cover_word' => 'Laravel',
        ])->assertSessionHasNoErrors();

        $this->assertSame('Laravel', $post->fresh()->cover_word);
    }
}
