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
                    <h1 class="m-0 text-dark">@lang('account.srp')</h1>
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
                        <button class="btn btn-sm btn-info float-right" id="all_report">Refresh</button>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.seller')
                                </label>
                                <select class="form-control" name="seller_id" id="seller_id">
                                    <option selected disabled>@lang('layout.select')</option>
                                    @foreach($sellers as $seller)
                                    <option value="{{$seller->id}}">{{$seller->user_id}} - {{$seller->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.company')
                                </label>
                                <select class="form-control" name="company_id" id="company_id">
                                    <option selected disabled>@lang('layout.select')</option>
                                    <option value="0">@lang('layout.all')</option>
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
                                    <input type="date" class="form-control mr-sm-2" id="from" name="from">
                                    <input type="date" class="form-control mr-sm-2" id="to" name="to">
                                    <button type="button" class="btn btn-success"
                                        id="report_Seller">@lang('layout.search')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div id="report_view" class="table-responsive">
                        <table id="ledgersTable" class="table table-sm table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Id No</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">SP</th>
                                    @foreach($companies as $company)
                                    <th colspan="3" style="text-align: center;">{{$company->name}}</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">OS</th>
                                    @endforeach
                                    <th colspan="3" style="text-align: center;">Total</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">D/I</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">OS</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Action</th>
                                </tr>
                                <tr>
                                    @foreach($companies as $company)
                                    <th>S</th>
                                    <th>C</th>
                                    <th>A</th>
                                    @endforeach
                                    <th>S</th>
                                    <th>C</th>
                                    <th>A</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sellers as $seller)
                                <?php $ts = 0;?>
                                <?php $tc = 0;?>
                                <?php $s = 0;?>
                                <?php $c = 0;?>
                                <tr>
                                    <td>{{$seller->user_id}}</td>
                                    <td>{{$seller->name}}</td>
                                    @foreach($companies as $company)
                                    <td>
                                        @foreach($seller->sales_details as $sale)
                                        @if($company->id == $sale['company_id'])
                                        {{$sale['amount']}}
                                        <?php $s = $sale['amount'];?>
                                        <?php $ts = $s + $ts;?>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach($seller->customer_receive as $receive)
                                        @if($company->id == $receive['company_id'])
                                        {{$receive['amount']}}
                                        <?php $c = $receive['amount'];?>
                                        <?php $tc = $c + $tc;?>
                                        @endif
                                        @endforeach
                                    </td>
                                    <td></td>
                                    <td>
                                        @foreach($seller->customer_receive as $receive)
                                        @if($company->id == $receive['company_id'])
                                        <?php echo($s-$c);?>
                                        @endif
                                        @endforeach

                                    </td>
                                    @endforeach
                                    <td><?php echo($ts)?></td>
                                    <td><?php echo($tc)?></td>
                                    <td></td>
                                    <td><?php echo($ts - $tc);?></td>
                                    <td><?php echo($ts - $tc);?></td>
                                    <td class="text-center">
                                        <a data-toggle="tooltip" data-placement="top" title="View Details"
                                            href="{{ route('reports.seller_details',["id"=>$seller->id, "company_id"=>0,"from"=>0,"to"=>0]) }}">
                                            <i class="fas fa-desktop" style="cursor:pointer;"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- <span class="float-right">{{ $sellers->links() }}</span> --}}
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
    //document.getElementById('from').value = moment().format('YYYY-MM-DD');
    //document.getElementById('to').value = moment().format('YYYY-MM-DD');
    $("#seller_id").select2()
        .on("select2:select", function (e) {
            var sel_element = $(e.currentTarget);
            var cus_val = sel_element.val();
            $('#seller_id').val(cus_val);
            console.log(cus_val)
        });
        
    $('.card_buttons').on('click', '#report_Seller', function () {
        var seller_id = $("#seller_id").val();
        var company_id = $("#company_id").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var url = baseUrl + "reports/sell_report_by_seller";
        var data = {
            company_id: company_id,
            seller_id: seller_id,
            from: from,
            to: to
        };
        getAjaxView(url, data = data, 'report_view', false, 'get');
    });

    $('.card_buttons').on('click', '#all_report', function () {
        location.reload();
    });

</script>

@endsection
