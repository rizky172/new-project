<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $fillable = ['fk_id', 'key', 'table_name', 'category_id', 'name', 'notes'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
