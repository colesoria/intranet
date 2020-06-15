<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_employee_user = new Role;
        $role_employee_user->name = 'Empleado';
        $role_employee_user->save();

        $role_manager_user = new Role;
        $role_manager_user->name = 'Director';
        $role_manager_user->save();

        $role_admin_user = new Role;
        $role_admin_user->name = 'Administrador';
        $role_admin_user->save();

        $role_superadmin_user = new Role;
        $role_superadmin_user->name = 'Super administrador';
        $role_superadmin_user->save();
    }
}
