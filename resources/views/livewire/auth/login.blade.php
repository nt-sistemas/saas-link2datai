<div class="flex w-full h-screen">
    <div class="bg-base-100 flex gap-4 flex-col w-full h-full justify-center items-center">
        <div class="w-2/3 lg:w-1/3">
            <img src="{{asset('/assets/logo.svg')}}" alt="link2datai logo"/>
        </div>
        <div class=" w-full lg:w-1/3 p-4">
            <x-form wire:submit="login">
                <x-input label="Digite seu E-mail" wire:model="email" class="w-full"/>
                <x-input label="Digite sua Senha" wire:model="password" type="password" class="w-full"/>

                <x-slot:actions>
                    <div class="flex flex-col w-full gap-2 justify-center">
                        <x-button label="Acessar" class="btn-primary" type="submit" spinner="login"/>
                        <a href="#" class="text-primary text-center font-bold hover:text-blue-700 underline">Esqueci
                            minha senha</a>

                    </div>
                </x-slot:actions>
            </x-form>

        </div>

    </div>
    <div class="bg-gray-400 w-full h-full hidden lg:flex flex-col justify-center items-center">
        <div class="w-full flex p-4 justify-center items-center">
            <img src="{{asset('/assets/bi.svg')}}" alt="Image BI"/>
        </div>
    </div>
</div>
