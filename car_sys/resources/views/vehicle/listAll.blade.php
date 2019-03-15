@extends('layouts.main')
{{--Init:--}}
{{--$pathOfRoot - overallProjRoot--}}
@section('php_script')
    <?php
    $pathOfRoot = '../../../';
    ?>
@endsection
{{--@php--}}
    {{--use App\Http\Controllers\ControllerVehicle;--}}
{{--@endphp--}}
<style>
    .sort-btn-highlight {
        background-color: #00aeef;
    }
    .table thead .sorting-general::after {
        content: '\e150';
    }
    .a-underline-zz {
        text-decoration: underline;
    }
    .btn-inside-table {
        margin: -5px 0px;
    }
</style>

@section('head_title')
    <title>All Vehicles - AGT Warehouse Management System</title>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Vehicle List<small></small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a>
                        </li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <p>{{$data['pagedAllVehicle']->total()}} result(s) was found in this query.
                        Sort by

                    </p>


                    <p style="position: relative;float: left">{{--Page:--}}
                        {!! $data['pagedAllVehicle']->appends(['sort_field'=>$data['request']->input('sort_field'),
                                                                'sort_order'=>$data['request']->input('sort_order')])->render() !!}
                    </p>
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Vehicle Id
                                <a href="{{$data['request']->url()."?page=".$data['request']->input('page').
                                    "&sort_field=vehicle_id".
                                    "&sort_order=asc"}}"
                                   class="glyphicon glyphicon-arrow-up {{$data['logicVehicle']->highlightCurrentSortingBtn('vehicle_id','asc','sort-btn-highlight')}}"
                                   style="float: right" aria-hidden="true"></a>&nbsp;
                                <a href="{{$data['request']->url()."?page=".$data['request']->input('page').
                                    "&sort_field=vehicle_id".
                                    "&sort_order=desc"}}"
                                   class="glyphicon glyphicon-arrow-down {{$data['logicVehicle']->highlightCurrentSortingBtn('vehicle_id','desc','sort-btn-highlight')}}"
                                   style="float: right" aria-hidden="true"></a>
                            </th>
                            <th>Vin Num
                                <a href="{{$data['request']->url()."?page=".$data['request']->input('page').
                                    "&sort_field=vin_num".
                                    "&sort_order=asc"}}"
                                   class="glyphicon glyphicon-arrow-up {{$data['logicVehicle']->highlightCurrentSortingBtn('vin_num','asc','sort-btn-highlight')}}"
                                   style="float: right" aria-hidden="true"></a>&nbsp;
                                <a href="{{$data['request']->url()."?page=".$data['request']->input('page').
                                    "&sort_field=vin_num".
                                    "&sort_order=desc"}}"
                                   class="glyphicon glyphicon-arrow-down {{$data['logicVehicle']->highlightCurrentSortingBtn('vin_num','desc','sort-btn-highlight')}}"
                                   style="float: right" aria-hidden="true"></a>
                            </th>
                            <th>Original Eagle Model
                            </th>
                            <th>Manuf. Date</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($data['pagedAllVehicle'] as $vehicle)
                        <tr>
                            <td>

                                {{$vehicle->vehicle_id}}

                            </td>
                            <td>
                                <a href="view_details/{{$vehicle->vehicle_id}}" class="a-underline-zz">
                                    {{$vehicle->vin_num}}
                                </a>
                            </td>
                            <td>
                                {{$vehicle->originalEgModel->model_name}}
                            </td>
                            <td>
                                {{$vehicle->manuf_date}}
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!--x_content-->
            </div><!--x_panel-->
        </div><!--col-->
    </div><!--row-->
@endsection

