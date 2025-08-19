<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filial extends Model
{
    /** @use HasFactory<\Database\Factories\FilialFactory> */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
