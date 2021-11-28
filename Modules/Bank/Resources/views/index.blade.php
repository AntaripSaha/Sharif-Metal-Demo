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
                    <h1 class="m-0 text-dark">Bank Accounts</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Accounts</li>
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
                        <h3 class="card-title">Bank Accounts List</h3> 
                        @if(\Auth::User()->can('browse_ledger',app('Modules\Bank\Entities\Bank')) || Auth::user()->isOfficeAdmin())
                        <button type="button" class="bank_ledger btn btn-warning btn-sm float-right">
                            <i class="fas fa-outdent"></i>
                            @lang('account.bank_ledger')
                        </button>
                        @endif
                        @if(\Auth::user()->can('browse_transaction',app('\Modules\Bank\Entities\Bank')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="bank_transaction mr-2 btn btn-info btn-sm float-right">
                            <i class="fas fa-outdent"></i>
                            @lang('account.bank_transaction')
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
                        <table id="accountTable" class="table table-bordered table-striped display responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>Bank Name</th>
                                    <th>Account Name</th>
                                    <th>Account No</th>
                                    <th>Branch</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
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
<script src="{{asset('js/Modules/Bank/index.js')}}"></script>
@endsection
