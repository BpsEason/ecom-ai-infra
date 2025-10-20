<?php
namespace Tests\Feature;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Modules\Product\Models\ProductModel;
class ProductTest extends TestCase
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
        $data = ['name' => 'Test Product', 'sku' => 'SKU123', 'price' => 99.99, 'description' => 'Test description'];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/product', $data);
        $response->assertStatus(201);
        $id = $response->json('data.id');
        $response = $this->actingAs($user, 'sanctum')->getJson('/api/product/'.$id);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->putJson('/api/product/'.$id, $data);
        $response->assertStatus(200);
        $response = $this->actingAs($user, 'sanctum')->deleteJson('/api/product/'.$id);
        $response->assertStatus(204);
    }
    public function test_unauthorized_user_cannot_modify() {
        $user = User::where('role', 'user')->first();
        $data = ['name' => 'Test Product', 'sku' => 'SKU123', 'price' => 99.99, 'description' => 'Test description'];
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/product', $data);
        $response->assertStatus(403);
    }
    public function test_validation_fails_on_invalid_data() {
        $user = User::where('role', 'admin')->first();
        $response = $this->actingAs($user, 'sanctum')->postJson('/api/product', []);
        $response->assertStatus(422);
    }
    
    public function test_admin_can_get_recommendations() {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = ProductModel::factory()->create();
        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/product/{$product->id}/recommend');
        $response->assertStatus(200)->assertJsonStructure(['data' => ['prediction']]);
    }
}
