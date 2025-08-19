<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Tenant;
use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Permission::insert([
            [
                'id' => Str::uuid()->toString(),
                'key' => 'be an admin'
            ],
            [
                'id' => Str::uuid()->toString(),
                'key' => 'be a manager'
            ],
            [
                'id' => Str::uuid()->toString(),
                'key' => 'be a user'
            ],

        ]);

        $tenant = Tenant::factory()->create([
            'name' => 'Link2B Telecom',
            'slug' => 'link2b',
            'phone' => '(11) 99999-9999',
            'email' => 'imagem@link2b.com.br',
            'active' => true,
        ]);


        $user = User::factory()->create([
            'name' => 'Link2B',
            'email' => 'admin@link2b.com.br',
            'password' => bcrypt('password'),
        ]);

        $userManager = User::create([
            'name' => 'Imagem Telecom',
            'email' => 'imagem@link2b.com.br',
            'password' => bcrypt('password'),
            'tenant_id' => $tenant->id,
        ]);
        $userManager->givePermissionTo('be a manager');

        $user->givePermissionTo('be an admin');


    }
}
