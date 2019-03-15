<?php
/**
 * Created by PhpStorm.
 * User: AGT John
 * Date: 2019-01-25
 * Time: 12:00 PM
 */

namespace App\Models\BusinessLogics;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class LogicVehicle
{
    private $request;
    public function __construct(Request $request=null)
    {
        $this->request = $request;
    }

    public function all(){
        $allVehicles = Vehicle::all();
        foreach ($allVehicles as $vehicle){

        }
    }

    public function highlightCurrentSortingBtn($thCaption, $btnOrder, $cssClassName){
        $sortField = $this->request->input('sort_field');
        $sortOrder = $this->request->input('sort_order');
        if($thCaption == $sortField){
            if($btnOrder == $sortOrder){
                return $cssClassName;
            }
            else return "";
        }
        else return "";
    }
}