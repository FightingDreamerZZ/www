<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';
    protected $primaryKey = 'depid';
    public $timestamps = false;
    protected $fillable = array('depid','depname');
}
