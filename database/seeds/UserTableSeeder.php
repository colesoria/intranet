<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Role;
use App\Department;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'Oscar Fernández';
        $user->email = 'oscar@clicknaranja.com';
        $user->password = bcrypt('cN1256cn');
        $user->save();
        $user->roles()->attach(Role::where('name', 'Empleado')->first());
        $user->departments()->attach(Department::where('name', 'Desarrollo')->first());

        $manager = new User;
        $manager->name = 'Andrés Ibarra';
        $manager->email = 'andres@clicknaranja.com';
        $manager->password = bcrypt('cN1256cn');
        $manager->save();
        $manager->roles()->attach(Role::where('name', 'Director')->first());
        $manager->roles()->attach(Role::where('name', 'Super administrador')->first());
        $manager->departments()->attach(Department::where('name', 'Desarrollo')->first());

        $admin = new User;
        $admin->name = 'Jorge';
        $admin->email = 'jorge@clicknaranja.com';
        $admin->password = bcrypt('cN1256cn');
        $admin->save();
        $admin->roles()->attach(Role::where('name', 'Administrador')->first());
        $admin->departments()->attach(Department::where('name', 'Recursos Humanos')->first());
    }
}
