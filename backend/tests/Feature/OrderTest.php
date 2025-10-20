<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Modules\Order\Models\OrderModel;
class OrderTest extends TestCase
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
        $data = ['customer_id' => $user->id, 'total_amount' => 50.00, 'items' => [['product_id' => 1, 'quantity' => 2]]];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/order', $data);
        $response->assertStatus(201);
        $id = $response->json('data.id');
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/order/'.$id);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->putJson('/api/order/'.$id, $data);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/order/'.$id);
        $response->assertStatus(204);
    }
    public function test_unauthorized_user_cannot_modify() {
        $user = User::where('role', 'user')->first();
        $data = ['customer_id' => $user->id, 'total_amount' => 50.00, 'items' => [['product_id' => 1, 'quantity' => 2]]];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/order', $data);
        $response->assertStatus(403);
    }
    public function test_validation_fails_on_invalid_data() {
        $user = User::where('role', 'admin')->first();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/order', []);
        $response->assertStatus(422);
    }
    
    public function test_admin_can_get_eta() {
        $admin = User::factory()->create(['role' => 'admin']);
        $order = OrderModel::factory()->create();
        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/order/{$order->id}/eta');
        $response->assertStatus(200)->assertJsonStructure(['data' => ['prediction']]);
    }
}
