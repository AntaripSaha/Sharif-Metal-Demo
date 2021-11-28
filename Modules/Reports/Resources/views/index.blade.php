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
                    <h1 class="m-0 text-dark">@lang('account.bank_ledgers')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.bank_ledgers')</li>
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
                        <h3 class="card-title">@lang('account.bank_ledgers')</h3>
                        @if(\Auth::user()->can('browse_transaction',app('\Modules\Bank\Entities\Bank')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="bank_transaction btn btn-info btn-sm float-right">
                            <i class="fas fa-outdent"></i>
                            @lang('account.bank_transaction')
                        </button>
                        @endif
                        @if(\Auth::user()->can('browse_bank',app('\Modules\Bank\Entities\Bank')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="manage_bank mr-2 btn btn-warning btn-sm float-right">
                            <i class="fas fa-list"></i>
                            @lang('account.manage_bank')
                        </button>
                        @endif
                        @if(Auth::user()->can('add_bank',app('\Modules\Bank\Entities\Bank')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="add_bank mr-2 btn btn-success btn-sm float-right">
                            <i class="fas fa-plus"></i>
                            @lang('account.new account')
                        </button>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4">
                                <label>
                                    @lang('account.bank name')
                                </label>
                                <select class="form-control" name="bank_id" id="bank_id">
                                    <option value="0">@lang('layout.select')</option>
                                    @foreach($company_info->banks as $bank)
                                    <option value="{{$bank->id}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-8">
                                <label>
                                    @lang('account.search_ledger')
                                </label>
                                <div class="form-inline card_buttons">
                                    <input type="date" class="form-control mr-sm-2" id="from" name="from">
                                    <input type="date" class="form-control mr-sm-2" id="to" name="to">
                                    <button type="button" class="btn btn-success" id="ledgers">@lang('layout.search')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
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
                            <p class="mt-4">@lang('layout.date'): {{$edate}}</p>
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" id="ledger_view">
                        <table id="ledgersTable" class="table table-bordered table-striped display responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th>@lang('layout.date')</th>
                                    <th>@lang('bank.bank_name')</th>
                                    <th>@lang('layout.description')</th>
                                    <th>@lang('bank.wd_id')</th>
                                    <th>@lang('bank.debit')</th>
                                    <th>@lang('bank.credit')</th>
                                    <th>@lang('bank.balance')</th>
                                </tr>
                            </thead>
                            <tbody >
                                
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
@section('js')
<script src="{{asset('js/Modules/Bank/ledgers.js')}}"></script>
@endsection
