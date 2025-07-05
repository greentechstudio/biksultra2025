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
            // Drop the current gender column first
            $table->dropColumn('gender');
        });
        
        Schema::table('users', function (Blueprint $table) {
            // Add gender column with Indonesian values
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the Indonesian gender column
            $table->dropColumn('gender');
        });
        
        Schema::table('users', function (Blueprint $table) {
            // Restore the original English gender column
            $table->enum('gender', ['male', 'female'])->after('name');
        });
    }
};
