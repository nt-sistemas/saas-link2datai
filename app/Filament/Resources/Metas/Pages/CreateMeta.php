<?php

namespace App\Filament\Resources\Metas\Pages;

use App\Filament\Resources\Metas\MetaResource;
use App\Models\Meta;
use App\Models\Venda;
use App\Models\Vendedor;
use Filament\Resources\Pages\CreateRecord;

class CreateMeta extends CreateRecord
{
    protected static string $resource = MetaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {


        $data['tenant_id'] = auth()->user()->tenant_id;


        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        $vendedores_id = $data['vendedor_id'];

        foreach ($vendedores_id as $vendedor_id) {
            $vendedor = Vendedor::find($vendedor_id);

            $meta_existente = Meta::where('tenant_id', auth()->user()->tenant_id)
                ->where('grupo_id', $data['grupo_id'])
                ->where('filial_id', $vendedor->filial_id)
                ->where('vendedor_id', $vendedor->id)
                ->where('ano', $data['ano'])
                ->where('mes', $data['mes'])
                ->first();

            if ($meta_existente) {
                continue; // Pula para o próximo vendedor se a meta já existir
            }

            Meta::create([
                'tenant_id' => auth()->user()->tenant_id,
                'grupo_id' => $data['grupo_id'],
                'filial_id' => $vendedor->filial_id,
                'vendedor_id' => $vendedor->id,
                'ano' => $data['ano'],
                'mes' => $data['mes'],
                'valor_meta' => $data['valor_meta'],
                'quantidade' => $data['quantidade'],
            ]);
        }
        return Meta::latest()->first();
    }
}
