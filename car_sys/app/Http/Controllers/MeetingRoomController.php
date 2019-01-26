<?php
namespace App\Http\Controllers;

use App\MeetingRoom;
use App\MeetingRoomBooking;
use App\Models\Part;
use Illuminate\Http\Request;

class MeetingRoomController extends Controller {
    
    public function  mngMRsIndex(){
        $meetingRooms = MeetingRoom::all();
         return view('bookingMeetingRoom.manageMeetingRooms')->with('meetingRooms',$meetingRooms);
    }

    public function  mngPartsIndex(){
        $allParts = Part::all();
        return view('bookingMeetingRoom.manageParts')->with('allParts',$allParts);
    }
    
    public  function mngMRsInsert(Request $request){               
        $this->validate($request,
                [
                    'room_number' => 'required',
                    'capacity' => 'required|Integer',
                    'description' => 'required',
                    'phone' => 'required|max:10'                
                ]
                );
        $inputs = $request->all();
        MeetingRoom::create($inputs);
        

    }
    
    public function mngMRsDelete($mr_id){
        $meetingRoom = MeetingRoom::find($mr_id);
        $meetingRoom->delete();
        
        return Redirect('manageMeetingRooms');
    }
    
    public function  dateTimePickerNext($nextFirst){
        return view('bookingMeetingRoom.dateTimePicker')->with('nextFirst',$nextFirst);
    }
    public function  dateTimePickerPrevious($previousFirst){
        return view('bookingMeetingRoom.dateTimePicker')->with('previousFirst',$previousFirst);
    }
    public static function dateTimePickerCheckBooked($mr_id,$date,$time_session){
        $conditions=['mr_id'=>$mr_id,
            'date'=>$date,
            'time_session'=>$time_session];
        $result=MeetingRoomBooking::where($conditions)->first();
        return isset($result);
    }
    
    public function roomPickerIndex(){
        $meetingRooms = MeetingRoom::all();
         return view('bookingMeetingRoom.roomPicker')->with('meetingRooms',$meetingRooms);
    }
    
    public function dateTimePickerIndex($mr_id){
        $meetingRoomBookings=MeetingRoomBooking::all();
        return view('bookingMeetingRoom.dateTimePicker')->with('mr_id',$mr_id)
            ->with('meetingRoomBookings',$meetingRoomBookings);
    }
    public static function hasExpired($date){
        $today=  date_create();
        $date=date_create_from_format("Y/m/d", $date);
//        $today=new \DateTime();
//        $date=new \DateTime($date);
        
        return $today>$date;
    }
    public static function SessVariIni($name,$value){
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }
        if(!isset($_SESSION[$name])){
            $_SESSION[$name]=$value;
        }
    
    }
    public static function SessVariGet($name){
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }
        
            return $_SESSION[$name]; 
    }
    public static function InitOAlign($name,$default){
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }
        if(!isset($_SESSION[$name])){
            return $default;
              
        }
        else{
            return $_SESSION[$name];
        }       
    }
//    public function dateTimePickerSave($bookingBufferItem){
//        session_start();
//        if(!isset($_SESSION['bookingBuffer'])){
//            $bookingBuffer=[
//                    ['mr_id'=>$bookingBufferItem[0],
//                        'date'=>$bookingBufferItem[1],
//                        'time_session'=>$bookingBufferItem[2],
//                        'empid'=>$bookingBufferItem[3]]
//                ];           
//        }
//        else $_SESSION['bookingBuffer'][]=['mr_id'=>$bookingBufferItem[0],
//                        'date'=>$bookingBufferItem[1],
//                        'time_session'=>$bookingBufferItem[2],
//                        'empid'=>$bookingBufferItem[3]];
//    }
    
    public function dateTimePickerSave($bookingBufferItem){
        session_start();
        $session=substr($bookingBufferItem, 0, 1);
        $currentFirst=substr($bookingBufferItem, 1, 10);
        $date=substr($bookingBufferItem, 11, 10);
        if(!isset($_SESSION['bookingBuffer'])){
            $_SESSION['bookingBuffer']=array(
                    array('mr_id'=>$_SESSION["mr_id"],
                        'date'=>$date,
                        'time_session'=>$session,
                        'empid'=>$_SESSION["empid"],
                        'purpose_cap'=>"undefined",
                        'purpose_desc'=>"undefined")
                );           
        }
        else{
            array_push($_SESSION['bookingBuffer'], array('mr_id'=>$_SESSION["mr_id"],
                        'date'=>$date,
                        'time_session'=>$session,
                        'empid'=>$_SESSION["empid"],
                        'purpose_cap'=>"undefined",
                        'purpose_desc'=>"undefined"));
        }          
//            $_SESSION['bookingBuffer'][]=array('mr_id'=>$_SESSION["mr_id"],
//                        'date'=>$date,
//                        'time_session'=>$session,
//                        'empid'=>$_SESSION["empid"]);
         
        return view('bookingMeetingRoom.dateTimePicker')->with('nextFirst',$currentFirst);
    }
    public function bookingDetailsIndex(){
        session_start();
        $bookingArrCount=count($_SESSION['bookingBuffer']);        
       
        return view('bookingMeetingRoom.bookingDetails')->with('bookingArrCount',$bookingArrCount);
    }
    public function bookingDPrev(){
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }
        $_SESSION['bookingCP']=(int)$_SESSION['bookingCP']-1;
        if((int)$_SESSION['bookingCP']=1)
        {$_SESSION['bookingPD']='disabled';}
        $_SESSION['bookingND']=' ';
        return view('bookingMeetingRoom.bookingDetails');
    }
    public function bookingDNext(){
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }
        $_SESSION['bookingCP']=(int)$_SESSION['bookingCP']+1;
        if((int)$_SESSION['bookingCP']=(int)$_SESSION['bookingTP'])
        {$_SESSION['bookingND']='disabled';}
        $_SESSION['bookingPD']=' ';
        return view('bookingMeetingRoom.bookingDetails');
    }
}

