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
                    <h1 class="m-0 text-dark">Supplier Management</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Suppliers</li>
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
                        <h3 class="card-title">Supplier List</h3> 
                        @if(\Auth::User()->can('browse_supplierledger',app('Modules\Supplier\Entities\Supplier')) || Auth::user()->isOfficeAdmin())
                        <button type="button" class="supplier_ledger btn btn-warning btn-sm float-right">
                            <i class="fas fa-outdent"></i>
                            @lang('supplier.supplier_ledger')
                        </button>
                        @endif
                        @if(\Auth::user()->can('add_supplier',app('\Modules\Supplier\Entities\Supplier')) ||
                        Auth::user()->isOfficeAdmin())
                        <button type="button" class="add_supplier mr-2 btn btn-success btn-sm float-right">
                            <i class="fas fa-plus"></i>
                            @lang('supplier.new_supplier')
                        </button>
                        @endif
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="supplierTable" class="table table-bordered table-striped display responsive nowrap"
                            width="100%">
                            <thead>
                                <tr>
                                    <th><input type="checkbox"></th>
                                    <th>Supplier Name</th>
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
<script src="{{asset('js/Modules/Supplier/index.js')}}"></script>
@endsection
