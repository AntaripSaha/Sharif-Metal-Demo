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
                    <h1 class="m-0 text-dark">@lang('warehouse.ware_products')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('warehouse.ware_products')</li>
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
                    <div class="card-header card_buttons">
                        <h3 class="card-title">@lang('warehouse.ware_products')</h3>
                        @if(Auth::user()->can('add_wareproducts',app('\Modules\Warehouse\Entities\Warehouse')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="add_wareproduct mr-2 btn btn-success btn-sm float-right">
                            <i class="fas fa-plus"></i>
                            @lang('warehouse.new_product')
                        </button>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4">
                                <label></label>
                                <div class="form-inline card_buttons mt-2">
                                    <h3>Search Report By Product :</h3>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('warehouse.warehouse')
                                </label>
                                <select class="form-control" name="warehouse_id" id="warehouse_id">
                                    <option value="0" selected>@lang('layout.select')</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    @lang('product.bar_code')
                                </label>
                                <input type="text" id="product_id" class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                </label>
                                <div class="form-inline card_buttons">
                                    <button type="button" onclick="SearchProduct()" class="btn btn-success mt-2" id="ledgers">@lang('layout.search')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons row">
                        <div class="col-md-3">
                            <img src="{{asset('img/zamanit.png')}}" class="img-fluid mt-4" alt="Company Logo">
                        </div>
                         <div class="col-md-6 text-center">
                            <h3>{{$company_info->name}}</h3>
                            <span>{{$company_info->address}}</span><br>
                            <span>{{$company_info->phone_code}}{{$company_info->phone_no}}</span>
                        </div>
                        <div class="col-md-3 text-center">
                            <p class="mt-4">@lang('layout.date'): {{$edate}}</p>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" id="ware_view">
                        <table id="ware_productsTable" class="table table-bordered responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>@lang('product.product_name')</th>
                                    <th>@lang('warehouse.warehouse')</th>
                                    <th>@lang('warehouse.sell_q')</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('js')
<script src="{{asset('js/Modules/Warehouse/product_report.js')}}"></script>
<script>
    function SearchProduct() {
        var wid = $('#warehouse_id').val();
        var prodid = $('#product_id').val();
        var url= baseUrl+"reports/product/"+prodid+'/'+wid;
        getAjaxView(url,data='','ware_view',false,'get');
    }
</script>
@endsection
