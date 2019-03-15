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
class ControllerEgModel extends Controller
{
    public function getAllModelNames(){
        $allEgModels = EgModel::All();
        $arrayAllModelNames = array();
        foreach ($allEgModels as $egModel) {
            $arrayAllModelNames[$egModel->eg_model_id] = $egModel->model_name;
        }
        return $arrayAllModelNames;
    }

}