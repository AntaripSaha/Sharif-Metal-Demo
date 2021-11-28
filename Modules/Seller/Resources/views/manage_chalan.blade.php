@extends('layouts.app')
@section('css')
@endsection
@section('content')
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('menu.Chalan Register')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('menu.Chalan Register')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons row">
                        <h3 class="card-title col-sm-6">@lang('menu.Chalan Register')</h3>
                        <div class="header_right col-sm-6">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <input type="date" class="form-control" id="from_date">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="date" class="form-control" id="to_date">
                                </div>
                                <div class="form-group col-md-2">
                                    <button class="btn btn-info btn-sm form-control" onclick="datesearch()">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" id="date_sales">
                        <table id="salesTable" class="table table-bordered table-striped display responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>Challan No</th>
                                    <th>Customer Name</th>
                                    <th>Sale By</th>
                                    <th>Request Date</th>
                                    <th>Delivery Date</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('js')
<script>
    function datesearch(){
        var from = $('#from_date').val();
        var to = $('#to_date').val();
        var url= baseUrl+"seller/sales_bydate";
        getAjaxView(url,data={from,to},'date_sales',false,'get');
    }
</script>
<script src="{{asset('js/Modules/Sale/manage_chalan.js')}}"></script>
@endsection
