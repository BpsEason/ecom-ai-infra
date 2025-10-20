<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Modules\Product\Models\ProductModel;
use Modules\Order\Models\OrderModel;
use Modules\Marketing\Models\MarketingModel;
use Modules\CustomerService\Models\CustomerServiceModel;
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create(['email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin']);
        User::factory()->create(['email' => 'user@example.com', 'password' => bcrypt('password'), 'role' => 'user']);
        ProductModel::factory()->count(10)->create();
        OrderModel::factory()->count(5)->create();
        MarketingModel::factory()->count(3)->create();
        CustomerServiceModel::factory()->count(5)->create();
    }
}
