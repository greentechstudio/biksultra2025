<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TicketType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'race_category_id',
        'price',
        'start_date',
        'end_date',
        'quota',
        'registered_count',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Get the race category for this ticket type
     */
    public function raceCategory()
    {
        return $this->belongsTo(RaceCategory::class);
    }

    /**
     * Get the category name through race category relationship
     */
    public function getCategoryAttribute()
    {
        return $this->raceCategory->name ?? null;
    }

    /**
     * Check if ticket type is currently active
     */
    public function isCurrentlyActive(): bool
    {
        $now = Carbon::now();
        return $this->is_active && 
               $now->between($this->start_date, $this->end_date) &&
               !$this->isQuotaExceeded();
    }

    /**
     * Check if quota is exceeded
     */
    public function isQuotaExceeded(): bool
    {
        if ($this->quota === null) {
            return false; // Unlimited quota
        }
        
        return $this->registered_count >= $this->quota;
    }

    /**
     * Get remaining quota
     */
    public function getRemainingQuota(): ?int
    {
        if ($this->quota === null) {
            return null; // Unlimited
        }
        
        return max(0, $this->quota - $this->registered_count);
    }

    /**
     * Get current active ticket type for a category
     */
    public static function getCurrentTicketType(string $category): ?self
    {
        $now = Carbon::now();
        
        return self::whereHas('raceCategory', function ($query) use ($category) {
                      $query->where('name', $category);
                  })
                  ->where('is_active', true)
                  ->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now)
                  ->whereRaw('(quota IS NULL OR registered_count < quota)')
                  ->orderBy('start_date', 'asc')
                  ->first();
    }

    /**
     * Get all available ticket types for a category
     */
    public static function getAvailableTicketTypes(string $category): \Illuminate\Database\Eloquent\Collection
    {
        $now = Carbon::now();
        
        return self::whereHas('raceCategory', function ($query) use ($category) {
                      $query->where('name', $category);
                  })
                  ->where('is_active', true)
                  ->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now)
                  ->whereRaw('(quota IS NULL OR registered_count < quota)')
                  ->orderBy('start_date', 'asc')
                  ->get();
    }

    /**
     * Get time remaining until ticket type ends
     */
    public function getTimeRemaining(): array
    {
        $now = Carbon::now();
        $endDate = Carbon::parse($this->end_date);
        
        if ($now->greaterThan($endDate)) {
            return [
                'expired' => true,
                'days' => 0,
                'hours' => 0,
                'minutes' => 0,
                'seconds' => 0
            ];
        }
        
        $diff = $now->diff($endDate);
        
        return [
            'expired' => false,
            'days' => $diff->days,
            'hours' => $diff->h,
            'minutes' => $diff->i,
            'seconds' => $diff->s
        ];
    }

    /**
     * Increment registered count
     */
    public function incrementRegisteredCount(): bool
    {
        if ($this->isQuotaExceeded()) {
            return false;
        }
        
        $this->increment('registered_count');
        return true;
    }

    /**
     * Decrement registered count (for cancellations)
     */
    public function decrementRegisteredCount(): void
    {
        $this->decrement('registered_count');
    }

    /**
     * Get users who registered with this ticket type
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
