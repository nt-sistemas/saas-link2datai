<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    /** @use HasFactory<\Database\Factories\VendedorFactory> */
    use HasFactory;
    use HasUuids;

    protected $table = 'vendedores';

    protected $fillable = [
        'tenant_id',
        'document',
        'name',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function vendas()
    {
        return $this->hasMany(Venda::class, 'vendedor_id');
    }
}
