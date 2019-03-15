@extends('layouts.main')
{{--Init:--}}
{{--$pathOfRoot - overallProjRoot--}}
@section('php_script')
    <?php
    $pathOfRoot = '../../../';
    ?>
@endsection

<style>
    .table_view_part_page tbody th{
        /*border: 3px solid purple;*/
    }
    #operation_links_view_part {
        margin-top: 10px;
        text-align: center;
    }
    #div_view_vehicle a img {
        display: block;
        margin: auto;
    }
    .a-underline-zz {
        text-decoration: underline;
    }
</style>

@section('head_title')
    <title>Add New Vehicle - AGT Warehouse Management System</title>
@endsection

@section('content')
    <!--zz x_panel single big-->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Add New Vehicle<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!--zz add new vehicle-->
                    <div id="div_add_new_vehicle">
                        <div class="row">
                            <form action="add_new_save" class="form-horizontal form-label-left"
                                  name="form-add-new" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Vin Number</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="vin_num"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Eagle Order Number</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="eg_order_num"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Original Eagle Model</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select name="original_eg_model_id" class="form-control">
                                                <option value="" selected disabled>Please select...</option>
                                                @foreach($arrayEgModels as $egModelId => $egModelName)
                                                    <option value="{{ $egModelId }}">{{ $egModelName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Manuf. Date</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="manuf_date"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Original Color</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select name="original_color_id" class="form-control">
                                                <option value="" selected disabled>Please select...</option>
                                                @foreach($arrayColors as $colorId => $colorName)
                                                    <option value="{{ $colorId }}">{{ $colorName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Ready to Sell?</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="rdy_to_sell"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Status</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="sale_status"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sale Dealer</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select name="sale_dealer_id" class="form-control">
                                                <option value="" selected disabled>Please select...</option>
                                                @foreach($arrayDealers as $dealerId => $dealerName)
                                                    <option value="{{ $dealerId }}">{{ $dealerName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Delivery Date</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="dlvr_date"
                                                   value=""/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-xs-12">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Vehicle Price</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="price"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Paid Amount</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="paid_amt"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">AGT Model Sold As</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select name="sold_as_agt_model_id" class="form-control">
                                                <option value="" selected disabled>Please select...</option>
                                                @foreach($arrayAgtModels as $agtModelId => $agtModelName)
                                                    <option value="{{ $agtModelId }}">{{ $agtModelName }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Invoice Date</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="invoice_date"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Invoice Number</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="invoice_num"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Invoice Sent?</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="invoice_sent"
                                                   value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Note</label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <textarea class="form-control" rows="3" cols="50" name="note"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-md-9 col-sm-9 col-xs-12 col-md-offset-3">
                                            <button type="reset" class="btn btn-primary">Reset</button>
                                            <input type="submit" name="submit" class="btn btn-success" value="Add New Vehicle" />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!--zz /new part-->
                </div><!--x_content-->
            </div><!--x_panel-->
        </div>
    </div><!--zz x_panel single big-->
@endsection