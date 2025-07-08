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
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->unsignedBigInteger('race_category_id')->after('id');
            $table->foreign('race_category_id')->references('id')->on('race_categories');
            
            // Remove the old category string column
            $table->dropColumn('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropForeign(['race_category_id']);
            $table->dropColumn('race_category_id');
            
            // Add back the old category string column
            $table->string('category')->after('name');
        });
    }
};
