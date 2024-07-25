<?php
namespace App\Libs\Generator;

use Faker\Factory as Faker;

use DB;

class DatabaseRandomizeGenerator
{
    private $faker;

    public function __construct()
    {
        $this->faker = Faker::create('id_ID');
    }

    public function generate()
    {
        $this->randomizePersonTable();
        $this->randomizeConfigTable();
    }

    private function randomizePersonTable()
    {
        $tableName = 'person';
        $faker = $this->faker;

        $rows = DB::table($tableName)->get();

        foreach ($rows as $x) {
            $update = [];

            if (!empty($x->name))
                $update['name'] = $faker->name;

            if (!empty($x->company_name))
                $update['company_name'] = $faker->company;

            if (!empty($x->address))
                $update['address'] = $faker->address;

            if (!empty($x->phone))
                $update['phone'] = $faker->e164PhoneNumber;

            if (!empty($x->fax))
                $update['fax'] = $faker->e164PhoneNumber;

            if (!empty($x->ext))
                $update['ext'] = $faker->e164PhoneNumber;

            if (!empty($x->notes))
                $update['notes'] = $faker->realText;

            if (!empty($x->npwp))
                $update['npwp'] = $faker->numberBetween(1000000000, 9999999999);

            DB::table($tableName)
                ->where('id', $x->id)
                ->update($update);
        }

        // Update user tabel
        $sql = <<<EOT
UPDATE user
JOIN person p ON p.id = user.person_id
SET user.name = p.name
    -- user.email = p.email
WHERE user.person_id IS NOT NULL
EOT;
        DB::statement($sql);
    }

    private function randomizeConfigTable()
    {
        $newConfig = [
            'smtp_host' => env('MAIL_HOST'),
            'smtp_port' => env('MAIL_PORT'),
            'smtp_username' => env('MAIL_USERNAME'),
            'smtp_password' => env('MAIL_PASSWORD'),
        ];

        $tableName = 'config';
        $rows = DB::table($tableName)->get();

        $list = [];
        foreach ($newConfig as $key => $value) {
            $row = $rows->firstWhere('key', $key);
            $row->value = $value;

            $list[] = $row;
        }

        foreach ($list as $x) {
            DB::table($tableName)
                ->where('id', $x->id)
                ->update((array) $x);
        }
    }
}
