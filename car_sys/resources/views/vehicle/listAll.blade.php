@extends('layouts.main')

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
                    <p>{{$pagedAllVehicle->total()}} result(s) was found in this query.
                        Sort by

                    </p>


                    <p style="position: relative;float: left">{{--Page:--}}
                        {!! $pagedAllVehicle->render() !!}
                    </p>
                    <table id="datatable" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Vehicle Id
                            </th>
                            <th>Vin Num
                            </th>
                            <th>Original Eagle Model
                            </th>
                            <th>Manuf. Date</th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($pagedAllVehicle as $vehicle)
                        <tr>
                            <td>
                                {{$vehicle->vehicle_id}}
                            </td>
                            <td>
                                {{$vehicle->vin_num}}
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

                    <style>
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

                </div><!--x_content-->
            </div><!--x_panel-->
        </div><!--col-->
    </div><!--row-->
@endsection

