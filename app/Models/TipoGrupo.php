<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class TipoGrupo extends Model
{
    //
    use HasUuids;

    protected $table = 'tipo_grupos';
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function grupos()
    {
        return $this->hasMany(Grupo::class);
    }
}
