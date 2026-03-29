<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dapodik_settings', function (Blueprint $table) {
            $table->string('fetch_endpoint')->nullable()->after('api_key');
            $table->string('push_endpoint')->nullable()->after('fetch_endpoint');
        });
    }

    public function down(): void
    {
        Schema::table('dapodik_settings', function (Blueprint $table) {
            $table->dropColumn(['fetch_endpoint', 'push_endpoint']);
        });
    }
};
