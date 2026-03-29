<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dapodik_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('base_url')->nullable();
            $table->string('api_key')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dapodik_settings');
    }
};
