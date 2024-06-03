<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BezhanSalleh\FilamentShield\Support\Utils;
use Spatie\Permission\PermissionRegistrar;

class ShieldSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $rolesWithPermissions = '[{"name":"super_admin","guard_name":"web","permissions":["view_campaign","view_any_campaign","create_campaign","update_campaign","restore_campaign","restore_any_campaign","replicate_campaign","reorder_campaign","delete_campaign","delete_any_campaign","force_delete_campaign","force_delete_any_campaign","view_donation","view_any_donation","create_donation","update_donation","restore_donation","restore_any_donation","replicate_donation","reorder_donation","delete_donation","delete_any_donation","force_delete_donation","force_delete_any_donation","view_donator","view_any_donator","create_donator","update_donator","restore_donator","restore_any_donator","replicate_donator","reorder_donator","delete_donator","delete_any_donator","force_delete_donator","force_delete_any_donator","view_institution","view_any_institution","create_institution","update_institution","restore_institution","restore_any_institution","replicate_institution","reorder_institution","delete_institution","delete_any_institution","force_delete_institution","force_delete_any_institution","view_item","view_any_item","create_item","update_item","restore_item","restore_any_item","replicate_item","reorder_item","delete_item","delete_any_item","force_delete_item","force_delete_any_item","view_news","view_any_news","create_news","update_news","restore_news","restore_any_news","replicate_news","reorder_news","delete_news","delete_any_news","force_delete_news","force_delete_any_news","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]},{"name":"panel_user","guard_name":"web","permissions":[]}]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            /** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($rolePlusPermissions as $rolePlusPermission) {
                $role = $roleModel::firstOrCreate([
                    'name' => $rolePlusPermission['name'],
                    'guard_name' => $rolePlusPermission['guard_name'],
                ]);

                if (! blank($rolePlusPermission['permissions'])) {
                    $permissionModels = collect($rolePlusPermission['permissions'])
                        ->map(fn ($permission) => $permissionModel::firstOrCreate([
                            'name' => $permission,
                            'guard_name' => $rolePlusPermission['guard_name'],
                        ]))
                        ->all();

                    $role->syncPermissions($permissionModels);
                }
            }
        }
    }

    public static function makeDirectPermissions(string $directPermissions): void
    {
        if (! blank($permissions = json_decode($directPermissions, true))) {
            /** @var Model $permissionModel */
            $permissionModel = Utils::getPermissionModel();

            foreach ($permissions as $permission) {
                if ($permissionModel::whereName($permission)->doesntExist()) {
                    $permissionModel::create([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }
            }
        }
    }
}
