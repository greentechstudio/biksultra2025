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
        Schema::create('price_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('entity_type'); // 'ticket_type', 'user', etc.
            $table->unsignedBigInteger('entity_id');
            $table->string('field_name'); // 'price', 'registration_fee', etc.
            $table->decimal('old_value', 10, 2)->nullable();
            $table->decimal('new_value', 10, 2);
            $table->unsignedBigInteger('user_id')->nullable(); // Who made the change
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->json('additional_data')->nullable(); // Extra context
            $table->string('reason')->nullable(); // Why the change was made
            $table->timestamps();
            
            $table->index(['entity_type', 'entity_id']);
            $table->index(['created_at']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('price_audit_logs');
    }
};
