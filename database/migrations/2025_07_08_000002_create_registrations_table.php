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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->enum('category', ['5K', '10K', '21K']);
            $table->foreignId('ticket_type_id')->constrained()->onDelete('cascade');
            $table->decimal('price', 10, 2);
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('xendit_invoice_id')->nullable();
            $table->string('xendit_payment_id')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('whatsapp_verified_at')->nullable();
            $table->string('registration_number')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['payment_status', 'is_active']);
            $table->index(['category', 'payment_status']);
            $table->index('xendit_invoice_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
