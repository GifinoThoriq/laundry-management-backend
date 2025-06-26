<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        //create roles
        $admin = Role::create(['name' => 'admin']);
        $washer = Role::create(['name' => 'washer']);
        $cashier = Role::create(['name' => 'cashier']);
        $customer = Role::create(['name' => 'customer']);

        //create permissions
        $permissions = [
            'manage employees',
            'manage customers',
            'manage payments',
            'manage clothes',
            'employee site',
            'customer site'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        //assign permission to roles
        $admin->syncPermissions($permissions); // all permissions
        $washer->syncPermissions(['manage clothes']);
        $cashier->syncPermissions(['manage payments']);
        $customer->syncPermissions(['customer site']);

        //assign role to user
        $user = User::find(1);//find by id
        $user->assignRole('admin');
    }
}
