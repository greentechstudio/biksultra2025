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
            $table->boolean('profile_edited')->default(false)->after('status');
            $table->timestamp('profile_edited_at')->nullable()->after('profile_edited');
            $table->text('edit_notes')->nullable()->after('profile_edited_at')->comment('Catatan perubahan profil');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_edited', 'profile_edited_at', 'edit_notes']);
        });
    }
};
