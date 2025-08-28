<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MetaFilial extends Model
{
    //
    use HasUuids;

    protected $table = 'metas_filiais';

    protected $fillable = [
        'tenant_id',
        'filial_id',
        'year',
        'month',
        'meta',
        'quant',
        'grupo_id',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function filial()
    {
        return $this->belongsTo(Filial::class);
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }


}
