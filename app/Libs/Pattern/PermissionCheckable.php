<?php

namespace App\Libs\Pattern;

interface PermissionCheckable
{
    public function permissionCheck(bool $state);
    public function getPermissionCheck();
}