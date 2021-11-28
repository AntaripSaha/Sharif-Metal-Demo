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
                    <h1 class="m-0 text-dark">Paid Parties</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('customer.paid_customer')</li>
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
                        <h3 class="card-title">@lang('customer.paid_customer')</h3> 
                        @if(\Auth::User()->can('browse_customerledger',app('Modules\Customer\Entities\Customer')) || Auth::user()->isOfficeAdmin())
                        <button type="button" class="customer_ledger btn btn-warning btn-sm float-right">
                            <i class="fas fa-outdent"></i>
                            @lang('customer.customer_ledger')
                        </button>
                        @endif
                        @if(\Auth::user()->can('browse_creditcustomer',app('\Modules\Customer\Entities\Customer')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="credit_customer mr-2 btn btn-danger btn-sm float-right">
                            <i class="fas fa-outdent"></i>
                            @lang('customer.credit_customer')
                        </button>
                        @endif
                        @if(\Auth::user()->can('add_customer',app('\Modules\Customer\Entities\Customer')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="add_customer mr-2 btn btn-success btn-sm float-right">
                            <i class="fas fa-plus"></i>
                            @lang('customer.new_customer')
                        </button>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="paidcustomerTable" class="table table-bordered table-striped display responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>Party Name</th>
                                    <th>Address</th>
                                    <th>Mobile No</th>
                                    <th>Email</th>
                                    <th>Balance</th>
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
<script src="{{asset('js/Modules/Customer/paidindex.js')}}"></script>
@endsection
