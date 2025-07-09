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
            // Add new emergency contact fields
            $table->string('emergency_contact_name')->nullable()->after('whatsapp_number');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            
            // Remove old emergency contact fields
            $table->dropColumn(['emergency_contact_1', 'emergency_contact_2']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add back old emergency contact fields
            $table->string('emergency_contact_1')->nullable()->after('whatsapp_number');
            $table->string('emergency_contact_2')->nullable()->after('emergency_contact_1');
            
            // Remove new emergency contact fields
            $table->dropColumn(['emergency_contact_name', 'emergency_contact_phone']);
        });
    }
};
