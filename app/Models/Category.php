<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $table = 'category';

    public function details()
    {
        return $this->hasMany(self::class, 'category_id');
    }
}
