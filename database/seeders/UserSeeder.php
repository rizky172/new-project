<?php

namespace Database\Seeders;

use DB;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

use App\Models\Category;
use App\Models\Meta;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Default user for testing
        $rows = [];

        $rows[] = [
            'id' => 1,
            'ref_no' => 'ADMIN_TEST',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'name' => 'admin',
            'email' => 'admin@unitypump',
            'created_at' => new \DateTime,
            'updated_at' => new \DateTime
        ];

       DB::table('user')->insert($rows);

       //set full access to admin
       $permissionGroup = Category::where('group_by', 'permission_group')
       ->where('name', 'full_access')
       ->first();

        Meta::create([
            'fk_id' => 1,
            'table_name' => 'user',
            'value' => $permissionGroup->id,
            'key' => 'permission_group_id'
        ]);
    }
}
