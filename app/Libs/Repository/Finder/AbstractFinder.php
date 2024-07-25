<?php
namespace App\Libs\Repository\Finder;

use Wevelope\Wevelope\Finder\AbstractFinder as ParentObject;

use App\Libs\LibKernel;

class AbstractFinder extends ParentObject
{
    public function __construct()
    {
        // Set row per page
        $this->setPerPage(LibKernel::getPaginate());
    }

    // Throw an exception if don't have certain access
    public function filterByAccessControl($access, $message = null)
    {
        if (isset($this->accessControl)) {
            $this->accessControl->hasAccessOrThrow($access, $message);
        }
    }
}
