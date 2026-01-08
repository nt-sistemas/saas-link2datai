<?php

namespace App\Filament\Resources\Imports\Tables;

use App\Jobs\ProcessImportJob;
use App\Models\Venda;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ImportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('numero_pedido')
                    ->label('Número do Pedido')
                    ->searchable(),
                TextColumn::make('data_pedido')
                    ->label('Data do Pedido')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('filename')
                    ->label('Nome do Arquivo')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('is_processed')
                    ->label('Processado')
                    ->sortable()
                    ->boolean(),
                TextColumn::make('message')
                    ->label('Mensagem')
                    ->searchable(),
                TextColumn::make('message_error')
                    ->label('Mensagem de Erro')
                    ->searchable(),

            ])
            ->defaultSort('data_pedido', direction: 'desc')
            ->filters([
                Filter::make('message_error')
                    ->label('Erros')
                    ->query(fn ($query) => $query->where('message_error', '!=', null)),
                Filter::make('data')
                    ->schema([
                        DatePicker::make('created_from')
                            ->label('Data de')
                            ->placeholder('Data de'),
                        DatePicker::make('created_until')
                            ->label('Data até')
                            ->placeholder('Data até'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {

                        $result = $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->where('data_pedido', '>=', Carbon::parse($date)->format('Y-m-d 0:00:00')),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->where('data_pedido', '<=', Carbon::parse($date)->format('Y-m-d 23:59:59')),
                            );

                        return $result;
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['created_from']) {
                            $indicators[] = 'De: '.Carbon::parse($data['created_from'])->format('d/m/Y');
                        }

                        if ($data['created_until']) {
                            $indicators[] = 'Até: '.Carbon::parse($data['created_until'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    Action::make('reprocessar_importacoes')
                        ->icon('heroicon-o-arrow-path')
                        ->label('Reprocessar Importações')
                        ->accessSelectedRecords()
                        ->requiresConfirmation()
                        ->action(function (Collection $records) {
                            foreach ($records as $record) {
                                $venda = Venda::where('import_id', $record->id)->first();
                                if ($venda) {
                                    $record->message = 'Reprocessado manualmente pelo usuário '.auth()->user()->name.' em '.Carbon::now()->format('d/m/Y H:i:s');
                                }
                                $record->save();
                                // Dispatch the job to process the import
                                ProcessImportJob::dispatch($record->tenant_id, [$record]);
                            }

                            Notification::make()
                                ->title('Importações selecionadas estão sendo reprocessadas.')
                                ->success()
                                ->send();
                        })
                        ->color('warning'),
                ]),
            ]);
    }
}
