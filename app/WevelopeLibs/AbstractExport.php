<?php
namespace App\WevelopeLibs;

use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;

use Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

abstract class AbstractExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data = [];
    protected $accessControl;

    // Giving header
    abstract public function headings() : array;

    // @return array
    abstract public function getData();

    abstract public function getFilename();

    abstract public function validate();

    abstract protected function setPermission();

    public function getDisk()
    {
        return 'tmp';
    }

    public function run()
    {
        $this->setPermission();
        $this->validate();

        return Excel::store($this, $this->getFilename(), $this->getDisk(), \Maatwebsite\Excel\Excel::XLS);
    }

    public function collection()
    {
        return collect($this->getData());
    }

    public function getAccessControl()
    {
        return $this->accessControl;
    }

    public function setAccessControl($accessControl)
    {
        $this->accessControl = $accessControl;
    }

    // Throw an exception if don't have certain access
    public function filterByAccessControl($access)
    {
        if (isset($this->accessControl)) {
            if(!$this->accessControl->hasAccess($access)) {
                WeAccessControl::throwUnauthorizedException();
            }
        }
    }
}
