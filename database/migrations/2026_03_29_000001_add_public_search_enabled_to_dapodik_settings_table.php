<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dapodik_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('dapodik_settings', 'public_search_enabled')) {
                $table->boolean('public_search_enabled')->default(false)->after('active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('dapodik_settings', function (Blueprint $table) {
            if (Schema::hasColumn('dapodik_settings', 'public_search_enabled')) {
                $table->dropColumn('public_search_enabled');
            }
        });
    }
};
