<x-filament::page>
    <form wire:submit="save">
        {{ $this->form }}

        <div>
            <x-filament::button type="submit" class="w-full flex my-8">
                Salvar
            </x-filament::button>
        </div>
    </form>
    <div>
        {{ $this->table }}
    </div>
</x-filament::page>
