<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Part extends Model{

    protected  $table ="ew_part";
//    public  $timestamps = false;
    const UPDATED_AT = 'date';
    protected $primaryKey = 'barcode';
    protected $fillable = ['name', 'photo_url', 'part_num', 'part_num_yigao', 'category', 'sub_category', 'color', 'p_price', 'w_price', 'r_price', 'quantity', 'w_quantity', 'l_zone', 'l_column', 'l_level', 'date', 'des', 'xsearch', 'organizing201809', 'last_counting_event'];

}
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

