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
            // Add missing fields from registrations table
            $table->foreignId('ticket_type_id')->nullable()->after('race_category')->constrained()->onDelete('set null');
            $table->string('xendit_payment_id')->nullable()->after('xendit_invoice_id');
            $table->timestamp('paid_at')->nullable()->after('payment_confirmed_at');
            $table->string('registration_number')->unique()->nullable()->after('email');
            $table->boolean('is_active')->default(true)->after('status');
            
            // Add indexes for better performance
            $table->index(['payment_status', 'is_active']);
            $table->index(['race_category', 'payment_status']);
            $table->index('registration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['payment_status', 'is_active']);
            $table->dropIndex(['race_category', 'payment_status']);
            $table->dropIndex(['registration_number']);
            
            // Drop foreign key and columns
            $table->dropForeign(['ticket_type_id']);
            $table->dropColumn([
                'ticket_type_id',
                'xendit_payment_id',
                'paid_at',
                'registration_number',
                'is_active'
            ]);
        });
    }
};
