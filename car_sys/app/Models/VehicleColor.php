<?php
/**
 * Created by PhpStorm.
 * Date: 2019-01-22
 * Time: 5:01 PM
 */
namespace APP\Models;
use Illuminate\Database\Eloquent\Model;

class VehicleColor extends Model {

    protected $table = 'ew_vehicle_colors';
    protected $primaryKey = 'color_id';
    protected $fillable = ['color_name',
        'color_value'];

    public function vehicles(){
        return $this->hasMany('App\Models\Vehicle','original_color_id','color_id');
    }
}