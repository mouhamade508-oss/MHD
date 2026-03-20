<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('materials')->nullable()->after('specifications');
            $table->text('care_instructions')->nullable()->after('materials');
            $table->text('origin')->nullable()->after('care_instructions');
            $table->text('warranty')->nullable()->after('origin');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['materials', 'care_instructions', 'origin', 'warranty']);
        });
    }
};

