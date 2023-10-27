<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConfigAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\ConfigAddress::updateOrCreate([
            'code'  => 'TOKEN_DAO'
        ],[
            'name'  => 'Address Token DAO',
            'address'=> '0xe84AbAE8fE74eC1df6D90970f97BbC826170c91B'
        ]);
    }
}
