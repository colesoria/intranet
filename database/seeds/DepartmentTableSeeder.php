<?php

use Illuminate\Database\Seeder;
use App\Department;

class DepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $department_desarrollo = new Department;
        $department_desarrollo->name = 'Desarrollo';
        $department_desarrollo->save();


        $department_publicidad = new Department;
        $department_publicidad->name = 'Publicidad';
        $department_publicidad->save();

        $department_rrss = new Department;
        $department_rrss->name = 'Redes Sociales';
        $department_rrss->save();

        $department_transversal = new Department;
        $department_transversal->name = 'Transversal';
        $department_transversal->save();

        $department_rrhh = new Department;
        $department_rrhh->name = 'Recursos Humanos';
        $department_rrhh->save();
    }
}
