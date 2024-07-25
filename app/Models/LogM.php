<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


// Conflict name with Log:: laravel
class LogM extends Model
{
    protected $table = 'log';
    protected $fillable = ['fk_id', 'table_name', 'notes', 'created'];

    public $timestamps = false;
}
