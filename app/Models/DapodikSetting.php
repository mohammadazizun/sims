<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DapodikSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_url',
        'api_key',
        'fetch_endpoint',
        'push_endpoint',
        'active',
        'public_search_enabled',
    ];

    protected $casts = [
        'active' => 'boolean',
        'public_search_enabled' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
