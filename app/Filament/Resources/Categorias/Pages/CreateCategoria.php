<?php

namespace App\Filament\Resources\Categorias\Pages;

use App\Filament\Resources\Categorias\CategoriaResource;
use App\Models\Categoria;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Exceptions\Halt;

class CreateCategoria extends CreateRecord
{
    protected static string $resource = CategoriaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $categoriaExists = Categoria::query()
            ->where('name', $data['name'])
            ->where('tenant_id', auth()->user()->tenant_id)
            ->get();

        if ($categoriaExists->isNotEmpty()) {
            Notification::make()
                ->title('Já existe uma categoria com esse nome.')
                ->danger()
                ->send();
            throw new Halt;
        }

        $data['tenant_id'] = auth()->user()->tenant_id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
