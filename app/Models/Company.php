<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'domain',
        'db_connection',
        'status',
        'settings',
        'gib_username',
        'gib_password',
    ];

    protected $casts = [
        'settings' => 'array',
        'gib_password' => 'encrypted',
    ];

    /**
     * Get the city from company settings
     *
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->settings['company_details']['city'] ?? $this->settings['city'] ?? null;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
