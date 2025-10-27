<?php

namespace App\Filament\Resources\Imports\Pages;

use App\Filament\Resources\Imports\ImportResource;
use App\Jobs\ProcessImportJob;
use App\Models\Import;
use App\Models\Venda;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Process;
use MongoDB\Laravel\Eloquent\Model;

class EditImport extends EditRecord
{
    protected static string $resource = ImportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //DeleteAction::make(),
            Action::make('reprocessar_importacao')
                ->action(function () {
                                      $venda = Venda::where('import_id', $this->record->id)->first();
                    if ($venda) {
                        $this->record->message = 'Reprocessado manualmente pelo usuário ' . auth()->user()->name . ' em ' . Carbon::now()->format('d/m/Y H:i:s');
                        $this->record->save();

                        ProcessImportJob::dispatch($this->record, auth()->user()->tenant_id);

                        Notification::make()
                            ->title('Registro Reprocessado com Sucesso!')
                            ->success()
                            ->send();


                        $this->redirect($this->getResource()::getUrl('index'));
                    } else {
                        Notification::make()
                            ->title('Nenhuma venda encontrada para reprocessar.')
                            ->warning()
                            ->send();
                    }
                })
                ->label('Reprocessar Importação')

                ->color('success')
                ->icon('heroicon-o-arrow-up-tray'),
            Action::make('processar_importacao')
                ->action(function () {

                    ProcessImportJob::dispatch($this->record, auth()->user()->tenant_id);

                    Notification::make()
                        ->title('Registro Reprocessado com Sucesso!')
                        ->success()
                        ->send();


                    $this->redirect($this->getResource()::getUrl('index'));
                })
                ->label('Processar Manualmente')
                ->color('primary')
                ->icon('heroicon-o-arrow-path'),

        ];
    }
}
