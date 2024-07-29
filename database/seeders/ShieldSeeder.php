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

        $rolesWithPermissions = '
        [
            { 
            "name":"super_admin",
            "guard_name":"web",
            "permissions":
                ["view_bentuk::surat","view_any_bentuk::surat","create_bentuk::surat","update_bentuk::surat","restore_bentuk::surat","restore_any_bentuk::surat","replicate_bentuk::surat","reorder_bentuk::surat","delete_bentuk::surat","delete_any_bentuk::surat","force_delete_bentuk::surat","force_delete_any_bentuk::surat","view_gaji::berkala::asn","view_any_gaji::berkala::asn","create_gaji::berkala::asn","update_gaji::berkala::asn","restore_gaji::berkala::asn","restore_any_gaji::berkala::asn","replicate_gaji::berkala::asn","reorder_gaji::berkala::asn","delete_gaji::berkala::asn","delete_any_gaji::berkala::asn","force_delete_gaji::berkala::asn","force_delete_any_gaji::berkala::asn","view_gaji::berkala::militer","view_any_gaji::berkala::militer","create_gaji::berkala::militer","update_gaji::berkala::militer","restore_gaji::berkala::militer","restore_any_gaji::berkala::militer","replicate_gaji::berkala::militer","reorder_gaji::berkala::militer","delete_gaji::berkala::militer","delete_any_gaji::berkala::militer","force_delete_gaji::berkala::militer","force_delete_any_gaji::berkala::militer","view_gapok::asn","view_any_gapok::asn","create_gapok::asn","update_gapok::asn","restore_gapok::asn","restore_any_gapok::asn","replicate_gapok::asn","reorder_gapok::asn","delete_gapok::asn","delete_any_gapok::asn","force_delete_gapok::asn","force_delete_any_gapok::asn","view_gapok::militer","view_any_gapok::militer","create_gapok::militer","update_gapok::militer","restore_gapok::militer","restore_any_gapok::militer","replicate_gapok::militer","reorder_gapok::militer","delete_gapok::militer","delete_any_gapok::militer","force_delete_gapok::militer","force_delete_any_gapok::militer","view_golongan","view_any_golongan","create_golongan","update_golongan","restore_golongan","restore_any_golongan","replicate_golongan","reorder_golongan","delete_golongan","delete_any_golongan","force_delete_golongan","force_delete_any_golongan","view_korp","view_any_korp","create_korp","update_korp","restore_korp","restore_any_korp","replicate_korp","reorder_korp","delete_korp","delete_any_korp","force_delete_korp","force_delete_any_korp","view_pangkat","view_any_pangkat","create_pangkat","update_pangkat","restore_pangkat","restore_any_pangkat","replicate_pangkat","reorder_pangkat","delete_pangkat","delete_any_pangkat","force_delete_pangkat","force_delete_any_pangkat","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user"]
            },
            {
            "name":"panel_user",
            "guard_name":"web",
            "permissions":
                ["view_bentuk::surat","view_any_bentuk::surat","view_gaji::berkala::asn","view_any_gaji::berkala::asn","view_gaji::berkala::militer","view_any_gaji::berkala::militer","view_gapok::asn","view_any_gapok::asn","view_gapok::militer","view_any_gapok::militer"]
            },
            {
            "name":"panel_guest",
            "guard_name":"web",
            "permissions":
                ["view_bentuk::surat","view_any_bentuk::surat","view_gaji::berkala::asn","view_any_gaji::berkala::asn","view_gaji::berkala::militer","view_any_gaji::berkala::militer","view_gapok::asn","view_any_gapok::asn","view_gapok::militer","view_any_gapok::militer"]
            }
        ]';
        $directPermissions = '[]';

        static::makeRolesWithPermissions($rolesWithPermissions);
        static::makeDirectPermissions($directPermissions);

        $this->command->info('Shield Seeding Completed.');
    }

    protected static function makeRolesWithPermissions(string $rolesWithPermissions): void
    {
        if (! blank($rolePlusPermissions = json_decode($rolesWithPermissions, true))) {
            //** @var Model $roleModel */
            $roleModel = Utils::getRoleModel();
            //** @var Model $permissionModel */
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
            //** @var Model $permissionModel */
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
