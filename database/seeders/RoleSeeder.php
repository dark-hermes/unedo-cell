<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                "name" => "admin",
                "permissions" => [
                    "user" => [
                        "create",
                        "list",
                        "update",
                        "delete",
                    ],
                ],
            ],
            [
                "name" => "user",
                "permissions" => [],
            ]
        ];

        foreach ($roles as $role) {
            $newRole = Role::firstOrCreate(["name" => $role["name"]]);

            foreach ($role["permissions"] as $model => $permissions) {
                foreach ($permissions as $permission) {
                    $permissionName = "{$permission} {$model}";

                    // Pastikan permission ada sebelum diberikan
                    $perm = Permission::firstOrCreate(["name" => $permissionName, "guard_name" => "web"]);

                    // Berikan permission ke role
                    $newRole->givePermissionTo($perm);
                }
            }
        }
    }
}
