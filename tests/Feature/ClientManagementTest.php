<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ClientManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_index_has_modal_and_rows_carry_edit_data(): void
    {
        $admin = User::first();
        $client = Client::first();

        $this->actingAs($admin)->get('/admin/clients')
            ->assertOk()->assertSee('id="client-dialog"', false)->assertSee($client->industry);

        $json = $this->actingAs($admin)->getJson('/admin/clients', ['X-Requested-With' => 'XMLHttpRequest'])->json('data.0.action');
        $this->assertStringContainsString('data-edit-modal', $json);
    }

    public function test_store_respects_unchecked_visibility_and_update_keeps_logo(): void
    {
        Storage::fake('public');
        $admin = User::first();

        $this->actingAs($admin)->post('/admin/clients', [
            'name' => 'Acme', 'industry' => 'Retail', 'logo' => UploadedFile::fake()->image('acme.png'),
        ])->assertRedirect('/admin/clients');

        $client = Client::where('name', 'Acme')->firstOrFail();
        $this->assertFalse($client->is_active);

        $this->actingAs($admin)->put("/admin/clients/{$client->id}", [
            'name' => 'Acme Corp', 'industry' => 'Retail', 'is_active' => '1',
        ])->assertRedirect('/admin/clients');

        $fresh = $client->fresh();
        $this->assertSame('Acme Corp', $fresh->name);
        $this->assertTrue($fresh->is_active);
        $this->assertSame($client->logo, $fresh->logo);
    }

    public function test_failed_create_returns_to_index_so_the_modal_can_reopen(): void
    {
        $this->actingAs(User::first())->from('/admin/clients')
            ->post('/admin/clients', ['name' => 'No logo', 'industry' => 'Retail'])
            ->assertRedirect('/admin/clients')
            ->assertSessionHasErrors('logo');
    }
}
