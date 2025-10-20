<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Modules\Dashboard\Models\DashboardModel;
class DashboardTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
        $user = User::factory()->create(['role' => 'admin']);
        User::factory()->create(['role' => 'user']);
    }
    public function test_admin_can_perform_crud() {
        $user = User::where('role', 'admin')->first();
        $data = [];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/dashboard', $data);
        $response->assertStatus(201);
        $id = $response->json('data.id');
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/dashboard/'.$id);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->putJson('/api/dashboard/'.$id, $data);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/dashboard/'.$id);
        $response->assertStatus(204);
    }
    public function test_unauthorized_user_cannot_modify() {
        $user = User::where('role', 'user')->first();
        $data = [];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/dashboard', $data);
        $response->assertStatus(403);
    }
    public function test_validation_fails_on_invalid_data() {
        $user = User::where('role', 'admin')->first();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/dashboard', []);
        $response->assertStatus(422);
    }
    
}
