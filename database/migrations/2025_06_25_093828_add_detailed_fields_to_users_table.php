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
            // Personal Info
            $table->enum('gender', ['male', 'female'])->after('name');
            $table->string('birth_place')->nullable()->after('gender');
            $table->date('birth_date')->nullable()->after('birth_place');
            $table->text('address')->nullable()->after('birth_date');
            
            // Event specific
            $table->string('jersey_size')->nullable()->after('address');
            $table->string('race_category')->nullable()->after('jersey_size');
            $table->string('whatsapp_number')->nullable()->after('phone');
            $table->string('emergency_contact_1')->nullable()->after('whatsapp_number');
            $table->string('emergency_contact_2')->nullable()->after('emergency_contact_1');
            $table->string('group_community')->nullable()->after('emergency_contact_2');
            $table->string('blood_type')->nullable()->after('group_community');
            $table->string('occupation')->nullable()->after('blood_type');
            $table->text('medical_history')->nullable()->after('occupation');
            $table->string('event_source')->nullable()->after('medical_history');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'gender', 'birth_place', 'birth_date', 'address',
                'jersey_size', 'race_category', 'whatsapp_number', 'emergency_contact_1',
                'emergency_contact_2', 'group_community', 'blood_type',
                'occupation', 'medical_history', 'event_source'
            ]);
        });
    }
};
