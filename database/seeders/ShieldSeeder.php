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

        $rolesWithPermissions = '[{"name":"super admin","guard_name":"web","permissions":
        ["view_area::comun","view_any_area::comun","create_area::comun","update_area::comun","restore_area::comun","restore_any_area::comun","replicate_area::comun","reorder_area::comun","delete_area::comun","delete_any_area::comun","force_delete_area::comun","force_delete_any_area::comun","view_departamento","view_any_departamento","create_departamento","update_departamento","restore_departamento","restore_any_departamento","replicate_departamento","reorder_departamento","delete_departamento","delete_any_departamento","force_delete_departamento","force_delete_any_departamento","view_factura","view_any_factura","create_factura","update_factura","restore_factura","restore_any_factura","replicate_factura","reorder_factura","delete_factura","delete_any_factura","force_delete_factura","force_delete_any_factura","view_nomina","view_any_nomina","create_nomina","update_nomina","restore_nomina","restore_any_nomina","replicate_nomina","reorder_nomina","delete_nomina","delete_any_nomina","force_delete_nomina","force_delete_any_nomina","view_pago","view_any_pago","create_pago","update_pago","restore_pago","restore_any_pago","replicate_pago","reorder_pago","delete_pago","delete_any_pago","force_delete_pago","force_delete_any_pago","view_role","view_any_role","create_role","update_role","delete_role","delete_any_role","view_ticket","view_any_ticket","create_ticket","update_ticket","restore_ticket","restore_any_ticket","replicate_ticket","reorder_ticket","delete_ticket","delete_any_ticket","force_delete_ticket","force_delete_any_ticket","view_user","view_any_user","create_user","update_user","restore_user","restore_any_user","replicate_user","reorder_user","delete_user","delete_any_user","force_delete_user","force_delete_any_user","page_CustomChatifyPage","page_NotificacionesPage","page_MyProfilePage","page_BannerManagerPage","widget_ChartsMultiWidget","widget_CustomMultiWidget","widget_FacturaPagos","widget_StatsMultiWidget","widget_ConsumoStatsWidget","widget_FacturaRevenueChartWidget","widget_ConsumoTrendChartWidget","widget_NominaExpenseChartWidget","widget_PagoMethodChartWidget","widget_RecentNotificationsWidget","widget_HighConsumptionWidget","widget_ActiveReservationsWidget","widget_PendingTicketsWidget"]},{"name":"admin","guard_name":"web","permissions":["view_area::comun","view_any_area::comun","create_area::comun","update_area::comun","restore_area::comun","restore_any_area::comun","replicate_area::comun","reorder_area::comun","delete_area::comun","delete_any_area::comun","force_delete_area::comun","force_delete_any_area::comun","view_departamento","view_any_departamento","view_factura","view_any_factura","update_factura","restore_factura","reorder_factura","view_nomina","view_any_nomina","create_nomina","update_nomina","replicate_nomina","reorder_nomina","view_pago","view_any_pago","create_pago","update_pago","restore_pago","restore_any_pago","replicate_pago","reorder_pago","view_ticket","view_any_ticket","create_ticket","update_ticket","restore_ticket","restore_any_ticket","replicate_ticket","reorder_ticket","delete_ticket","delete_any_ticket","force_delete_ticket","force_delete_any_ticket","view_user","page_CustomChatifyPage","page_NotificacionesPage","page_MyProfilePage","page_BannerManagerPage","widget_ChartsMultiWidget","widget_FacturaPagos","widget_ConsumoStatsWidget","widget_PagoMethodChartWidget","widget_RecentNotificationsWidget"]}]';
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
