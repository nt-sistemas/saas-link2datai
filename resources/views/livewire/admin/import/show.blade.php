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
    {{-- Do your work, then step back. --}}
    <x-table :headers="$headers" :rows="$this->data" striped @row-click="alert($event.detail.name)" />

</div>
