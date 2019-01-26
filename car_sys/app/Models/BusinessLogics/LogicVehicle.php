<?php
/**
 * Created by PhpStorm.
 * User: AGT John
 * Date: 2019-01-25
 * Time: 12:00 PM
 */

namespace App\Models\BusinessLogics;

use App\Models\Vehicle;

class LogicVehicle
{
    public function all(){
        $allVehicles = Vehicle::all();
        foreach ($allVehicles as $vehicle){

        }
    }
}