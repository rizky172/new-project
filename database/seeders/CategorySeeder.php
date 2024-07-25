<?php

namespace Database\Seeders;

use DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Seeder;

use App\Libs\Repository\Category;
use App\WevelopeLibs\Csv;

use App\Models\Meta;
use App\Models\Category as CategoryModel;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $csv = Storage::disk('database')->get('csv/category.csv');
        $lines = Csv::toArray($csv);

        // ignore first line
        array_shift($lines);
        foreach ($lines as $x) {
            if (!empty($x) && $x[5] != 'USED') {
                $name = empty($x[2]) ? Category::generateName($x[3]) : $x[2];
                if($x[5] == 'department') {
                    $name = sprintf("%02s", (int) $x[2]);
                }

                $isDeleted = ($x[6] == 1) ? new \DateTime : null;

                $rows[] = [
                    'id' => (int)$x[0],
                    'category_id' => empty($x[1]) ? null : $x[1],
                    'name' => $name,
                    'label' => $x[3],
                    'notes' => $x[4],
                    'group_by' => $x[5],
                    'disabled' => (int)$x[7],
                    // Help merging
                    'created_at' => '2020-08-04 00:00:00',
                    'updated_at' => '2020-08-04 00:00:00',
                    'deleted_at' => $isDeleted
                ];
            }
        }

        DB::table('category')->insert($rows);

        $permissions = CategoryModel::where('group_by', 'permission')->get();
        $fullAccess = CategoryModel::where('group_by', 'permission_group')
            ->where('name', 'full_access')->first();

        foreach($permissions as $x) {
            Meta::create(
                [
                    'fk_id' => $fullAccess->id,
                    'table_name' => 'category', 
                    'value' => $x->id, 
                    'key' => 'permission_id'
                ]
            );
        }
    }
}