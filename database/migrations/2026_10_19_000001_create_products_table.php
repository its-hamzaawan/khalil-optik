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
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category', 40)->index();       // spectacles | sunglasses | contact-lenses | accessories
            $table->string('brand')->nullable();
            $table->string('gender', 20)->nullable();       // men | women | unisex | kids
            $table->string('frame_shape', 40)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');               // PKR, whole rupees
            $table->unsignedInteger('sale_price')->nullable();
            $table->unsignedInteger('stock')->default(0);
            $table->string('image')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
