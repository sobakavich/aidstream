<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class RoleTableUpdateSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['id' => 5, 'role' => 'secondary', 'created_at' => '2015-09-01 11:42:29', 'updated_at' => '2015-09-01 11:43:29'],
        ];

        DB::table('role')->insert($roles);
    }

}
