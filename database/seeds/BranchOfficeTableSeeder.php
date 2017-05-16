<?php

use Illuminate\Database\Seeder;

use App\BranchOffice;

class BranchOfficeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      BranchOffice::create([
          'id' => 1,
          'name' => 'Mendoza Centro',
          'abbreviation'
      ]);
      BranchOffice::create([
          'id' => 2,
          'name' => 'Mendoza Chacras'
      ]);
      BranchOffice::create([
          'id' => 3,
          'name' => 'Buenos Aires Centro'
      ]);
      BranchOffice::create([
          'id' => 4,
          'name' => 'Lima Centro'
      ]);
    }
}
