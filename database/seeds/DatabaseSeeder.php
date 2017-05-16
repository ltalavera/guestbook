<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(GuestTypeTableSeeder::class);
        $this->call(BranchOfficeTableSeeder::class);
        $this->call(UserSeeder::class);
    }
}
