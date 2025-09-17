<?php

namespace App\Filament\Pages;

use App\Enum\UploadStatusEnum;
use App\Models\Upload;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;


class UploadDatasys extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public array|null $attachment = null;
    public string|null $filename = null;
    protected string $view = 'filament.pages.upload-datasys';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedArrowUpOnSquare;

    protected static ?string $recordTitleAttribute = 'upload-datasys';
    protected static ?string $modelLabel = 'Upload Datasys';
    protected static ?string $pluralModelLabel = 'Uploads Datasys';
    protected static ?string $navigationLabel = 'Upload';




    public function mount(): void
    {
        $this->form->fill($this->getRecord());
    }

    /**
     * @throws \Exception
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                FileUpload::make('attachment')
                    ->label('Upload Arquivo Excel')
                    ->disk('public')
                    ->directory('uploads/' . auth()->user()->tenant_id . '/imports')
                    ->storeFileNamesIn('filename')

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Upload::query()->where('tenant_id', auth()->user()->tenant_id)->latest())
            ->columns([
                TextColumn::make('filename')
                    ->label('Nome do Arquivo')
                    ->limit(50),
                IconColumn::make('status')
                    ->icon(fn(string $state): Heroicon => match ($state) {
                        'pending', 'processing' => Heroicon::Clock,
                        'pocessed' => Heroicon::OutlinedCheckCircle,
                        'failed' => Heroicon::OutlinedXCircle,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'blue',
                        'processed' => 'success',
                        'failed' => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->label('Enviado Em')
                    ->dateTime('d/m/Y H:i:s'),

            ])->recordActions([
                Action::make('download')
                    ->label('Download')
                    ->icon(Heroicon::OutlinedArrowDownOnSquare)
                    ->url(fn(Upload $record): string => asset('storage/' . $record->attachment))
                    ->openUrlInNewTab()
                    ->color('success')
                    ->requiresConfirmation()
                    ->tooltip('Baixar arquivo'),
            ])->filters([
                //
            ])->headerActions([
                //
            ])->bulkActions([
                //
            ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $uploadExists = Upload::where('tenant_id', auth()->user()->tenant_id)
            ->where('filename', $data['filename'])
            ->first();

        if ($uploadExists) {
            Notification::make()
                ->danger()
                ->title('Arquivo Já foi enviado anteriormente')
                ->send();
            return;
        }

        Upload::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->user()->id,
            'filename' => $data['filename'],
            'attachment' => $data['attachment'],
            'rows' => 0,
            'status' => 'pending',
        ]);

        Notification::make()
            ->success()
            ->title('Arquivo enviado com sucesso')
            ->send();

        $this->redirect('/admin/upload-datasys');
    }

    public function getRecord()
    {
        ds($this);
        return [];
    }
}
