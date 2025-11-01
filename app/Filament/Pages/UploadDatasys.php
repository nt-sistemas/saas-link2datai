<?php

namespace App\Filament\Pages;

use App\Enum\UploadStatusEnum;
use App\Jobs\ExcelMongoDBJob;
use App\Models\Import;
use App\Models\Upload;
use Carbon\Carbon;
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
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Utils;
use Illuminate\Support\Facades\Date;
use League\Csv\Reader;
use League\Csv\Statement;
use Livewire\WithFileUploads;

class UploadDatasys extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;
    use WithFileUploads;

    public $file;

    public array|null $attachment = null;
    public string|null $filename = null;
    protected string $view = 'filament.pages.upload-datasys';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedArrowUpOnSquare;

    protected static ?string $recordTitleAttribute = 'upload-datasys';
    protected static ?string $modelLabel = 'Upload Datasys';
    protected static ?string $pluralModelLabel = 'Uploads Datasys';
    protected static ?string $navigationLabel = 'Upload';
    protected static ?int $navigationSort = 20;




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
                TextColumn::make('rows')
                    ->label('Quantidade de Linhas'),

                IconColumn::make('status')
                    ->icon(fn(string $state): Heroicon => match ($state) {
                        'pending', 'processing' => Heroicon::Clock,
                        'completed' => Heroicon::OutlinedCheckCircle,
                        'failed' => Heroicon::OutlinedXCircle,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'processing' => 'blue',
                        'completed' => 'success',
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

        /*$uploadExists = Upload::where('tenant_id', auth()->user()->tenant_id)
            ->where('filename', $data->getClientOriginalName())
            ->first();

        if ($uploadExists) {
            Notification::make()
                ->danger()
                ->title('Arquivo Já foi enviado anteriormente')
                ->send();
            return;
        } //$this->form->getState();

        $client = new Client();
        $clientBaseUrl = env('LINK2B_ETL_API_URL', 'http://localhost:3000');

        $options = [
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => Utils::tryFopen($data->getRealPath(), 'r'),
                    'filename' => $data->getClientOriginalName(),
                    'headers'  => [
                        'Content-Type' => $data->getMimeType()
                    ]
                ],
                [
                    'name' => 'tenant_id',
                    'contents' => auth()->user()->tenant_id
                ],
                [
                    'name' => 'user_id',
                    'contents' => auth()->user()->id
                ]
            ]
        ];

        $request = new Request('POST', $clientBaseUrl . '/uploads');
        $client->sendAsync($request, $options)->wait();

        Notification::make()
            ->success()
            ->title('Arquivo enviado com sucesso')
            ->send();

        $this->redirect('/admin/upload-datasys');*/


        $uploadExists = Upload::where('tenant_id', auth()->user()->tenant_id)
            ->where('filename', $data['filename'])
            ->first();

        /*if ($uploadExists) {
            Notification::make()
                ->danger()
                ->title('Arquivo Já foi enviado anteriormente')
                ->send();
            return;
        }*/

        $filePath = storage_path('app/public/' . $data['attachment']);

        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setDelimiter(';');
        $csv->setHeaderOffset(0);

        $records = (new Statement())->process($csv);

        $batchInserts = [];

        Upload::create([
            'tenant_id' => auth()->user()->tenant_id,
            'user_id' => auth()->user()->id,
            'filename' => $data['filename'],
            'attachment' => $data['attachment'],
            'rows' => count($records),
            'status' => 'pending',
        ]);

        foreach ($records as $record) {
            $batchInserts[] = [
                'tenant_id' => auth()->user()->tenant_id,
                'filename' => $data['filename'],
                'data_pedido' => new Date($record['Data Pedido'], 'Y-m-d'),
                'numero_pedido' => $record['Número PV'],
                'data' => json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                'is_processed' => false,
            ];

            if (count($batchInserts) >= 1000) {
                ExcelMongoDBJob::dispatch($batchInserts);

                $batchInserts = [];
            }
        }

        if (!empty($batchInserts)) {
            ExcelMongoDBJob::dispatch($batchInserts);


            $batchInserts = [];
        }










        Notification::make()
            ->success()
            ->title('Arquivo enviado com sucesso')
            ->send();

        $this->redirect('/admin/upload-datasys');
    }

    public function getRecord()
    {

        return [];
    }

    public function placeholder()
    {
        return <<<'HTML'
                <div class="flex items-center justify-center h-screen">
                    <div class="p-4  animate-pulse max-w-sm w-full mx-auto">
                        <div>
                            <img src="{{asset('/assets/loading.svg')}}" alt="loading"/>

                        </div>
                    </div>
                </div>
            HTML;
    }
}
