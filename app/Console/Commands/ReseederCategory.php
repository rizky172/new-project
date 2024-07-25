<?php

namespace App\Console\Commands;

use DB;
use Storage;

use Illuminate\Console\Command;

use App\WevelopeLibs\Csv;

use App\Category;

class ReseederCategory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reseeder-category {group_by}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Drop and store category data by group_by';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            DB::beginTransaction();

            $this->reseederCategory();

            DB::commit();
        } catch(ValidationException $e) {
            $this->error('ERROR: ' . $e->getMessage());
            var_dump($e->validator->errors());

            DB::rollBack();
        }
    }

    private function reseederCategory()
    {
        printf("Reseeder database...");

        $groupBy = $this->argument('group_by');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('category')->where('group_by', $groupBy)->delete();

        $csv = Storage::disk('database')->get('csv/category.csv');
        $lines = Csv::toArray($csv);

        // ignore first line
        $rows = [];
        array_shift($lines);
        foreach ($lines as $x) {
            if (!empty($x) && $x[5] == $groupBy) {
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
                    'created_at' => new \DateTime,
                    'updated_at' => new \DateTime,
                    'deleted_at' => $isDeleted
                ];
            }
        }

        DB::table('category')->insert($rows);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        printf("\nReseeder complete!\n");
    }
}
