<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'race_category_id',
        'jersey_size_id',
        'registration_fee',
        'registration_date',
        'status',
        'payment_status',
        'ticket_type_id',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'xendit_external_id',
        'payment_method',
        'paid_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'paid_at' => 'datetime',
        'registration_fee' => 'decimal:2'
    ];

    /**
     * Get the user that owns the registration.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the race category for the registration.
     */
    public function raceCategory()
    {
        return $this->belongsTo(RaceCategory::class);
    }

    /**
     * Get the jersey size for the registration.
     */
    public function jerseySize()
    {
        return $this->belongsTo(JerseySize::class);
    }

    /**
     * Get the ticket type for the registration.
     */
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }

    /**
     * Scope a query to only include paid registrations.
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    /**
     * Scope a query to only include pending registrations.
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope a query to only include active registrations.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
