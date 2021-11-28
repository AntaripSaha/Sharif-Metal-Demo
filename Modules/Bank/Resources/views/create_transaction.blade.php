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
                    <h1 class="m-0 text-dark">Bank Transaction</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Transaction</li>
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
                        <h3 class="card-title">Bank Transaction</h3>
                        @if(\Auth::user()->can('browse_bank',app('\Modules\Bank\Entities\Bank')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="manage_bank btn btn-warning btn-sm float-right">
                            <i class="fas fa-list"></i>
                            @lang('account.manage_bank')
                        </button>
                        @endif
                        @if(\Auth::user()->can('add_bank',app('\Modules\Bank\Entities\Bank')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="add_bank mr-2 btn btn-success btn-sm float-right">
                            <i class="fas fa-plus"></i>
                            @lang('account.new account')
                        </button>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>'bank.transactions','id'=>'transaction-add-form','enctype'=>"multipart/form-data"]) !!}
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.date')
                                </label>
                                <input type="date" id="VDate" name="VDate" class="form-control" required>
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    @lang('account.type')
                                </label>
                                <select class="form-control" id="ac_type" name="ac_type">
                                    <option value="1">Debit(+)</option>
                                    <option value="0">Credit(-)</option>
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <label>
                                    @lang('bank.bank_name')
                                </label>
                                <select class="form-control" name="bank_id">
                                    @foreach($banks as $bank)
                                    <option value="{{$bank->bank_id}}">{{$bank->bank_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-6">
                                <label>
                                    @lang('bank.wd_id')
                                </label>
                                <input type="text" id="VNo" name="VNo" class="form-control" placeholder="@lang('bank.wd_id')">
                            </div>
                            <div class="col-lg-6">
                                <label>
                                    @lang('bank.amount')
                                </label>
                                <input type="number" id="amount" name="amount" class="form-control" placeholder="@lang('bank.amount')">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12">
                                <label>
                                    @lang('layout.description')
                                </label>
                                <textarea name="Narration" id="narration" cols="30" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12 transaction_submit">
                                <button type="button" id="transaction_create" class="btn btn-success">@lang('layout.save')</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- ./col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('js')
<script src="{{asset('js/Modules/Bank/transaction.js')}}"></script>
@endsection
