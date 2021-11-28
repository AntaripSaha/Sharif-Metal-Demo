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
                    <h1 class="m-0 text-dark">Sales Report - Party Wise</h1>
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
                        <button class="btn btn-sm btn-info float-right" onclick="refresh()">Refresh</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('customer.customer')
                                </label>
                                <select class="form-control form-control-sm" name="customer_id" id="customer_id">
                                    <option selected disabled>@lang('layout.select')</option>
                                    @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">
                                        {{$customer->customer_id}}{{$customer->customer_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.company')
                                </label>
                                <select class="form-control form-control-sm" name="company_id" id="company_id">
                                    <option selected disabled>@lang('layout.select')</option>
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label>
                                    @lang('account.search_ledger')
                                </label>
                                <div class="form-inline card_buttons">
                                    <input type="date" class="form-control form-control-sm mr-sm-2" id="from"
                                        name="from">
                                    <input type="date" class="form-control form-control-sm mr-sm-2" id="to" name="to">
                                    <button type="button" class="btn btn-success btn-sm"
                                        id="report_Seller">@lang('layout.search')</button>
                                    <a href="{{ route('reports.customer_wise_print') }}">
                                        <button type="button" class="btn btn-primary btn-sm ml-2">Download</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div id="report_view" class="table-responsive">
                        <table id="ledgersTable" class="table table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Id No</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Party</th>
                                    @foreach($companies as $company)
                                    <th colspan="3" style="text-align: center;">{{$company->name}}</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">OB</th>
                                    @endforeach
                                    <th colspan="3" style="text-align: center;">Total</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">D/I</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">OB</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Action</th>
                                </tr>
                                <tr>
                                    @foreach($companies as $company)
                                    <th>Sells</th>
                                    <th>Collection</th>
                                    <th>Due</th>
                                    @endforeach
                                    <th>Sells</th>
                                    <th>Collection</th>
                                    <th>Due</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($customers as $customer)
                                <?php $ts = 0;?>
                                <?php $tc = 0;?>
                                <?php $td = 0;?>
                                <?php $s = 0;?>
                                <?php $d = 0;?>
                                <?php $c = 0;?>
                                <tr>
                                    <td>{{$customer->customer_id}}</td>
                                    <td>{{$customer->customer_name}}</td>
                                    @foreach($companies as $company)
                                    <td>
                                        @foreach($customer->sales_details as $sale)
                                        @if($company->id == $sale['company_id'])
                                        {{$sale['amount']}}
                                        <?php $s = $sale['amount'];?>
                                        <?php $ts = $s + $ts;?>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($customer->customer_receive as $receive)
                                        @if($company->id == $receive['company_id'])
                                        {{$receive['amount']}}
                                        <?php $c = $receive['amount'];?>
                                        <?php $tc = $c + $tc;?>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($customer->sales_details as $sale)
                                        @if($company->id == $sale['company_id'])
                                        {{$sale['del_discount']}}
                                        <?php $d = $sale['del_discount'];?>
                                        <?php $td = $d + $td;?>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($customer->customer_receive as $receive)
                                        @if($company->id == $receive['company_id'])
                                        <?php echo($s-$c);?>
                                        @endif
                                        @endforeach
                                    </td>
                                    @endforeach
                                    <td><?php echo($ts)?></td>
                                    <td><?php echo($tc)?></td>
                                    <td><?php echo($td)?></td>
                                    <td><?php echo($ts - $tc);?></td>
                                    <td><?php echo($ts - $tc);?></td>
                                    <td>
                                        <a
                                            href="{{ route('reports.customer_details', ['customer_id' => $customer->id, "company_id" => 0, "from"=>null, "to"=>null]) }}">
                                            <button class="btn btn-info btn-sm">Details</button>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <span class="float-right">{{ $customers->links() }}</span> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('js')
<script>
    $("#customer_id").select2()
        .on("select2:select", function (e) {
            var sel_element = $(e.currentTarget);
            var cus_val = sel_element.val();
            $('#customer_id').val(cus_val);
        });
    $('.card_buttons').on('click', '#report_Seller', function () {
        var company_id = $("#company_id").val();
        var customer_id = $("#customer_id").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var url = baseUrl + "reports/sell_report_by_customer";
        var data = {
            company_id: company_id,
            customer_id: customer_id,
            from: from,
            to: to
        };
        getAjaxView(url, data = data, 'report_view', false, 'get');
    });

    function refresh() {
        location.reload();
    }

</script>

@endsection
