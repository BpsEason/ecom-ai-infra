<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Modules\CustomerService\Models\CustomerServiceModel;
class CustomerServiceTest extends TestCase
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
        $data = ['user_id' => $user->id, 'subject' => 'Test Issue', 'description' => 'Test description', 'status' => 'open'];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/customerservice', $data);
        $response->assertStatus(201);
        $id = $response->json('data.id');
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/customerservice/'.$id);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->putJson('/api/customerservice/'.$id, $data);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/customerservice/'.$id);
        $response->assertStatus(204);
    }
    public function test_unauthorized_user_cannot_modify() {
        $user = User::where('role', 'user')->first();
        $data = ['user_id' => $user->id, 'subject' => 'Test Issue', 'description' => 'Test description', 'status' => 'open'];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/customerservice', $data);
        $response->assertStatus(403);
    }
    public function test_validation_fails_on_invalid_data() {
        $user = User::where('role', 'admin')->first();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/customerservice', []);
        $response->assertStatus(422);
    }
    
    public function test_admin_can_get_wave_suggestion() {
        $admin = User::factory()->create(['role' => 'admin']);
        $ticket = CustomerServiceModel::factory()->create();
        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/customerservice/{$ticket->id}/wave-suggestion');
        $response->assertStatus(200)->assertJsonStructure(['data' => ['prediction']]);
    }
}
