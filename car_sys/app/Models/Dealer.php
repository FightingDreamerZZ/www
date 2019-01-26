<?php
/**
 * Created by PhpStorm.
 * Date: 2019-01-22
 * Time: 5:01 PM
 */
namespace APP\Models;
use Illuminate\Database\Eloquent\Model;

class Dealer extends Model {

    protected $table = 'ew_dealers';
    protected $primaryKey = 'dealer_id';
    protected $fillable = ['dealer_name','dealer_phone','dealer_email','dealer_address'];

    public function vehicles(){
        return $this->hasMany('App\Models\Vehicle','sale_dealer_id','dealer_id');
    }
}