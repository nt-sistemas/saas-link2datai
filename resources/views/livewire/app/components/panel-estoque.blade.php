<div class="bg-white rounded-lg shadow-md p-4">
    <h2 class="text-lg font-semibold mb-4">Grupo de Estoque</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach ($data as $item)
            <a href="{{ route('app.grupo-estoque', $item['tipo']->id) }}">
                <x-card
                    class="flex w-full justify-between shadow rounded bg-gray-300 p-2 hover:bg-gray-400 transition-colors">
                    <span class="text-md font-bold italic text-primary text-center">{{ $item['tipo']->name }}</span>
                    <x-menu-separator />
                    <div class="flex flex-col items-center">
                        <p class="text-lg font-bold text-primary">R$ {{ number_format($item['total'], 2, ',', '.') }}</p>
                        <p class="text-sm text-gray-600 italic font-bold">Quantidade: {{ $item['quantidade'] }}</p>
                    </div>

                </x-card>
            </a>
        @endforeach
    </div>

</div>
