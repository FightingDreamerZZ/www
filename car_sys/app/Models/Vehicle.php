<?php
/**
 * Created by PhpStorm.
 * Date: 2019-01-22
 * Time: 5:01 PM
 */
namespace APP\Models;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model {

    protected $table = 'ew_vehicles';
    protected $primaryKey = 'vehicle_id';
    protected $fillable = ['vin_num',
        'eg_order_num',
        'original_eg_model_id',
        'manuf_date',
        'original_color_id',
        'rdy_to_sell',
        'sale_status',
        'sale_dealer_id',
        'price',
        'paid_amt',
        'dlvr_date',
        'sold_as_agt_model_id',
        'invoice_date',
        'invoice_num',
        'invoice_sent',
        'update_at',
        'note'];

    public function originalEgModel(){
        return $this->belongsTo('App\Models\EgModel','original_eg_model_id','eg_model_id');
    }
    public function soldAsAgtModel(){
        return $this->belongsTo('App\Models\AgtModel','sold_as_agt_model_id','agt_model_id');
    }
    public function originalColor(){
        return $this->belongsTo('App\Models\VehicleColor','original_color_id','color_id');
    }
    public function saleDealer(){
        return $this->belongsTo('App\Models\Dealer','sale_dealer_id','dealer_id');
    }

//    private $aa = 1;
//    public function append() {
//        $attr = $this->attributes;
//        $attr[''];
//    }

//    protected $attributes = $this->attributes;

}