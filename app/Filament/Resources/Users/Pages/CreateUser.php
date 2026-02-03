<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userExists = User::where('email', $data['email'])->get();
        $data['tenant_id'] = auth()->user()->tenant_id;

        $data['password'] = bcrypt($data['password']);

        if (! $userExists->isEmpty()) {
            \Filament\Notifications\Notification::make()
                ->title('Já existe um usuário com este e-mail.')
                ->danger()
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
