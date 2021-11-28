@extends('layouts.app')
@section('css')
@endsection
@section('content')

<style>
    .m-top {
        margin-top: 2.0rem !important;
    }

</style>
<div class="loading">Loading&#8230;</div>
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('warehouse.stock_details')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('warehouse.stock_details')</li>
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
                        <h3 class="card-title">@lang('warehouse.stock_details')</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
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
                            <div class="col-lg-2">
                                <label>
                                    @lang('product.bar_code')
                                </label>
                                <input type="text" id="product_id" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    From
                                </label>
                                <input type="date" id="from" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    To
                                </label>
                                <input type="date" id="to" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-1">
                                <label>
                                </label>
                                <div class="form-inline card_buttons">
                                    <button type="button" onclick="searching()" class="btn btn-sm btn-success mt-2"
                                        id="ledgers">@lang('layout.search')</button>
                                </div>
                            </div>
                            <div class="col-lg-1">
                                <button class="btn btn-info btn-sm m-top" onclick="refresh()">Refresh </button>
                            </div>
                            <div class="col-lg-1">
                                <form action="{{ route('warehouse.stock_details_print') }}" method="get">
                                    <input type="date" id="from_pdf" name="from" class="d-none">
                                    <input type="date" id="to_pdf" name="to" class="d-none">
                                    <input type="number" id="warehouse_id_pdf" name="warehouse_id" class="d-none">
                                    <button class="btn btn-primary btn-sm m-top" type="submit" id="downloadPdf">Download </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive" id="stockReportDetails">
                        <table id="stockReportDetailsTable" class="table table-sm table-bordered  nowrap"
                            width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>@lang('product.product_name')</th>
                                    <th>@lang('warehouse.warehouse')</th>
                                    <th>@lang('warehouse.v_date')</th>
                                    <th>@lang('warehouse.chalan_no')</th>
                                    <th>@lang('warehouse.stq_q')</th>
                                    <th>@lang('warehouse.sell_q')</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $total_in_qnty = 0;
                                    $total_out_qnty = 0;
                                    $available = 0;
                                @endphp
                                @foreach ($stock_report_details as $product)
                                <tr class="text-center">
                                    <td class="align-middle"
                                        rowspan="{{ $product->product->warehouse_insert->count() }}">
                                        {{ $product->products[0]->product_name }} - {{ $product->products[0]->product_id }} - {{ $product->products[0]->head_code }}</td>
                                    <td>
                                        {{ $product->products[0]->warehouse_insert->first()->warehouse[0]->name }}
                                    </td>
                                    <td>
                                        {{ $product->products[0]->warehouse_insert->first()->v_date }}
                                    </td>
                                    <td>{{ $product->products[0]->warehouse_insert->first()->chalan_no }}</td>
                                    <td>{{ $product->products[0]->warehouse_insert->first()->in_qnt }}</td>
                                    <td>{{ $product->products[0]->warehouse_insert->first()->out_qnt }}</td>
                                </tr>
                                @foreach( $product->products[0]->warehouse_insert as $key => $warehouse )
                                @if( $key != 0 )
                                <tr class="text-center">
                                    <td>{{ $warehouse->warehouse[0]->name }}</td>
                                    <td>{{ $warehouse->v_date }}</td>
                                    <td>{{ $warehouse->chalan_no }}</td>
                                    <td>{{ $warehouse->in_qnt }}</td>
                                    <td>{{ $warehouse->out_qnt }}</td>
                                </tr>
                                @endif
                                @php
                                    $total_in_qnty += $warehouse->in_qnt;
                                    $total_out_qnty += $warehouse->out_qnt;
                                @endphp
                                @endforeach
                                <tr>
                                    <td colspan="4" class="text-right text-bold">Total</td>
                                    <td class="text-center text-bold">{{ $total_in_qnty }}</td>
                                    <td class="text-center text-bold">{{ $total_out_qnty }}</td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="text-right text-bold">Available</td>
                                    <td colspan="2" class="text-center text-bold">{{ $total_in_qnty - $total_out_qnty }}</td>
                                </tr>
                                @php
                                    $total_in_qnty = 0;
                                    $total_out_qnty = 0;
                                    $available = 0;
                                @endphp
                                @endforeach

                            </tbody>
                        </table>
                        <span class="float-right">{{ $stock_report_details->links() }}</span> 
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
        var url = baseUrl + "warehouse/stock/" + warid;
        location.href = url;
    }

    function SearchProduct() {
        $('.loading').show();
        var prodid = $('#product_id').val();
        var url = baseUrl + "warehouse/stockproduct/" + prodid;
        getAjaxView(url, '', 'ware_view', false, 'get');
    }
    // Searching Function
    function searching(){
        $('.loading').show();
        var warehouse_id = $('#warehouse_id').val();
        var product_id = $('#product_id').val();
        var from = $('#from').val();
        var to = $('#to').val();
        
        if( warehouse_id && from && to ){
            var url= baseUrl+"warehouse/search/stock_report_details";
            var data = {warehouse_id: warehouse_id, product_id: product_id, from: from, to:to};
            getAjaxView(url,data=data,'stockReportDetails',false,'get');
            
        }
        else{
            alert("Please select warehouse / product / from date / to date")
            $('.loading').hide();
        }

        
    }

    function refresh() {
        location.reload();
    }

    $("#from").on('input',function(){
        let $this= $(this)
        $("#from_pdf").val($this.val())
    })
    $("#to").on('input',function(){
        let $this= $(this)
        $("#to_pdf").val($this.val())
    })
    $("#warehouse_id").on('change',function(){
        let $this= $(this)
        console.log($this.val())
        $("#warehouse_id_pdf").val($this.val())
    })

</script>
@endsection
