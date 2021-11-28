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
                    <h1 class="m-0 text-dark">@lang('supplier.supplier_ledgers')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('supplier.supplier_ledgers')</li>
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
                        <h3 class="card-title">@lang('supplier.supplier_ledgers')</h3>
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
                        <div class="form-group m-form__group row">
                            <div class="col-lg-2">
                                <label>
                                    @lang('company.company')
                                </label>
                                <select class="form-control" name="company_id" id="company_id">
                                    <option selected disabled>@lang('layout.select')</option>
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('supplier.supplier_name')
                                </label>
                                <select class="form-control" name="supplier_id" id="supplier_id">
                                    <option selected disabled>@lang('layout.select')</option>
                                    @foreach($suppliers as $supplier)
                                    <option value="{{$supplier->id}}">{{$supplier->id}} - {{$supplier->supplier_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-7">
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
                        <h3 class="card-title col-sm-10">Supplier ledger</h3>
                        <button class="btn btn-sm btn-info col-sm-2" id="ledger_all">All Ledgers</button> 
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body" id="ledger_view">
                        <table id="ledgersTable" class="table table-bordered table-striped display responsive nowrap"
                            width="100%">
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
                            <tbody >
                                <?php $sum_debit = 0 ?>
                                <?php $sum_credit = 0 ?>
                                <?php $balance = 0 ?>
                                @forelse($transactions as $transaction)
                                <tr>
                                    <td>{{$transaction->VDate}}</td>
                                    <td>{{$transaction->Narration}}</td>
                                    <td>{{$transaction->company->name}}</td>
                                    <td>{{$transaction->VNo}}</td>
                                    <td>{{$transaction->Debit}}</td>
                                    <td>{{$transaction->Credit}}</td>
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
                                    <td><span style="font-family: initial;">৳ </span><?php echo($sum_debit) ?></td>
                                    <td><span style="font-family: initial;">৳ </span><?php echo($sum_credit) ?></td>
                                    <td><span style="font-family: initial;">৳ </span><?php echo($balance) ?></td>
                                </tr>
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
<script src="{{asset('js/Modules/Supplier/ledgers.js')}}"></script>
@endsection
