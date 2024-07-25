<?php
namespace App\Libs\Generator;

use Wevelope\Wevelope\Generator\FilenameGenerator as ParentObject;

class FilenameGenerator extends ParentObject
{
    public function __construct($filename, $ext = 'pdf', $prefix = null, $postfix = null)
    {
        parent::__construct($filename, $ext, $prefix, $postfix);

        // Generate postfix with hash string
        if(empty($postfix)) {
            $this->setPostfix((new \DateTime())->format('Ymdhis'));
        }
    }
}
