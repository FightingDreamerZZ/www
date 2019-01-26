<?php
/**
 * Created by PhpStorm.
 * Date: 2019-01-22
 * Time: 5:01 PM
 */
namespace APP\Models;
use Illuminate\Database\Eloquent\Model;

class AgtModel extends Model {//zz AgtModel here model = type, not model in MVC

    protected $table = 'ew_agt_vehicle_models';
    protected $primaryKey = 'agt_model_id';
    protected $fillable = ['model_name',
        'img_url'];

    public function vehicles(){
        return $this->hasMany('App\Models\Vehicle','sold_as_agt_model_id','agt_model_id');
    }
}