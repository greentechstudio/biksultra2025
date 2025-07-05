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
            $table->string('xendit_invoice_id')->nullable()->after('payment_notes');
            $table->string('xendit_invoice_url')->nullable()->after('xendit_invoice_id');
            $table->string('xendit_external_id')->nullable()->after('xendit_invoice_url');
            $table->enum('payment_status', ['pending', 'paid', 'expired', 'failed'])->default('pending')->after('xendit_external_id');
            $table->timestamp('payment_requested_at')->nullable()->after('payment_status');
            $table->json('xendit_callback_data')->nullable()->after('payment_requested_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'xendit_invoice_id',
                'xendit_invoice_url', 
                'xendit_external_id',
                'payment_status',
                'payment_requested_at',
                'xendit_callback_data'
            ]);
        });
    }
};
