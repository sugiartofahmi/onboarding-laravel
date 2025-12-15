<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define modules with display names
        $modules = [
            'category' => 'Category',
            'product' => 'Product',
            'stock_movement' => 'Stock Movement',
            'sales_order' => 'Sales Order',
            'user' => 'User',
            'role' => 'Role',
            'permission' => 'Permission',
        ];

        // Define actions with display names
        $actions = [
            'create' => 'Create',
            'read' => 'Read',
            'update' => 'Update',
            'delete' => 'Delete',
        ];

        // Create permissions
        foreach ($modules as $moduleGuard => $moduleName) {
            foreach ($actions as $actionGuard => $actionName) {
                Permission::create([
                    'name' => "{$actionName} {$moduleName}",
                    'guard_name' => "{$actionGuard}_{$moduleGuard}",
                ]);
            }
        }

        // Create roles
        $adminRole = Role::create([
            'name' => 'Admin',
            'guard_name' => 'admin',
        ]);

        $staffRole = Role::create([
            'name' => 'Staff',
            'guard_name' => 'staff',
        ]);

        $viewerRole = Role::create([
            'name' => 'Viewer',
            'guard_name' => 'viewer',
        ]);

        // Admin: full access (all permissions)
        $adminRole->permissions()->attach(
            Permission::all()->pluck('id')->toArray()
        );

        // Staff: CRUD product, CRUD stock_movement only
        $staffPermissions = [
            'create_product',
            'read_product',
            'update_product',
            'delete_product',
            'create_stock_movement',
            'read_stock_movement',
            'update_stock_movement',
            'delete_stock_movement',
        ];
        $staffRole->permissions()->attach(
            Permission::whereIn('guard_name', $staffPermissions)->pluck('id')->toArray()
        );

        // Viewer: read-only (all read permissions)
        $viewerRole->permissions()->attach(
            Permission::where('guard_name', 'like', 'read_%')->pluck('id')->toArray()
        );
    }
}
