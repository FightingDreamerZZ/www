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
use App\Models\BusinessLogics\LogicVehicle;

use Illuminate\Http\Request;


class ControllerVehicle extends Controller
{
    public function listAll(Request $req){
//        $allVehicles = Vehicle::all();

        $sortField = $req->input('sort_field');
        $sortOrder = $req->input('sort_order');
        if(isset($sortField))
            $pagedAllVehicle = Vehicle::orderBy($sortField,$sortOrder)->paginate(2);
        else
            $pagedAllVehicle = Vehicle::paginate(2);

        $logicVehicle = new LogicVehicle($req);

        return view('vehicle.listAll')->with('data',[
            'pagedAllVehicle'=>$pagedAllVehicle,
            'logicVehicle'=>$logicVehicle,
            'request'=>$req]);
    }
    public function addNewRenderForm() {
        $allVehicles = Vehicle::all();
        $arrayEgModels = array();
        $arrayColors = array();
        $arrayDealers = array();
        $arrayAgtModels = array();
        foreach ($allVehicles as $vehicle) {
            $arrayEgModels[$vehicle->original_eg_model_id] = $vehicle->originalEgModel->model_name;
            $arrayColors[$vehicle->original_color_id] = $vehicle->originalColor->color_name;
            $arrayDealers[$vehicle->sale_dealer_id] = $vehicle->saleDealer->dealer_name;
            $arrayAgtModels[$vehicle->sold_as_agt_model_id] = $vehicle->soldAsAgtModel->model_name;
        }
        $a=1;
        return view('vehicle.addNew',[
            "arrayEgModels"=>$arrayEgModels,
            "arrayColors"=>$arrayColors,
            "arrayDealers"=>$arrayDealers,
            "arrayAgtModels"=>$arrayAgtModels
            ]);
    }

//    `vehicle_id` int(5) NOT NULL,
//    `vin_num` varchar(25) NOT NULL,
//    `eg_order_num` varchar(20) NOT NULL COMMENT 'Order number of the order from Eagle',
//    `original_eg_model_id` int(5) DEFAULT NULL COMMENT 'FK link to vehicel models (Eagle) table',
//    `manuf_date` date NOT NULL,
//    `original_color_id` int(5) DEFAULT NULL,
//    `rdy_to_sell` varchar(10) NOT NULL,
//    `sale_status` varchar(20) NOT NULL,
//    `sale_dealer_id` int(5) DEFAULT NULL,
//    `price` decimal(10,2) NOT NULL,
//    `paid_amt` decimal(10,2) NOT NULL,
//    `dlvr_date` date NOT NULL,
//    `sold_as_agt_model_id` int(5) DEFAULT NULL,
//    `invoice_date` date NOT NULL,
//    `invoice_num` varchar(15) NOT NULL,
//    `invoice_sent` varchar(5) NOT NULL,
//    `update_at` date DEFAULT NULL,
//    `note` varchar(200) NOT NULL COMMENT '200 chars limit.'
    public function addNewSaveToDB(Request $request) {
        $this->validate($request, [
            'vin_num' => 'required|max:25',
            'eg_order_num' => 'max:20',
            'manuf_date' => 'date',
            'rdy_to_sell' => 'max:10',
            'sale_status' => 'max:20',
            'price'=>'numeric',
            'paid_amt'=>'numeric',
            'dlvr_date' => 'date',
            'invoice_date' => 'date',
            'invoice_num' => 'max:15',
            'invoice_sent' => 'max:5',
            'note' => 'max:200'
        ]);
        $newVehicle = Vehicle::create($request->all());

        return redirect('/vehicle/view_details/'.($newVehicle->vehicle_id));
    }
    public function viewDetail($id){
        $vehicle = Vehicle::find($id);
        return view('vehicle.viewDetail',[
            'vehicle'=>$vehicle
        ]);
    }
    public function edit(Request $request) {

    }
    public function delete($id){
        Vehicle::find($id)->delete();
        return redirect("/Vehicle/list");
    }
    public function globalSearch($keyword){

    }
}