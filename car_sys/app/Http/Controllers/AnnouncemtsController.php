<?php
namespace App\Http\Controllers;

use App\MeetingRoom;
use App\MeetingRoomBooking;
use Illuminate\Http\Request;

class AnnouncemtsController extends Controller {
    public function  anncIndex(){
        $anncs = \App\Announcement::all();
         return view('announcement.announcemts')->with('anncs',$anncs);
    }
    public function  mngAnncIndex(){
        $anncs = \App\Announcement::all();
         return view('announcement.manageAnnc')->with('anncs',$anncs);
    }
    public  function mngAnncInsert(Request $request){               
        $this->validate($request,
                [
                    'title' => 'required|max:30',
                    'content' => 'required|max:200'
                         
                ]
                );
        //=== +date, posted_by (hidden form fields)
        $inputs = $request->all();
        \App\Announcement::create($inputs);
        return Redirect('manageAnnc');
    }
    
    public function mngAnncDelete($annc_id){
        $annc = \App\Announcement::find($annc_id);
        $annc->delete();
        
        return Redirect('manageAnnc');
    }
    public function mngAnncEdit($annc_id){
        $annc = \App\Announcement::find($annc_id);
        $annc->delete();
        
        return Redirect('manageAnnc');
    }
}
