<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('name');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->json('items');
            $table->string('status')->default('pending');
            $table->timestamps();
        });
        Schema::create('marketing_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('budget', 10, 2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::create('customer_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('subject');
            $table->text('description');
            $table->string('status')->default('open');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('customer_services');
        Schema::dropIfExists('marketing_campaigns');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
    }
};
