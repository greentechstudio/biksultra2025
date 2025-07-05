<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'bib_name',
        'email',
        'phone',
        'password',
        'role',
        'whatsapp_verified',
        'whatsapp_verified_at',
        'payment_confirmed',
        'payment_confirmed_at',
        'payment_amount',
        'payment_method',
        'payment_notes',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'xendit_external_id',
        'payment_status',
        'payment_requested_at',
        'xendit_callback_data',
        'status',
        // Detail registration fields
        'gender',
        'birth_place',
        'birth_date',
        'address',
        'jersey_size',
        'race_category',
        'whatsapp_number',
        'emergency_contact_1',
        'emergency_contact_2',
        'group_community',
        'blood_type',
        'occupation',
        'medical_history',
        'event_source',
        'profile_edited',
        'profile_edited_at',
        'edit_notes',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'whatsapp_verified_at' => 'datetime',
            'payment_confirmed_at' => 'datetime',
            'payment_requested_at' => 'datetime',
            'xendit_callback_data' => 'array',
            'birth_date' => 'date',
            'whatsapp_verified' => 'boolean',
            'payment_confirmed' => 'boolean',
            'password' => 'hashed',
            'profile_edited' => 'boolean',
            'profile_edited_at' => 'datetime',
        ];
    }

    /**
     * Get the race category for this user.
     */
    public function raceCategory()
    {
        return $this->belongsTo(RaceCategory::class, 'race_category', 'name');
    }

    /**
     * Get the registration fee based on race category.
     */
    public function getRegistrationFeeAttribute()
    {
        // Refresh relationship untuk memastikan data terbaru
        $this->unsetRelation('raceCategory');
        $this->load('raceCategory');
        
        if ($this->raceCategory) {
            return (float) $this->raceCategory->price;
        }
        return (float) config('xendit.registration_fee', 150000); // Default fallback
    }

    /**
     * Get the jersey size for this user.
     */
    public function jerseySize()
    {
        return $this->belongsTo(JerseySize::class, 'jersey_size', 'code');
    }

    /**
     * Get the blood type for this user.
     */
    public function bloodType()
    {
        return $this->belongsTo(BloodType::class, 'blood_type', 'name');
    }

    /**
     * Get the event source for this user.
     */
    public function eventSource()
    {
        return $this->belongsTo(EventSource::class, 'event_source', 'name');
    }

    // Check if user can edit profile
    public function canEditProfile()
    {
        return !$this->profile_edited;
    }

    // Mark profile as edited
    public function markProfileAsEdited($notes = null)
    {
        $this->update([
            'profile_edited' => true,
            'profile_edited_at' => now(),
            'edit_notes' => $notes
        ]);
    }

    // Role-based access methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    public function hasRole($role)
    {
        return $this->role === $role;
    }
}
