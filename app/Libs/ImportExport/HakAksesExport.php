<?php
namespace App\Libs\ImportExport;

use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;

use Illuminate\Database\Eloquent\Model;
use Excel;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use App\WevelopeLibs\AbstractExport;

use App\Libs\ImportExport\Sheet\HakAksesListSheet;

class HakAksesExport extends AbstractExport implements WithMultipleSheets
{
    private $keyword;
    private $item;
    private $itemCategory;
    private $filename;

    public function __construct() {
        $date = new \Datetime;
        $this->filename = 'hak_akses_export_' . $date->format('Ymdhis') . '.xls';
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function validate()
    {
        return;
    }

    public function getData()
    {
        return;
    }

    public function collection()
    {
        return collect($this->getData());
    }

    public function headings() : array
    {
        return [];
    }

    public function sheets(): array {
        $filter = [];

        $list = new HakAksesListSheet($filter);
        $list->setAccessControl($this->accessControl);
        return [
            $list
        ];
    }

    public function setPermission()
    {
        // $this->filterByAccessControl('item_create');
    }

}
