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
        Schema::table('users', function (Blueprint $table) {
            $table->string('regency_id')->nullable()->after('address');
            $table->string('regency_name')->nullable()->after('regency_id');
            $table->string('province_name')->nullable()->after('regency_name');
            
            // Add index for search performance
            $table->index('regency_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['regency_name']);
            $table->dropColumn([
                'regency_id',
                'regency_name',
                'province_name'
            ]);
        });
    }
};
