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
                    <h1 class="m-0 text-dark">Sales Report - Party Wise [Details]</h1>
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
                        <a href="{{ route('reports.customer_wise') }}">
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
                                {{-- <select class="form-control" name="customer_id" id="customer_id">
                                    <option selected disabled>@lang('layout.select')</option>
                                    @foreach($customers as $customer)
                                    <option value="{{$customer->id}}">{{$customer->customer_id}}{{$customer->customer_name}}</option>
                                    @endforeach
                                </select> --}}
                                <input type="text" name="" id="" class="form-control" value="{{ $customer->customer_name }}" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.company')
                                </label>
                                <input type="text" name="" id="" class="form-control" value="{{ $company }}" readonly>
                            </div>
                            {{-- <div class="col-lg-6">
                                <label>
                                    @lang('account.search_ledger')
                                </label>
                                <div class="form-inline card_buttons">
                                    <input type="date" class="form-control mr-sm-2" id="from" name="from">
                                    <input type="date" class="form-control mr-sm-2" id="to" name="to">
                                    <button type="button" class="btn btn-success" id="report_Seller">@lang('layout.search')</button>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <table id="ledgersTable" class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>@lang('layout.date')</th>
                                <th>@lang('layout.description')</th>
                                <th>@lang('company.company')</th>
                                <th>@lang('layout.voucher_no')</th>
                                <th>@lang('bank.debit')</th>
                                <th>@lang('bank.credit')</th>
                                <th>@lang('bank.balance')</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $sum_debit = 0 ?>
                            <?php $sum_credit = 0 ?>
                            <?php $balance = 0 ?>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>{{$transaction->VDate}}</td>
                                <td>{{$transaction->Narration}}</td>
                                <td>{{$transaction->company->name}}</td>
                                <td>{{$transaction->VNo}}</td>
                                <td><span style="font-family: initial;"></span>{{$transaction->Debit}}</td>
                                <td><span style="font-family: initial;"></span>{{$transaction->Credit}}</td>
                                <td>
                                    @if($transaction->Credit > 0)
                                    <?php $sum_credit += $transaction->Credit ?>
                                    @else
                                    <?php $sum_debit += $transaction->Debit ?>
                                    @endif
                                    <?php $balance = $sum_debit-$sum_credit ?>
                                    <?php echo($balance) ?>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">No Data Found</td>
                            </tr>
                            @endforelse
                            <tr>
                                <td colspan="4" class="text-right">Grand total :</td>
                                <td><span style="font-family: initial;"></span>
                                    <?php echo($sum_debit) ?>
                                </td>
                                <td><span style="font-family: initial;"></span>
                                    <?php echo($sum_credit) ?>
                                </td>
                                <td><span style="font-family: initial;"></span>
                                    <?php echo($balance) ?>
                                </td>
                            </tr>
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
{{-- <script>
    $("#customer_id").select2()
    .on("select2:select", function(e) {
        var sel_element = $(e.currentTarget);
        var cus_val = sel_element.val();
        $('#customer_id').val(cus_val);
    });
  $('.card_buttons').on('click', '#report_Seller', function(){
        var company_id = $("#company_id").val();
        var customer_id = $("#customer_id").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var url= baseUrl+"reports/sell_report_by_customer";
        var data = {company_id:company_id,customer_id:customer_id,from:from,to:to};
        getAjaxView(url,data=data,'report_view',false,'get');
  });
  $('.card_buttons').on('click', '#company_all', function(){
    location.reload();
  });
</script> --}}

@endsection
