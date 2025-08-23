<div>
    @php
            $breadcrumbs = [
                [
                    'icon' => 'o-rectangle-group',
                    'link' => '/admin',
                ],
                [
                    'label' => 'Pagina',
                    'link' => '#default',
                    'icon' => 's-building-office-2',
                ],
            ];
        @endphp

    <x-breadcrumbs :items="$breadcrumbs" separator="o-slash" class="mb-4" />
    <x-header title="Pagina" separator />
    {{-- Care about people's approval and you will be their prisoner. --}}
</div>
