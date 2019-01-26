<?php

namespace App;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model{
    
    protected  $table ="announcemts";
    public  $timestamps = false;
    protected $primaryKey = 'annc_id';
    protected $fillable = ['title','content','date','posted_by'];
    
      
}
