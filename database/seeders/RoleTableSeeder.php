<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Finance operational permissions
        $financePermissions = Permission::whereIn('name', [
            'dashboard-access',
            'cash-management-access',
            'payment-requests-access',
            'master-data-access',
            'transactions-access',
            'approvals-access',
            'treasury-access',
            'reconciliation-access',
            'reports-access',
            'administration-access',
        ])->get();

        $financeStaff = Role::create(['name' => 'finance-staff']);
        $financeStaff->givePermissionTo([
            'dashboard-access',
            'cash-management-access',
            'payment-requests-access',
            'transactions-access',
            'reports-access',
        ]);

        $financeSupervisor = Role::create(['name' => 'finance-supervisor']);
        $financeSupervisor->givePermissionTo([
            'dashboard-access',
            'cash-management-access',
            'payment-requests-access',
            'transactions-access',
            'approvals-access',
            'reports-access',
        ]);

        $financeManager = Role::create(['name' => 'finance-manager']);
        $financeManager->givePermissionTo([
            'dashboard-access',
            'cash-management-access',
            'payment-requests-access',
            'master-data-access',
            'transactions-access',
            'approvals-access',
            'treasury-access',
            'reconciliation-access',
            'reports-access',
        ]);

        $cashierTreasury = Role::create(['name' => 'cashier-treasury']);
        $cashierTreasury->givePermissionTo([
            'dashboard-access',
            'cash-management-access',
            'payment-requests-access',
            'transactions-access',
            'treasury-access',
            'reports-access',
        ]);


        $regularUser = Role::create(['name' => 'reguler-user']);
        $regularUser->givePermissionTo([
            'dashboard-access',
            'cash-management-access',
            'payment-requests-access',
        ]);

        $auditor = Role::create(['name' => 'auditor']);
        $auditor->givePermissionTo([
            'dashboard-access',
            'cash-management-access',
            'reports-access',
            'reconciliation-access',
        ]);

        $adminSystem = Role::create(['name' => 'admin-system']);
        $adminSystem->givePermissionTo($financePermissions);

        // Existing user management groups
        $userPermissions = Permission::where('name', 'like', '%users%')->get();
        $userGroup = Role::create(['name' => 'users-access']);
        $userGroup->givePermissionTo($userPermissions);

        $rolePermissions = Permission::where('name', 'like', '%roles%')->get();
        $roleGroup = Role::create(['name' => 'roles-access']);
        $roleGroup->givePermissionTo($rolePermissions);

        $permissionPermissions = Permission::where('name', 'like', '%permissions%')->get();
        $permissionGroup = Role::create(['name' => 'permission-access']);
        $permissionGroup->givePermissionTo($permissionPermissions);

        Role::create(['name' => 'super-admin']);
    }
}
