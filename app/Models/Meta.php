<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    /** @use HasFactory<\Database\Factories\MetaFactory> */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'tenant_id',
        'grupo_id',
        'filial_id',
        'vendedor_id',
        'ano',
        'mes',
        'valor_meta',
        'quantidade',
    ];

    protected $casts = [
        'valor_meta' => 'decimal:2',
        'quantidade' => 'integer',

    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class);
    }
}
