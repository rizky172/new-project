<?php

namespace App\Libs\Repository;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\File;

use App\Libs\Helpers\FullPath;
use App\Libs\Helpers\ResourceUrl;

use App\Libs\Repository\AbstractRepository;
use App\Libs\Repository\Media;

use App\Models\Config as Model;
use App\Models\Media as MediaModel;
use App\Models\Meta;

class Config extends AbstractRepository
{
    const ALLOWED_KEY = [
        // Company Information
        'company_name',
        'company_address',
        'company_phone',
        'company_email',
    ];

    private $list = [];
    private $logo = [];
    private $_delete_logo = [];


    public function __construct()
    {
        parent::__construct(new Model());
    }

    public function addLogo($logo)
    {
        $this->logo = $logo;
    }

    public function deleteLogo($logo)
    {
        $model = Meta::where('table_name', 'config')
            ->where('key', 'logo')
            ->first();

        $repo = new Media($model);
        $repo->delete();
    }

    public function update($key, $value)
    {
        $this->list[] = [
            'key' => $key,
            'value' => $value
        ];
    }

    public static function get($key)
    {
        $value = null;

        $row = Model::where('key', $key)->first();
        if (!empty($row))
            $value = $row->value;

        return $value;
    }

    public function validate()
    {
        $this->validateBasic();
    }

    private function validateBasic()
    {
        $collection = collect($this->list);

        // Validate value
        $fields = [
            'setting' => $this->list
        ];

        $rules = array(
            'setting.*.key' => 'required',
            'setting.*.value' => 'nullable'
        );

        $validator = Validator::make($fields, $rules);
        AbstractRepository::validOrThrow($validator);
    }

    private function generateData()
    {
        foreach ($this->list as $x) {
            $key = $x['key'];
            $value = $x['value'];
        }
    }

    public function save()
    {
        $this->filterByAccessControl('setting_create');
        $this->validate();
        // $this->generateData();

        if (isset($this->logo) && !empty($this->logo))
            $this->saveLogo();

        foreach ($this->list as $x) {
            $key = $x['key'];
            $value = $x['value'];

            $row = Model::where('key', $key)->first();
            if (empty($row)) {
                $row = new Model();
                $row->key = $key;
            }

            $row->value = $value;
            $row->save();
        }
    }

    public function saveLogo()
    {
        foreach ($this->logo as $x => $value) {
            $row = Model::firstOrNew(['key' => 'logo', 'value' => 'media']);
            $row->save();

            $fkId = $row->id;
            $tableName = 'config';
            $key = 'logo';

            $media = MediaModel::firstOrNew([
                'fk_id' => $fkId,
                'table_name' => $tableName,
                'key' => $key
            ]);

            $media->fk_id = $fkId;
            $media->table_name = $tableName;
            $media->key = $key;

            $repo = new Media($media);

            if (!empty($this->logo[$x])){
                if (!empty($media->name)) {
                    $fullPath = FullPath::config($media->name);
                    if (File::exists($fullPath))
                        File::delete($fullPath);
                }

                $repo->addFile($this->logo[$x]);
            }

            $repo->save();
        }
    }

    public static function toArray()
    {
        $list = [];
        $setting = Model::all();
        $exclude = ['smtp_password'];

        $setingList = [];
        foreach ($setting as $x) {
            if (!in_array($x->key, $exclude)) {
                $setingList[] = [
                    'key' => $x->key,
                    'value' => $x->value
                ];
            }
        }

        //create formatted array for setting
        $formattedSetting = array_column($setingList, 'value', 'key');

        //just fill allowed key with value
        foreach (self::ALLOWED_KEY as $x => $value) {
            $list[$value] = null;
            //find exist key
            if (array_key_exists($value, $formattedSetting))
                $list[$value] = $formattedSetting[$value];
        }

        $media = Model::whereIn('key', ['logo'])->get();

        $configLogo = $media->where('key', 'logo')->first();

        if ($configLogo) {
            $logo = MediaModel::where([
                'fk_id' => $configLogo->id,
                'key' => 'logo',
                'table_name' => 'config'
            ])->first();

            $list['logo'] = [];

            if ($logo) {
                $list['logo']['id'] = $logo->id;
                $list['logo']['url'] = ResourceUrl::config($logo->name);
                $list['logo_name'] = $logo->name;
            }
        } else {
            $list['logo'] = [
                'url' => null
            ];
            $list['logo_name'] = null;
        }

        $list['_delete_logo'] = [];

        return $list;
    }
}
