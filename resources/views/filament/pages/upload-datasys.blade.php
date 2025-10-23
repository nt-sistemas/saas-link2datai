<x-filament::page>

    <form wire:submit="save">


        <div class="mb-4 flex gap-2 w-full">
            <div class="w-full flex flex-col">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="file_input">
                    Upload Arquivo em Excel(.xlsx):
                </label>
                <input
                    class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50
           dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
           file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold
           file:bg-blue-50 file:text-blue-950 hover:file:bg-blue-100"
                    wire:model="file" type="file" />
            </div>

            <button type="submit"
                class="mt-2 btn bg-blue-950 hover:bg-blue-800 p-2 rounded-lg w-1/3 text-white flex items-center justify-center gap-2">
                <x-filament::loading-indicator class="h-8 w-8" wire:loading />
                <span>Enviar</span>

            </button>
        </div>
    </form>
    <div>
        {{ $this->table }}
    </div>
</x-filament::page>
