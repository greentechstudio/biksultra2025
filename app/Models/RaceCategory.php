<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RaceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Get the users for this race category.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'race_category', 'name');
    }
}
