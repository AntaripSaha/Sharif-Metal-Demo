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
                    <h1 class="m-0 text-dark">@lang('account.trial_balace')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.trial_balace')</li>
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
                    <form method="get" action="{{ route('accounts.trail_balance_by_date') }}">
                        @csrf
                        <div class="card-header card_buttons row">
                            <h2 class="card-title col-4 mt-2">Search By Date</h2>
                            <div class="form-inline card_buttons col-8">
                                <label for="from" class="mr-2">@lang('layout.from') : </label>
                                <input type="date" class="form-control mr-sm-2 form-control-sm" id="from" name="from">
                                <label for="to" class="mr-2">@lang('layout.to') : </label>
                                <input type="date" class="form-control mr-sm-2 form-control-sm" id="to" name="to">
                                <button type="submit" class="btn btn-success btn-sm">@lang('layout.search')</button>
                                <button type="button" class="btn btn-info ml-2 btn-sm" onclick="printPdf()">Download PDF</button>
                            </div>
                        </div>
                    </form>
                    <!-- /.card-header -->
                    <div id="ledger_view">
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
                                <p class="mt-4">@lang('layout.date'): {{ \Carbon\Carbon::now()->toDateString() }}
                                </p>
                            </div>
                        </div>

                        <div class="card-body" >
                            <div class="col-md-12">
                                <h5 class="text-center" id="gl_title">Trial Balance [All]</h5>
                            </div>
                            <table id="ledgersTable" class="table table-bordered table-striped table-sm responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th style="vertical-align: middle;text-align: center;" rowspan="2">
                                            @lang('account.account')</th>
                                        <th style="vertical-align: middle;text-align: center;" rowspan="2">
                                            @lang('account.account_name')</th>
                                        <th style="vertical-align: middle;text-align: center;" colspan="2">
                                            @lang('account.brought_forward')</th>
                                        <th style="vertical-align: middle;text-align: center;" colspan="2">
                                            @lang('account.this_period')</th>
                                        <th style="vertical-align: middle;text-align: center;" colspan="3">
                                            @lang('account.balance')</th>
                                    </tr>
                                    <tr>
                                        {{-- Debit Credit for Brought Forward --}}
                                        <td class="text-bold">@lang('account.debit')</td>
                                        <td class="text-bold">@lang('account.credit')</td>

                                        {{-- Debit Credit for This Period --}}
                                        <td class="text-bold">@lang('account.debit')</td>
                                        <td class="text-bold">@lang('account.credit')</td>

                                        {{-- Debir Credit for Total Balance --}}
                                        <td class="text-bold">@lang('account.debit')</td>
                                        <td class="text-bold">@lang('account.credit')</td>
                                    </tr>
                                </thead>
                                <tbody id="tableData">
                                    @foreach($accounts as $account)
                                    @if($account->HeadLevel == 0 || $account->HeadLevel == 1)
                                    <tr class="table-active">
                                        <td colspan="8" class="table-light text-bold">{{$account->HeadCode}} -
                                            {{$account->HeadName}}</td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{$account->HeadCode}}</td>
                                        <td>{{$account->HeadName}}</td>
                                        @endif
                                    </tr>
                                    {{-- Total Debit Credit --}}
                                    <span id="dc">
                                        @if(count($account->childrenRecursive))
                                        @include('accounts::trial_partial ',
                                        [
                                            'childs' => $account->childrenRecursive,
                                            'head'=>$account->HeadCode,
                                        ])
                                        @endif
                                    </span>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr class="text-bold">
                                        <td>
                                            <strong>Grand Total</strong>
                                        </td>
                                        <td></td>
                                        <td class="text-center">{{ $grand_total_brought_forward_debit }}</td>
                                        <td class="text-center">{{ $grand_total_brought_forward_credit }}</td>

                                         <td class="text-center">{{ $grand_total_this_period_debit }}</td>
                                        <td class="text-center">{{ $grand_total_this_period_credit }}</td>

                                        <td class="text-center">{{ $grand_total_brought_forward_debit  +  $grand_total_this_period_debit }}</td>
                                        <td class="text-center">{{ $grand_total_brought_forward_credit + $grand_total_this_period_credit }}</td>
                                    </tr>
                                </tfoot>
                            </table>
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
<script type="text/javascript" src="{{ asset('js/html2pdf.min.js') }}"></script>
<script>
    var date = new Date();
    var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
    var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

    function convert(str) {
        var date = new Date(str),
            mnth = ("0" + (date.getMonth() + 1)).slice(-2),
            day = ("0" + date.getDate()).slice(-2);
        return [date.getFullYear() ,mnth, day].join("-");

    }

    document.getElementById('from').value = convert(firstDay);
    document.getElementById('to').value = convert(lastDay);

    $("#bank_id").select2()
        .on("select2:select", function (e) {
            var sel_element = $(e.currentTarget);
            var cus_val = sel_element.val();
            $('#bank_id').val(cus_val);
    });


    function printPdf() {
        const invoice = document.getElementById("ledger_view")
        var opt = {
            margin: [0.34,0.1,0.0,0.1],
            filename: 'TrialBalance[All].pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 1
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        }
        html2pdf().from(invoice).set(opt).save()
    }
</script>
<script src="{{asset('js/Modules/Bank/ledgers.js')}}"></script>
@endsection
