<?php

use Illuminate\Database\Seeder;
use App\Permission;
use App\Role;
use App\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
          'id' => 1,
          'branch_office_id' => 4,
          'name' => 'Belatrix Mobile Lab',
          'email' => 'mobilelab@belatrixsf.com',
          'password' => bcrypt("q1!q1!")
        ]);

        User::create([
          'id' => 2,
          'branch_office_id' => 2,
          'name' => 'Maria Fernanda Gomez',
          'email' => 'fgomez@belatrixsf.com',
          'password' => bcrypt("123456")
        ]);

        Role::create([
            'id' => 1,
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Super user'
        ]);

        Role::create([
            'id' => 2,
            'name' => 'recepcionist',
            'display_name' => 'Recepcionist',
            'description' => 'Belatrix Recepcionist'
        ]);

        // Set role admin to mobilelab@belatrixsf.com
        $permission = new Permission();
        $permission->name = "general";
        $permission->save();

        $user = User::where('email', '=', 'mobilelab@belatrixsf.com')->first();
        $role = Role::where('name', '=', 'admin')->first();
        $user->roles()->attach($role->id);

        $permission = Permission::where('name', '=', 'general')->first();
        $role->attachPermission($permission);

        // Set role recepcionist to mobilelab@belatrixsf.com
        $user = User::where('email', '=', 'fgomez@belatrixsf.com')->first();
        $role = Role::where('name', '=', 'recepcionist')->first();
        $user->roles()->attach($role->id);

        $permission = Permission::where('name', '=', 'general')->first();
        $role->attachPermission($permission);
    }
}
