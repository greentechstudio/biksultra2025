<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'entity_id', 
        'field_name',
        'old_value',
        'new_value',
        'user_id',
        'ip_address',
        'user_agent',
        'additional_data',
        'reason'
    ];

    protected $casts = [
        'old_value' => 'decimal:2',
        'new_value' => 'decimal:2',
        'additional_data' => 'array'
    ];

    /**
     * Relationship to user who made the change
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log a price change
     */
    public static function logPriceChange(
        string $entityType,
        int $entityId,
        string $fieldName,
        $oldValue,
        $newValue,
        ?int $userId = null,
        ?string $reason = null,
        array $additionalData = []
    ) {
        return self::create([
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'field_name' => $fieldName,
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'additional_data' => $additionalData,
            'reason' => $reason
        ]);
    }
}
