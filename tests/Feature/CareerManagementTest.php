<?php

namespace Tests\Feature;

use App\Models\Career;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CareerManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_index_lists_careers_in_sort_order_with_modal(): void
    {
        $response = $this->actingAs(User::first())->get('/admin/careers');

        $response->assertOk()->assertSee('id="career-dialog"', false)->assertSee('id="career-list"', false);
        $response->assertSeeInOrder(Career::orderBy('sort_order')->orderBy('id')->pluck('role')->all());
    }

    public function test_new_career_is_appended_and_edit_keeps_its_position(): void
    {
        $admin = User::first();
        $last = (int) Career::max('sort_order');

        $this->actingAs($admin)->post('/admin/careers', [
            'company' => 'Acme', 'role' => 'Ops Lead', 'period' => '2025 - now',
        ])->assertRedirect('/admin/careers');

        $career = Career::where('company', 'Acme')->firstOrFail();
        $this->assertSame($last + 1, $career->sort_order);

        $this->actingAs($admin)->put("/admin/careers/{$career->id}", [
            'company' => 'Acme Corp', 'role' => 'Ops Lead', 'period' => '2025 - now',
        ])->assertRedirect('/admin/careers');

        $this->assertSame('Acme Corp', $career->fresh()->company);
        $this->assertSame($last + 1, $career->fresh()->sort_order);
    }

    public function test_reorder_saves_dragged_order_and_rejects_unknown_ids(): void
    {
        $admin = User::first();
        $ids = Career::orderBy('sort_order')->pluck('id')->reverse()->values()->all();

        $this->actingAs($admin)->patchJson('/admin/careers/reorder', ['ids' => $ids])->assertOk();
        $this->assertSame($ids, Career::orderBy('sort_order')->pluck('id')->all());

        $this->actingAs($admin)->patchJson('/admin/careers/reorder', ['ids' => [999999]])->assertUnprocessable();
    }
}
