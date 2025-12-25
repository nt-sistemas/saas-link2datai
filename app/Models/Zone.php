<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    /** @use HasFactory<\Database\Factories\ZoneFactory> */
    use HasFactory;

    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'name',
        'description',
    ];

    public function filials()
    {
        return $this->belongsToMany(Filial::class, 'filial_zone', 'zone_id', 'filial_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
