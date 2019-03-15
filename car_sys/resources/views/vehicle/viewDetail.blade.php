@extends('layouts.main')
{{--Init:--}}
{{--$pathOfRoot - overallProjRoot--}}
@section('php_script')
    <?php
        $pathOfRoot = '../../../../';
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
    <title>Vehicle Details - AGT Warehouse Management System</title>
@endsection

@section('content')
    <!--zz x_panel single big-->
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>View Vehicle Details<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!--zz view part-->
                    <div id="div_view_vehicle" <?php /*echo $display_view_section;*/?>>
                        <div class="row">
                            <div class="col-md-4 col-xs-12"><!--left column-->
                                <h4>Photo Preview</h4>
                                <a href="" target="_blank">
                                    <img style="width:auto;height:auto;object-fit: cover;overflow: hidden" class ="withborder" src="{{ $pathOfRoot }}upload/eg_vehicle_models/1.jpg" />
                                    <!--    width="300" height="300" class="image_wrapper" -->
                                </a>
                                <div id="operation_links_view_part">
                                    <a class="btn btn-primary" href="edit_part.php?barcode= "
                                       data-toggle="tooltip" data-original-title='Edit this part.' data-placement="bottom">
                                        Edit <i class="fa fa-pencil"></i>
                                    </a>
                                    <a class="btn btn-primary" href="edit_part.php?barcode= "
                                       data-toggle="tooltip" data-original-title='Quick enter to inventory.' data-placement="bottom">
                                        Receiving <i class="fa fa-download"></i>
                                    </a>
                                    <a class="btn btn-primary" href="edit_part.php?barcode= "
                                       data-toggle="tooltip" data-original-title='Quick depart from inventory' data-placement="bottom">
                                        Shipping <i class="fa fa-upload"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4 col-xs-12">
                                <table class="table table_view_part_page">
                                    <tbody>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right" style="border-top: 0px">
                                            Vehicle Id
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left" style="border-top: 0px">
                                            {{$vehicle->vehicle_id}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Vin Number
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            {{$vehicle->vin_num}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Eagle Order Number
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            {{$vehicle->eg_order_num}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-5 col-sm-3 col-xs-12 text-right">
                                            Original Eagle Model
                                        </th>
                                        <td class="col-md-7 col-sm-9 col-xs-12 text-left">
                                            @if(isset($vehicle->originalEgModel))
                                                {{$vehicle->originalEgModel->model_name}}
                                            @else

                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Manuf. Date
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            {{$vehicle->manuf_date}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Original Color
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            @if(isset($vehicle->originalColor))
                                                {{$vehicle->originalColor->color_name}}
                                            @else

                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Ready to Sell
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            {{$vehicle->rdy_to_sell}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            sale_status
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            {{$vehicle->sale_status}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            sale_dealer_id
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            @if(isset($vehicle->saleDealer))
                                                {{$vehicle->saleDealer->dealer_name}}
                                            @else

                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            sold_as_agt_model_id
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            @if(isset($vehicle->soldAsAgtModel))
                                                {{$vehicle->soldAsAgtModel->model_name}}
                                            @else

                                            @endif
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!--middle column-->
                            <div class="col-md-4 col-xs-12">
                                <table class="table">
                                    <tbody>
                                    <tr>
                                        <th class="col-md-5 col-sm-3 col-xs-12 text-right" style="border-top: 0px">
                                            Retail Price
                                        </th>
                                        <td class="col-md-7 col-sm-9 col-xs-12 text-left" style="border-top: 0px">

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-5 col-sm-3 col-xs-12 text-right">
                                            Quantity
                                        </th>
                                        <td class="col-md-7 col-sm-9 col-xs-12 text-left">

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Stock Warning
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Location
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">
                                            <a href="search.php?table=ew_part&keyword=<?php /*echo($a_check['l_zone']."_".$a_check['l_column']."_".$a_check['l_level']);*/ ?>">aa</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Latest Update
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Flag Organizing1809
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Last Counting Event
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="col-md-3 col-sm-3 col-xs-12 text-right">
                                            Description
                                        </th>
                                        <td class="col-md-9 col-sm-9 col-xs-12 text-left">

                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div><!--right column-->
                        </div>

                    </div>
                    <!--zz /view part-->

                </div><!--x_content-->
            </div><!--x_panel-->
        </div><!--col-->
    </div><!--row-->
@endsection

