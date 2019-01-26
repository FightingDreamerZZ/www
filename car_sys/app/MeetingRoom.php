<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class MeetingRoom extends Model{
    
    protected  $table ="meeting_rooms";
    public  $timestamps = false;
    protected $primaryKey = 'mr_id';
    protected $fillable = ['room_number','capacity','description','phone'];
    
      
}

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

