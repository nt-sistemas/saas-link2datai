<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Import extends Model
{
    protected $connection = 'mongodb';

    protected $fillable = [
        'tenant_id',
        'data_pedido',
        'numero_pedido',
        'data',
        'is_processed',
        'message_error',
    ];

    protected $casts = [
        'data' => 'array',
        'is_processed' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
