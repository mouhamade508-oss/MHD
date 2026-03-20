<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('specifications')->nullable();
            $table->text('additional_info')->nullable();
            $table->text('available_colors')->nullable();
            $table->text('sizes_available')->nullable();
            $table->text('why_choose_it')->nullable();
            $table->decimal('price', 8, 2);
            $table->decimal('discount_percentage', 5, 2)->default(0);
$table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('featured')->default(false);
            $table->string('tags')->nullable();
            $table->string('image')->nullable();
            $table->string('video')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};