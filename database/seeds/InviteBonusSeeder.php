<?php

use Illuminate\Database\Seeder;

class InviteBonusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invite_bonus')->insert([
            ['rule' => '1,2', 'bonus' => 980.00],
            ['rule' => '1,2,2', 'bonus' => 490.00],
            ['rule' => '2,2', 'bonus' => 490.00]
        ]);
    }
}
