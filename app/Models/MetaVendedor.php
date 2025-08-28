<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MetaVendedor extends Model
{
    //
    use HasUuids;

    protected $table = 'metas_vendedors';

    protected $fillable = [
        'tenant_id',
        'month',
        'year',
        'vendedor_id',
        'meta',
        'quant',
        'grupo_id',
    ];

    public function vendedor()
    {
        return $this->belongsTo(Vendedor::class, 'vendedor_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }
}
