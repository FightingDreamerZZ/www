<?php
/**
 * Created by PhpStorm.
 * Date: 2019-01-22
 * Time: 5:01 PM
 */
namespace APP\Models;
use Illuminate\Database\Eloquent\Model;

class EgModel extends Model {//zz EgModel here model = type, not model in MVC

    protected $table = 'ew_eg_vehicle_models';
    protected $primaryKey = 'eg_model_id';
    protected $fillable = ['model_name',
        'img_url'];

    public function vehicles(){
        return $this->hasMany('App\Models\Vehicle','original_eg_model_id','eg_model_id');
    }
}