<?php
namespace App\Libs\ImportExport;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Rule;
use Excel;

use App\WevelopeLibs\AbstractImport;

use App\Libs\Repository\AbstractRepository;
use App\Libs\Repository\PermissionGroup;

use App\Libs\ImportExport\Sheet\HakAksesListSheet;

use App\Models\Category as Model;
use App\Models\Meta;

class HakAksesImport extends AbstractImport
{
    private $errorRowList = []; // Contain ID row that failed to insert
    private $list = [];
    private $newList = [];

    public function headingRow()
    {
        return 1;
    }

    // Validate plain data
    public function validateBasic()
    {
        $fields = [
            'permission' => $this->permission->toArray()
        ];

        $rules = [
            'permission.*.permission_group' => 'required',
            'permission.*.permission' => 'required',
        ];

        $validator = Validator::make($fields, $rules);
        AbstractRepository::validOrThrow($validator);
    }

    public function validate()
    {
        $this->validateBasic();
    }

    public function run()
    {
        $list = Excel::toArray(new HakAksesListSheet, $this->file);
        $this->permission = self::remap($list[0]); // For validate

        $this->validate();

        $list = $this->permission->unique('permission_group');

        // get all category needed
        $dataPermission = Model::withTrashed()->where('group_by', 'permission')->get();

        foreach ($list as $x){
            $x = (object) $x;

            $model = null;
            $groupBy = 'permission_group';

            // Check ID
            // parse to string first to prevent 0 from being considered empty
            if ($x->permission_group != null) {
                $model = Model::where('name', $x->permission_group)->where('group_by', $groupBy)->first();
            }

            // If model still empty then create new
            if(empty($model))
                $model = new Model;

            $model->name = $x->permission_group;
            $model->label = $x->permission_group;
            $model->group_by = $groupBy;

            $permissions = $this->permission->where('permission_group', $x->permission_group);

            $repo = new PermissionGroup($model);

            foreach ($permissions as $y) {
                $permission = $dataPermission->where('name', $y['permission'])->where('group_by', 'permission')->first();

                if(!empty($permission)){
                    $repo->addDetailPermission($permission->id);
                }
            }

            $repo->save();
        }
    }

    public function getErrorMessage()
    {
        $message = null;
        if(!empty($this->errorRowList)) {
            $message = 'Baris :list-baris: tidak disertakan karena ada data yang tidak benar. Check kembali pengisian.';
            $message = str_replace(':list-baris:', implode(', ', $this->errorRowList), $message);
        }

        return $message;
    }

    // Remap column
    private static function remap($list)
    {
        // Must be in order
        $columns = explode(',', 'permission_group,permission');
        $newList = [];
        foreach($list as $x) {
            // Mapping to columns
            $temp = [];
            $c = 0;
            foreach($x as $y) {
                $temp[$columns[$c]] = $y;
                $c++;
            }

            $newList[] = $temp;
        }

        $header = array_shift($newList);

        return collect($newList);
    }
}
