<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionSeeder extends Seeder
{
    /**
     * List of applications to add.
     */
    private $permissions = [
        'role-list',
        'role-create',
        'role-edit',
        'role-delete',
        'user-list',
        'user-create',
        'user-edit',
        'user-delete'
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = Role::updateOrCreate([
            'name' => 'administrator'
        ], [
            'name' => 'administrator'
        ]);
        $guestRole = Role::updateOrCreate([
            'name' => 'guest'
        ], [
            'name' => 'guest'
        ]);

        $permissionDashboard = Permission::updateOrCreate([
            'name' => 'view_dashboard',
        ], [
            'name' => 'view_dashboard',
        ]);

        $permissionViewChart = Permission::updateOrCreate([
            'name' => 'view_chart',
        ], [
            'name' => 'view_chart',
        ]);

        foreach ($this->permissions as $permission) {
            $adminPermission = Permission::updateOrCreate(['name' => $permission], ['name' => $permission]);
            $adminRole->givePermissionTo($adminPermission);
        }

        $adminRole->givePermissionTo($permissionDashboard);
        $adminRole->givePermissionTo($permissionViewChart);
        $guestRole->givePermissionTo($permissionDashboard);

        $user = User::where('id', '57e80945-2334-4f56-9d96-b5fdee17f88d')->first();
        $user->assignRole('administrator');
    }
}
