@extends('layouts.app')
@section('css')
@endsection
@section('content')
<style>
    .m-top {
        margin-top: 2rem !important;
    }

</style>
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Sales Report - Seller Wise [Details]</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.sr')</li>
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
                        <h3 class="card-title">@lang('account.sr')</h3>
                        <a href="{{ route('reports.sales_person') }}">
                            <button class="btn btn-sm btn-info float-right" id="customer_all">Back</button>
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('customer.customer')
                                </label>
                                <select class="form-control form-control-sm" name="seller_id" id="seller_id">
                                    <option selected value="{{ $seller->id }}">{{ $seller->name }}</option>
                                    @foreach($sellers as $seller)
                                    <option value="{{$seller->id}}">{{$seller->user_id}} - {{$seller->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.company')
                                </label>
                                <select class="form-control form-control-sm" id="company_id">
                                    <option value="0">All</option>
                                    @foreach ($companies as $company)
                                    <option  value="{{ $company->id }}" @if ($company_id == $company->id) selected @endif>{{ $company->name }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    From
                                </label>
                                <input type="date" name="from" id="from" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    To
                                </label>
                                <input type="date" name="to" id="to" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-2">
                                <button class="btn btn-success btn-sm m-top" id="searching">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card" id="seller_details_report_view">
                    <table id="sellerDetailsTable"
                        class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th>Chalan No</th>
                                <th>RequestDate</th>
                                <th>DeliveryDate</th>
                                <th>Total Amount</th>
                                <th>DeliveryAmount</th>
                                <th>SaleDiscount</th>
                                <th>Company</th>
                                <th>FullyDelivered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($report_details->count() > 0)
                            @foreach ($report_details as $item)
                            <tr class="text-center">
                                <td>{{ $item->voucher_no }}</td>
                                <td>{{ $item->v_date }}</td>
                                <td>{{ $item->del_date }}</td>
                                <td>{{ $item->amount }}</td>
                                <td>{{ $item->del_amount }}</td>
                                <td>
                                    @if ($item->sale_disc == null)
                                    <span>0</span>
                                    @else
                                    <span>{{ $item->sale_disc }}</span>
                                    @endif
                                </td>
                                <td>{{ $item->company->name }}</td>
                                <td>
                                    @if ($item->fully_delivered == 1)
                                    <span class="badge badge-success">Yes</span>
                                    @else
                                    <span class="badge badge-danger">No</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="8">
                                    <center>
                                        <span class="badge badge-danger">No Record Found</span>
                                    </center>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('js')
<script type="text/javascript">
    $("#seller_id").select2()
        .on("select2:select", function (e) {
            var sel_element = $(e.currentTarget);
            var cus_val = sel_element.val();
            $('#seller_id').val(cus_val);
            console.log(cus_val)
        });

    $('#searching').on('click', function () {
        $('.loading').show();
        var seller_id = $("#seller_id").val();
        var company_id = $("#company_id").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var url = baseUrl + "reports/sell_report_by_seller_details_search";

        if (from && to) {
            var data = {
                company_id: company_id,
                seller_id: seller_id,
                from: from,
                to: to
            };
        } else {
            var data = {
                company_id: company_id,
                seller_id: seller_id,
            };
        }

        getAjaxView(url, data = data, 'seller_details_report_view', false, 'get');
    });

</script>
@endsection
