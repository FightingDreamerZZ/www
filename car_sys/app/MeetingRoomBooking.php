<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class MeetingRoomBooking extends Model{
    
    protected  $table ="meeting_room_booking";
    public  $timestamps = false;
    protected $primaryKey = 'mr_booking_id';
    protected $fillable = ['mr_id','date','time_session','empid','purpose_cap','purpose_desc'];
    
      
}
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

