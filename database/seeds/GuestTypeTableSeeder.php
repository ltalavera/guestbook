<?php

use Illuminate\Database\Seeder;

use App\GuestType;

class GuestTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GuestType::create([
            'id' => 1,
            'name' => 'Provider'
        ]);
        GuestType::create([
            'id' => 2,
            'name' => 'Interview'
        ]);
        GuestType::create([
            'id' => 3,
            'name' => 'Visitor'
        ]);
        GuestType::create([
            'id' => 4,
            'name' => 'Employee'
        ]);
    }
}
