<?php
namespace App\Libs\Repository;

use Illuminate\Validation\Validator;
use Illuminate\Validation\ValidationException;

use DB;

use Wevelope\Wevelope\Repository\AbstractRepository as ParentObject;
use App\Libs\Generator\FilenameGenerator;
use App\Libs\Pattern\PermissionCheckable;

use App\Models\LogM;

class AbstractRepository extends ParentObject implements PermissionCheckable
{
    protected $enablePermissionCheck = true;

    // Throw an exception if don't have certain access
    public function filterByAccessControl($access, $message = null)
    {
        if (isset($this->accessControl)) {
            $this->accessControl->hasAccessOrThrow($access, $message);
        }
    }

    // Generate name format for PDF
    public function generateFilenameDownload($prefix = 'abstract-repository_')
    {
        $generator = new FilenameGenerator($this->model->ref_no, 'pdf', $prefix);

        return $generator->generate();
    }

    /*
     * Default generator ref no PREFIX+XXXX
     *
     * @return String
     */
    public function generateRefNo($digitLength = 4, $prefix = null, $postfix = null)
    {
        // $digitLength = 4;
        $refNo = null;
        // pattern for SQL where LIKE
        $pattern = sprintf('%s' . str_repeat('_', $digitLength) . '%s', $prefix, $postfix);

        $index = 1;
        $row = DB::table($this->model->getTable())
                ->orderBy('ref_no', 'desc')
                ->where('ref_no', 'like', $pattern)
                ->first();

        // Loop until get one unique ref no
        $refNo = null;
        while(!empty($row)) {
            // Increase XXXXX(index) by +1
            $formatted = str_replace($prefix, null, str_replace($postfix, null, $row->ref_no));
            $index = (int) $formatted;
            $index++;

            $refNo = sprintf("%s%s%s", $prefix, sprintf('%0' . $digitLength . 'd', $index), $postfix);

            // Verify that ref no is unique
            $row = DB::table($this->model->getTable())->where('ref_no', $refNo)->first();
        };

        // When ref no is empty then it means this date doesn't have any
        // ref no with YYMM-XXXXX format
        if(empty($refNo))
            $refNo = sprintf("%s%s%s", $prefix, sprintf('%0' . $digitLength . 'd', $index), $postfix);

        return $refNo;
    }

    protected function afterDelete($permanent = null)
    {
        if (!empty($permanent)) {
            LogM::where('fk_id', $this->model->id)
                ->where('table_name', $this->model->getTable())
                ->delete();
        }
    }

    public function permissionCheck(bool $state)
    {
        $this->enablePermissionCheck = $state;
    }

    public function getPermissionCheck()
    {
        return $this->enablePermissionCheck;
    }
}