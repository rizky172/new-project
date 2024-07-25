<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

use App\Models\Category;

class ConfigSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        DB::table('config')->insert($data);
    }
}