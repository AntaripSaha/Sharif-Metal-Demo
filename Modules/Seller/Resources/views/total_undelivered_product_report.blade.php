@extends('layouts.app')
@section('css')
@endsection
@section('content')
<style>
    .m-top {
        margin-top: 2rem !important;
    }

</style>
<section class="content" id="ajaxview">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Undelivered Product Reports</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Undelivered Product Reports</li>
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
                        <div class="row">
                            <div class="col-sm-3">
                                <label>Product Name</label>
                                <select class="form-control-sm form-control js-example-basic-single" id="product_id">
                                    <option selected disabled>Select Product</option>
                                    @foreach ($product as $p)
                                    <option value="{{ $p->id }}">{{ $p->product_id }} - {{ $p->product_name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-sm-3">
                                <label>From Date</label>
                                <input type="date" name="from_date" id="from_date" class="form-control form-control-sm">
                            </div>

                            <div class="col-sm-2">
                                <label>To Date</label>
                                <input type="date" name="to_date" id="to_date" class="form-control form-control-sm">
                            </div>

                            <div class="col-sm-2">
                                <button class="btn btn-info btn-sm m-top" onclick="searching()"><i
                                        class="fas fa-search"></i></button>
                                <button class="btn btn-success btn-sm m-top" onclick="refresh()"><i
                                        class="fas fa-sync-alt"></i></button>
                                <a href="{{ route('undelivered_product_print') }}">
                                    <button class="btn btn-primary btn-sm m-top"><i class="fa fa-print"></i><span
                                            class="ml-1">Print</span></button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="undeliveredProductTable">
                        <table id="productTable"
                            class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr class="text-center">
                                    <th>SL No</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                    <th>Total Undelivered</th>
                                    <th>Unit Price</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                $i = 1;
                                $in_total_amount = 0;
                                @endphp
                                @foreach ($undelivered_products as $undelivered)
                                @if ($undelivered->undelivered_product != 0)
                                <tr class="text-center">
                                    <td>{{ $i }}</td>
                                    <td>{{ $undelivered->products->product_id }}</td>
                                    <td>{{ $undelivered->products->product_name }}</td>
                                    <td>{{ $undelivered->undelivered_product }}</td>
                                    <td>{{ $undelivered->products->price }}</td>
                                    <td>{{ $undelivered->products->price * $undelivered->undelivered_product }}</td>
                                </tr>
                                @php
                                $i++;
                                $in_total_amount += ($undelivered->products->price * $undelivered->undelivered_product)
                                @endphp
                                @endif
                                @endforeach
                            </tbody>

                            <tfoot>
                                <tr>
                                    <td colspan="5" class="text-right text-bold"><strong>Total Amount</strong></td>
                                    <td class="text-center text-bold">{{ $in_total_amount }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
@section('js')
<script>
    $(document).ready(function () {
        $('.js-example-basic-single').select2();
    });
    // Searching Function
    function searching() {
        var product_id = $('#product_id').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();

        if (!product_id && !from_date && !to_date) {
            swal("", "Please Select Product or Date Range", "error");
        } else {
            $('.loading').show();
            var url = baseUrl + "seller/undelivered_product_search";
            var data = {
                product_id: product_id,
                from_date: from_date,
                to_date: to_date
            };
            getAjaxView(url, data = data, 'undeliveredProductTable', false, 'get');
        }
    }
    // Refresh Function
    function refresh() {
        location.reload();
    }

</script>
@endsection
