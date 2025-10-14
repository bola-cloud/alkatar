<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Database\Seeders\OrderSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Some projects use roles/permissions; we bypass by creating an admin-like user
        $this->artisan('migrate');
        $this->seed(OrderSeeder::class);
    }

    protected function actingAsAdmin()
    {
        $user = User::factory()->create(['is_admin' => 1]);
        return $this->actingAs($user);
    }

    public function test_today_filter_returns_ok_and_has_totals()
    {
        $this->actingAsAdmin();
        $res = $this->get(route('admin.reports.orders.index', ['range' => 'today']));
        $res->assertStatus(200);
        $res->assertSee('Total Orders');
    }

    public function test_month_filter_returns_ok_and_has_totals()
    {
        $this->actingAsAdmin();
        $res = $this->get(route('admin.reports.orders.index', ['range' => 'month']));
        $res->assertStatus(200);
        $res->assertSee('Total Order Amount');
    }

    public function test_pdf_export_returns_pdf_content_type()
    {
        $this->actingAsAdmin();
        $res = $this->get(route('admin.reports.orders.pdf', ['range' => 'today']));
        $res->assertStatus(200);
        $res->assertHeader('content-type', 'application/pdf');
    }
}
