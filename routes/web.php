<?php

use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Route;


Route::get('/login', \App\Livewire\Auth\Login::class)->name('login');
Route::get('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

Route::middleware('auth')->group(function () {
    //Route::get('/', \App\Livewire\App\Dashboard::class)->name('dashboard');
    Route::prefix('/')->name('app.')->group(function () {
        Route::get('', \App\Livewire\App\Dashboard::class)->name('dashboard');
        Route::get('filiais', \App\Livewire\App\Filiais\Main::class)->name('filiais');
        Route::get('filiais/{id}', \App\Livewire\App\Filiais\Show::class)->name('filiais.show');
        Route::get('vendedores', \App\Livewire\App\Vendedores\Main::class)->name('vendedores');
        Route::get('vendedores/{id}', \App\Livewire\App\Vendedores\Show::class)->name('vendedores.show');
        Route::get('movimentacao/{id}', \App\Livewire\App\Movimentacao\Main::class)->name('movimentacao');
        Route::get('grupo-estoque/{id}', \App\Livewire\App\GrupoEstoque\Main::class)->name('grupo-estoque');
    });

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');

        Route::prefix('tenants')->group(function () {
            Route::get('/', \App\Livewire\Admin\Tenants\Index::class)->name('tenants.index');
            Route::get('/create', \App\Livewire\Admin\Tenants\Create::class)->name('tenants.create');
            Route::get('/{id}/edit', \App\Livewire\Admin\Tenants\Edit::class)->name('tenants.edit');
        });
        Route::prefix('categories')->group(function () {
            Route::get('/', \App\Livewire\Admin\Categories\Index::class)->name('categories.index');
            Route::get('/create', \App\Livewire\Admin\Categories\Create::class)->name('categories.create');
            Route::get('/{id}/edit', \App\Livewire\Admin\Categories\Edit::class)->name('categories.edit');
        });

        Route::prefix('groups')->group(function () {
            Route::get('/', \App\Livewire\Admin\Groups\Index::class)->name('groups.index');
            Route::get('/create', \App\Livewire\Admin\Groups\Create::class)->name('groups.create');
            Route::get('/{id}/edit', \App\Livewire\Admin\Groups\Edit::class)->name('groups.edit');
        });

        Route::prefix('storage')->group(function () {
            Route::get('/', \App\Livewire\Admin\Storage\Index::class)->name('storage.index');
            Route::get('/create', \App\Livewire\Admin\Storage\Create::class)->name('storage.create');
            Route::get('/{id}/edit', \App\Livewire\Admin\Storage\Edit::class)->name('storage.edit');
        });

        Route::prefix('types')->group(function () {
            Route::get('/', \App\Livewire\Admin\Types\Index::class)->name('types.index');
            Route::get('/create', \App\Livewire\Admin\Types\Create::class)->name('types.create');
            Route::get('/{id}/edit', \App\Livewire\Admin\Types\Edit::class)->name('types.edit');
        });

        Route::prefix('panels')->group(function () {
            Route::get('/', \App\Livewire\Admin\Panels\Index::class)->name('panels.index');
            Route::get('/create', \App\Livewire\Admin\Panels\Create::class)->name('panels.create');
            Route::get('/{id}/edit', \App\Livewire\Admin\Panels\Edit::class)->name('panels.edit');
        });

        Route::prefix('groups')->group(function () {
            Route::get('/', \App\Livewire\Admin\Groups\Index::class)->name('groups.index');
            Route::get('/create', \App\Livewire\Admin\Groups\Create::class)->name('groups.create');
            Route::get('/{id}/edit', \App\Livewire\Admin\Groups\Edit::class)->name('groups.edit');
        });


    });
});