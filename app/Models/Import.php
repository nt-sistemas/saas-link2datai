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
        'message',

    ];

    protected $casts = [
        'data' => 'array',
        'is_processed' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function atualizarStatusImportacaoPorRegistro(Venda $venda)
    {

        $import = self::where('tenant_id', $venda->tenant_id)
            ->where('id', $venda->import_id)
            ->first();

        if ($import) {
            $import->is_processed = true;
            $import->message = 'Registro processado com sucesso.';
            $import->save();
        }
    }
}
