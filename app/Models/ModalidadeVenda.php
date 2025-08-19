<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModalidadeVenda extends Model
{
    /** @use HasFactory<\Database\Factories\ModalidadeVendaFactory> */
    use HasFactory;
    use HasUuids;

    protected $table = 'modalidade_vendas';

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
