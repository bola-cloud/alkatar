<?php

namespace Tests\Feature;

use App\Models\Admin\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrdersReportTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Run default migrations
        $this->artisan('migrate');
        // Seed basic user and orders
        $this->seed(\Database\Seeders\OrderSeeder::class);
    }

    protected function actingAsAdmin()
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        return $this->actingAs($admin);
    }

    public function test_today_filter_works(): void
    {
        $this->actingAsAdmin();
        $res = $this->get(route('admin.reports.orders.index', ['range' => 'today']));
        $res->assertStatus(200);
        $res->assertSee('Orders Report');
    }

    public function test_month_filter_works(): void
    {
        $this->actingAsAdmin();
        $res = $this->get(route('admin.reports.orders.index', ['range' => 'month']));
        $res->assertStatus(200);
        $res->assertSee('Orders Report');
    }

    public function test_pdf_export_returns_pdf(): void
    {
        $this->actingAsAdmin();
        $res = $this->get(route('admin.reports.orders.pdf', ['range' => 'month']));
        $res->assertStatus(200);
        $res->assertHeader('content-type', 'application/pdf');
    }
}
