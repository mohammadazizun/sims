<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('dusun')->nullable()->after('address');
            $table->string('residence_type')->nullable()->after('dusun');
            $table->string('transportation')->nullable()->after('residence_type');
            $table->string('parent_phone')->nullable()->after('phone');
            $table->string('father_nik')->nullable()->after('father_name');
            $table->string('mother_nik')->nullable()->after('mother_name');
            $table->string('guardian_nik')->nullable()->after('guardian_name');
            $table->string('assistance_type')->nullable()->after('guardian_nik');
            $table->string('assistance_number')->nullable()->after('assistance_type');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'dusun',
                'residence_type',
                'transportation',
                'parent_phone',
                'father_nik',
                'mother_nik',
                'guardian_nik',
                'assistance_type',
                'assistance_number',
            ]);
        });
    }
};
