<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::query()->firstOrCreate(
            ['name' => 'admin'],
            ['description' => 'Administra el catalogo y los movimientos del almacen'],
        );

        $staff = Role::query()->firstOrCreate(
            ['name' => 'almacenista'],
            ['description' => 'Consulta el almacen y registra entradas y salidas'],
        );

        $admin->permissions()->sync(Permission::query()->pluck('id'));

        $staffPermissions = Permission::query()
            ->where(function ($query) {
                $query->where('name', 'like', '%.view')
                    ->orWhere('name', 'movements.create');
            })
            ->pluck('id');

        $staff->permissions()->sync($staffPermissions);

        User::query()->where('email', 'admin@example.com')->update([
            'role_id' => $admin->id,
        ]);

        User::query()->firstOrCreate(
            ['email' => 'almacenista@example.com'],
            [
                'name' => 'Almacenista',
                'password' => 'password',
                'role_id' => $staff->id,
            ],
        );
    }
}
