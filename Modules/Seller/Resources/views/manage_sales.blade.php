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
                    <h1 class="m-0 text-dark">@lang('menu.Manage Sales')</h1>
                    <!--------success messege-------->
                    @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Request has been Rejected .
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <!--------success messege-------->
                    <!--------error  messege-------->
                    @if(Session::has('error '))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error !</strong> No Sale Request Found.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <!--------error  messege-------->
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('menu.Manage Sales')</li>
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
                        <h3 class="card-title col-sm-6">@lang('menu.Manage Sales')</h3>

                        <div class="header_right col-sm-6">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <input type="date" class="form-control form-control-sm" id="from_date">
                                </div>
                                <div class="form-group col-md-4">
                                    <input type="date" class="form-control form-control-sm" id="to_date">
                                </div>
                                <div class="form-group col-md-4">
                                    <button class="btn btn-info btn-sm" onclick="datesearch()">Search</button>
                                    <button class="btn btn-success btn-sm" onclick="refresh()">Refresh</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--------success messege-------->
                    @if(Session::has('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        sales discount updated successfully...!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <!--------success messege-------->
                    <!-- /.card-header -->
                    <div class="card-body" id="date_sales">
                        <table id="salesTable" class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Challan No</th>
                                    <th>Customer Name</th>
                                    <th>Sale By</th>
                                    <th>Request Date</th>
                                    <th>Delivery Date</th>
                                    {{-- <th>Price</th> --}}
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

<!-- MY MODAL -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
<!-- MY MODAL END -->

@section('js')
<script>
    function datesearch() {
        $('.loading').show();
        var from = $('#from_date').val();
        var to = $('#to_date').val();
        var url = baseUrl + "seller/sales_bydate";
        getAjaxView(url, data = {
            from,
            to
        }, 'date_sales', false, 'get');

    }

    // Refresh Button
    function refresh() {
        location.reload();
    }
</script>
<script src="{{asset('js/Modules/Sale/manage_sales.js')}}"></script>
@endsection