<?php
namespace App\Libs\Repository;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Contracts\Support\Arrayable;

use App\Libs\Helpers\FullPath;
use App\Libs\Repository\AbstractRepository;

use App\Models\Media as Model;

class Media extends AbstractRepository implements Arrayable
{
    private $file;

    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function addFile($file)
    {
        $this->file = $file;
    }

    public function delete($permanent = null)
    {
        $path = null;

        switch ($this->model->table_name) {
            case 'config':
                $path = FullPath::config($this->model->name);
                break;
        }

        File::delete($path);

        $this->model->delete();
    }

    public function deleteLastFile()
    {
        $path = null;

        switch ($this->model->table_name) {
            case 'config':
                $path = FullPath::config($this->model->name);
                break;
        }

        File::delete($path);
    }

    public function validate()
    {
        // Validation
        $fields = [
            'fk_id' => $this->model->fk_id,
            'name' => $this->model->name,
            'notes' => $this->model->notes,
            'table_name' => $this->model->table_name
        ];

        $rules = [
            'fk_id' => 'required',
            'name' => 'nullable',
            'notes' => 'nullable',
            'table_name' => 'required',
        ];

        $validator = Validator::make($fields, $rules);
        self::validOrThrow($validator);
    }

    private function generateFileName()
    {
        // To lower case
        $originalName = strtolower($this->file->getClientOriginalName());
        $originalExt = $this->file->getClientOriginalExtension();
        // Remove extension
        $originalName = str_replace(sprintf(".%s", $originalExt), '', $originalName);
        // No white space
        $originalName = str_replace(' ', '-', $originalName);
        // Remove symbol
        $originalName = preg_replace('/[^A-Za-z0-9\-]/', '_', $originalName);
        $originalName = sprintf("%s.%s", $originalName, $originalExt);

        $randomInt = rand(1, 9999) . time();

        $newFilename = ':id:_:timestamp:_:filename:';

        $newFilename = str_replace(':id:', $this->model->fk_id, $newFilename);
        $newFilename = str_replace(':filename:', $originalName, $newFilename);
        $newFilename = str_replace(':timestamp:', $randomInt, $newFilename);

        $this->model->name = $newFilename;
    }

    private function saveFile()
    {
        $path = null;

        // Move uploaded file
        switch ($this->model->table_name) {
            case 'config':
                $path = FullPath::config();
                $this->file->move($path, $this->model->name);
                break;
        }
    }

    public function save()
    {
        if (!empty($this->file))
            $this->generateFileName();

        $this->validate();

        if (!empty($this->file))
            $this->saveFile();

        $this->model->save();
    }

    public function toArray()
    {

    }
}
