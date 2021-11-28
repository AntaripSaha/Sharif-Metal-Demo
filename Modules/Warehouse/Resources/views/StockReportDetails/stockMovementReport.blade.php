@extends('layouts.app')
@section('css')
@endsection
@section('content')

<style>
    .m-top {
        margin-top: 2.0rem !important;
    }

</style>

<section class="content" id="ajaxview">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('warehouse.stock_details')</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Stock Movement Report</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <h3 class="card-title">@lang('warehouse.stock_details')</h3>
                    </div>

                    <form method="POST" action="{{ route('warehouse.stock_movement_print') }}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group m-form__group row">
                                <div class="col-lg-3">
                                    {{-- <label>
                                        @lang('warehouse.warehouse')
                                    </label>
                                    <select class="form-control form-control-sm" name="warehouse_id" id="warehouse_id">
                                        <option value="0">@lang('layout.select')</option>
                                        @foreach($warehouses as $warehouse)
                                        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                                        @endforeach
                                    </select> --}}
                                </div>
                                <div class="col-lg-2">
                                    {{-- <label>
                                        @lang('product.bar_code')
                                    </label>
                                    <input type="text" id="product_id" class="form-control form-control-sm"> --}}
                                </div>

                                <div class="col-lg-2">
                                    <label>
                                        From
                                    </label>
                                    <input type="date" id="from" name="from" class="form-control form-control-sm">
                                </div>
                                <div class="col-lg-2">
                                    <label>
                                        To
                                    </label>
                                    <input type="date" id="to" name="to" class="form-control form-control-sm">
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
                                    <button class="btn btn-info btn-sm m-top" onclick="refresh()"> <i
                                            class="fa fa-refresh" aria-hidden="true"></i>Refresh </button>
                                </div>
                                <div class="col-lg-1">
                                    {{-- <a href="{{ route('warehouse.stock_movement_print') }}"> --}}
                                    <button class="btn btn-primary btn-sm m-top"><i class="fa fa-print mr-1"
                                            aria-hidden="true"></i> Print </button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive" id="stockReportDetails">
                        <table id="stockMovementReport" class="table table-sm table-bordered  nowrap" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>Particulars</th>
                                    <th>Head</th>
                                    <th>Inwards (Qnty)</th>
                                    <th>Outwards (Qnty)</th>
                                    <th>Closing Balance (Qnty)</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($data as $report)
                                <tr>
                                    <td>{{ $report['particulars'] }}</td>
                                    <td style="text-align: center">
                                        @if ($report['head_code'])
                                            {{ $report['head_code'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="text-align: right;">{{ $report['inwards'] }} Psc.</td>
                                    <td style="text-align: right;">{{ $report['outwards'] }} Psc.</td>
                                    <td style="text-align: right;">{{ $report['closing_balance'] }} Psc.</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <span class="float-right">{{ $data->links() }}</span>
                        </div>
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
    function searching() {
        $('.loading').show();
        var warehouse_id = $('#warehouse_id').val();
        var product_id = $('#product_id').val();
        var from = $('#from').val();
        var to = $('#to').val();

        var url = baseUrl + "warehouse/search/stock_movement_report";
        var data = {
            warehouse_id: warehouse_id,
            product_id: product_id,
            from: from,
            to: to
        };
        getAjaxView(url, data = data, 'stockReportDetails', false, 'get');
    }

    function refresh() {
        location.reload();
    }

</script>
@endsection
