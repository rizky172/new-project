<?php
namespace App\WevelopeLibs;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Bsi\ImportExport\Sheet\QuotationSheet;
use App\Bsi\ImportExport\Sheet\QuotationDetailSheet;

use App\Quotation;

abstract class AbstractImport implements WithHeadingRow, WithMultipleSheets
{
    protected $file;

    public function __construct(UploadedFile $file)
    {
        $this->file = $file;
    }

    public function headingRow()
    {
        return 0;
    }

    public function sheets(): array
    {
        return [
            'penawaran' => new QuotationSheet(new Quotation),
            'detail' => new QuotationDetailSheet(new Quotation)
        ];
    }

    abstract public function validate();

    abstract public function run();

    public function getErrorMessage()
    {
        return null;
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
