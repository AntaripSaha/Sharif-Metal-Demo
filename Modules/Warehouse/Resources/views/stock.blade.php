@extends('layouts.app')
@section('css')
@endsection
@section('content')

<style>
    .m-top{
        margin-top:2.0rem !important;
    }
</style>
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('warehouse.stock')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('warehouse.stock')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="container-fluid">
        <div class="row">
            
            <div class="col-12">
                @if( session()->has('success') )
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                  {{ session()->get('success') }}
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                @endif
            </div>
            
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <h3 class="card-title">@lang('warehouse.stock')</h3>
                        @if(Auth::user()->can('add_wareproducts',app('\Modules\Warehouse\Entities\Warehouse')) ||
                        Auth::user()->isOfficeAdmin())
                        <!--<button type="button" class="add_wareproduct mr-2 btn btn-success btn-sm float-right">-->
                        <!--    <i class="fas fa-plus"></i>-->
                        <!--    @lang('warehouse.new_product')-->
                        <!--</button>-->
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4">
                                <label>
                                    @lang('warehouse.warehouse')
                                </label>
                                <select class="form-control form-control-sm" name="warehouse_id" id="warehouse_id">
                                    <option value="0">@lang('layout.select')</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-1">
                                <label>
                                </label>
                                <div class="form-inline card_buttons">
                                    <button type="button" onclick="SearchWare()" class="btn btn-sm btn-success mt-2" id="warehouse">@lang('layout.search')</button>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    @lang('product.bar_code')
                                </label>
                                <input type="text" id="product_id" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-1">
                                <label>
                                </label>
                                <div class="form-inline card_buttons">
                                    <button type="button" onclick="SearchProduct()" class="btn btn-sm btn-success mt-2" id="ledgers">@lang('layout.search')</button>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <button class="btn btn-info btn-sm m-top" onclick="refresh()">Refresh </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <!--<div class="card-header card_buttons row">-->
                    <!--    <div class="col-md-3">-->
                    <!--        <img src="{{asset('img/zamanit.png')}}" class="img-fluid mt-4" alt="Company Logo">-->
                    <!--    </div>-->
                    <!--     <div class="col-md-6 text-center">-->
                    <!--        <h3>{{$company_info->name}}</h3>-->
                    <!--        <span>{{$company_info->address}}</span><br>-->
                    <!--        <span>{{$company_info->phone_code}}{{$company_info->phone_no}}</span>-->
                    <!--    </div>-->
                    <!--    <div class="col-md-3 text-center">-->
                    <!--        <p class="mt-4">@lang('layout.date'): {{$edate}}</p>-->
                    <!--    </div>-->

                    <!--</div>-->
                    <!-- /.card-header -->
                    <div class="card-body" id="ware_view">
                        <table id="ware_productsTable" class="table table-sm table-bordered responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('product.product_name')</th>
                                    <th class="text-center">@lang('warehouse.warehouse')</th>
                                    <th class="text-center">@lang('warehouse.stq_q')</th>
                                    <th class="text-center">@lang('warehouse.sell_q')</th>
                                    <th class="text-center">@lang('warehouse.av_q')</th>
                                </tr>
                            </thead>
                            <tbody >
                                @foreach($products as $product)
                                    <tr>
                                        <td>{{$product->products[0]->product_id}} {{$product->products[0]->product_name}} - {{ $product->products[0]->head_code }}</td>
                                        <td class="text-center">{{$product->warehouse[0]->name}}</td>
                                        <td class="text-center">{{$product->stck_q}}</td>
                                        <td class="text-center">{{$product->sell_q}}</td>
                                        <td class="text-center">{{$product->stck_q - $product->sell_q}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                       <span class="float-right">{{ $products->links() }}</span> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('js')
<script type="text/javascript">
    function SearchWare() {
        $('.loading').show();
        var warid = $('#warehouse_id').val();
        var url= baseUrl+"warehouse/stock/"+warid;
        location.href= url;
    }
    function SearchProduct() {
        $('.loading').show();
        var prodid = $('#product_id').val();
        if( prodid ){
            var url= baseUrl+"warehouse/stockproduct/"+prodid;
            getAjaxView(url,'','ware_view',false,'get');
        }
        else{
            alert("Please enter a product code")
            $('.loading').hide();
        }
        
    }
    
    function refresh(){
        location.reload();
    }
</script>

@endsection
