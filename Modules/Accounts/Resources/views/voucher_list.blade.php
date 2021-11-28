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
                    <h1 class="m-0 text-dark">Voucher List</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Vouchers</li>
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
                        <h3 class="card-title">All Vouchers</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="debtAccVoucher">
                                <thead class="text-center">
                                    <tr>
                                        <th>@lang('account.vno')</th>
                                        <th>@lang('account.v_type')</th>
                                        <th>@lang('account.narration')</th>
                                        <th>@lang('account.debit')</th>
                                        <th>@lang('account.credit')</th>
                                        <th>@lang('layout.date')</th>
                                        <th>@lang('layout.status')</th>
                                        <th>@lang('layout.action')</th>
                                    </tr>
                                </thead>
                                <tbody id="debitvoucher" class="text-center">
                                    @foreach($vouchers as $voucher)
                                    <tr>
                                        <td>{{$voucher->VNo}}</td>
                                        <td>{{$voucher->Vtype}}</td>
                                        <td>{{$voucher->remark}}</td>
                                        <td>
                                            @if($voucher->Vtype == 'CRV')
                                                {{'0.00'}}
                                            @else
                                                {{$voucher->Debit}}
                                            @endif
                                        </td>
                                        <td>
                                            @if($voucher->Vtype == 'DV')
                                                {{'0.00'}}
                                            @else
                                                {{$voucher->Credit}}
                                            @endif
                                        </td>
                                        <td>{{$voucher->VDate}}</td>
                                        <td>@if($voucher->IsAppove == 1)
                                                <span class="badge badge-success">Approved</span>
                                            @else
                                                <span class="badge badge-warning">Pending</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a class="mr-2 cp view-tr btn btn-info btn-sm" id="view-tr-{{$voucher->VNo}}"> View</a>
                                            <a class="mr-2 cp print-tr btn btn-success btn-sm" id="print-tr-{{$voucher->VNo}}"> Print</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
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
<script src="{{asset('js/Modules/Accounts/voucher.js')}}"></script>
@endsection
