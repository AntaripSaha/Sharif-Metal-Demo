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
                    <h1 class="m-0 text-dark">@lang('account.balance_sheet')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.balance_sheet')</li>
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
                    <div class="card-header card_buttons row">
                        <h2 class="card-title col-4 mt-2">Search By Date</h2>
                        <div class="form-inline card_buttons col-8">
                            <form action="{{ route('search.balance') }}" method="get" style='display: flex;'>
                                @csrf
                                <label for="from" class="mr-2">@lang('layout.from') : </label>
                                <input type="date" class="form-control mr-sm-2" id="from" name="from">
                                <label for="to" class="mr-2">@lang('layout.to') : </label>
                                <input type="date" class="form-control mr-sm-2" id="to" name="to">
                                <button type="submit" class="btn btn-success">@lang('layout.search')</button>
                            </form>
                            {{-- Refresh Searching --}}
                            <button type="button" class="btn btn-info ml-3" onclick="refresh()">Refresh</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" id="ledger_view">
                        <table id="ledgersTable" class="table table-bordered table-striped table-sm responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th style="vertical-align: middle;text-align: center;" rowspan="2">@lang('account.account')</th>
                                    <th style="vertical-align: middle;text-align: center;" rowspan="2">@lang('account.account_name')</th>
                                    <th style="vertical-align: middle;text-align: center;" colspan="2">@lang('account.balance')</th>
                                </tr>
                                <tr>
                                    <td class="text-bold">@lang('account.debit')</td>
                                    <td class="text-bold">@lang('account.credit')</td>
                                </tr>
                            </thead>
                            <tbody id="tableData">
                                @foreach($accounts as $account)
                                @if($account->HeadLevel == 0 || $account->HeadLevel == 1)
                                <tr class="table-active">
                                    <td colspan="8" class="table-light text-bold">{{$account->HeadCode}} - {{$account->HeadName}}</td>
                                </tr>
                                @else
                                <tr>
                                    <td>{{$account->HeadCode}}</td>
                                    <td>{{$account->HeadName}}</td>
                                    @endif
                                </tr>
                                
                                @if(count($account->childrenRecursive))
                                   @include('accounts::trial_partial_balance_sheet',['childs' => $account->childrenRecursive,'head'=>$account->HeadCode])
                                @endif
                                @endforeach
                            </tbody>

                            <tbody id="searchTableData">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content --> 
@endsection
<?php
    $fromSearchDate = '2021-02-22';
    $toSearchDate = '2021-12-22';
?>
@section('js')
<script type="text/javascript">
    function refresh(){
        location.reload();
    }
</script>

<script src="{{asset('js/Modules/Bank/ledgers.js')}}"></script>
@endsection
