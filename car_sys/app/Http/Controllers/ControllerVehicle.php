<?php
/**
 * Created by PhpStorm.
 * User: AGT John
 * Date: 2019-01-25
 * Time: 10:38 AM
 */

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleColor;
use App\Models\EgModel;
use App\Models\AgtModel;
use App\Models\Dealer;

use Illuminate\Http\Request;


class ControllerVehicle extends Controller
{
    public function listAll(Request $req){
//        $allVehicles = Vehicle::all();
        $pagedAllVehicle = Vehicle::paginate(2);
        $a = $req->input('haha');
        return view('vehicle.listAll')->with('pagedAllVehicle',$pagedAllVehicle);
    }
}