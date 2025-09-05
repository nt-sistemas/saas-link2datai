<div class=" flex flex-col gap-2 ">
    <div class="flex flex-col">
        <div class="flex gap-2 justify-between">
            <div class="flex flex-col bg-primary rounded-lg p-2">
                <span class="text-xs text-white">Valor Total</span>
                <span class="text-xl font-bold text-white p-2 ">R$ 1.0000.0000.000,00</span>
            </div>
            <div class="flex flex-col bg-primary rounded-lg p-2">
                <span class="text-xs text-white">Quantidade Total</span>
                <span class="text-xl font-bold text-white p-2 ">100000</span>
            </div>


        </div>
        <div class="flex w-full gap-2 justify-between mt-2">
            <div class="flex justify-between items-center bg-secondary p-2 rounded-lg w-full">
                <div class="flex flex-col bg-secondary ">
                    <span class="text-xs text-gray-500">Meta Valor</span>
                    <span class="text-sm font-bold text-gray-800">R$ 2.000.000,00</span>
                </div>
                <div class="flex flex-col justify-center items-center ">
                    <span class="text-md font-bold text-gray-800">99%</span>
                </div>

            </div>
            <div class="flex justify-between items-center  bg-secondary p-2 rounded-lg w-full">
                <div class="flex flex-col bg-secondary ">
                    <span class="text-xs text-gray-500">Meta Quantidade</span>
                    <span class="text-sm font-bold text-gray-800">2000</span>
                </div>
                <div class="flex flex-col justify-center items-center ">
                    <span class="text-md font-bold text-gray-800">99%</span>
                </div>

            </div>


        </div>


    </div>
    <div class="flex flex-col w-full">
        <div class="h-36 flex w-full">
            <x-chart wire:model="myChart" class="w-full"/>
        </div>
        <div class="h-36 flex w-full">
            <x-chart wire:model="myChart" class="w-full"/>
        </div>
    </div>


</div>

