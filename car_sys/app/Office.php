<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    protected $table = 'office';
    protected $primaryKey = 'officeid';
    public $timestamps = false;
    protected $fillable = array('officeid','officesite');
}
