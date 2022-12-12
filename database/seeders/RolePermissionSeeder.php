<?php

namespace Database\Seeders;

use App\Models\MeasurementUnit;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $permissions = [
            'create',
            'update',
            'view',
            'delete'

        ];

        $roles = [
            [
                'role' => 'admin',
                'permissions' => [
                    'create',
                    'update',
                    'view',
                    'delete'        
                ]
            ],
            [
                'role' => 'basic-user',
                'permissions' => [
                    'create',
                    'view'
                ]
            ],
            [
                'role' => 'premium-user',
                'permissions' => [
                    'create',
                    'update',
                    'view',
                ]
            ],
        ];

        $allRoles = Role::all()->pluck('name')->toArray();

        foreach($permissions as $permission)
        {
            try {
                Permission::create(['name' => $permission]);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }

        foreach($roles as $role)
        {
            try {
                if(!in_array($role['role'], $allRoles))
                {
                    $roleObj = Role::create(['name' => $role['role']]);
                } else {
                    $roleObj = Role::where('name', $role['role'])->first();
                }
                $roleObj->syncPermissions($role['permissions']);
            } catch (Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
