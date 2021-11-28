@extends('layouts.app')
@section('css')
<style>
    table thead tr th,
    table tbody tr td{
        font-size: 13px;
    }
</style>
@endsection
@section('content')
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Daily Sales Report</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Daily Sales Report</li>
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
                        <a href="{{ route('reports.daily_sales_report.search.pdf',$date) }}" class="btn btn-sm btn-info float-right" id="all_report">Download PDF</a>
                        <a href="{{ route('reports.daily_sales_report') }}" class="btn btn-sm btn-success float-right mr-2" id="all_report">Refresh</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <form action="{{ route('reports.daily_sales_report.search') }}" method="get">
                            @csrf
                            <div class="row">
                                <div class="col-md-10 col-12 form-group">
                                    <input type="date" class="form-control" name="date" value="{{ $date }}" required>
                                </div>
                                <div class="col-md-2 col-12 form-group">
                                    <button type="submit" class="btn btn-success" style="width: 100%">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div id="report_view" class="table-responsive">
                        <table id="ledgersTable" class="table table-sm table-bordered table-striped" width="100%">
                            <thead>
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">SI</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">CH No</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">CH Date</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Bill No</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Bill Date</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Party Code</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Party Name</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Sales Amount</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Per</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Discount Amount</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Total Amount <br>After Discount</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Sales Person</th>
                                    <th rowspan="2" style="vertical-align: middle;text-align: center;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $amount = 0;
                                    $discount = 0;
                                    $total_after_discount = 0;
                                @endphp
                                @forelse( $sell_requests as $key => $sell_request ) 
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $sell_request->voucher_no }}</td>
                                    <td>{{ $sell_request->v_date }}</td>
                                    <td>{{ $sell_request->req_id }}</td>
                                    <td>{{ $sell_request->del_date }}</td>
                                    <td>{{ $sell_request->customer->customer_id }}</td>
                                    <td>{{ $sell_request->customer->customer_name }}</td>
                                    <td>
                                        @php
                                            $products = \Modules\Seller\Entities\RequestProduct::with('products')->where('req_id',$sell_request->id)->where("del_qnt","!=",0)->get()
                                        @endphp
                                        
                                        @php
                                            $single_sales_amount = 0;
                                        @endphp
                                        @foreach($products as $key => $product)
                                            @php
                                                $single_sales_amount += ( $product->del_qnt * $product->products->price )
                                            @endphp
                                        @endforeach
                                        {{ $single_sales_amount }} 
                                        
                                        @php
                                            $amount += $single_sales_amount
                                        @endphp
                                        
                                    </td>
                                    <td>
                                        {{ $sell_request->sale_disc ?? 0 }}
                                    </td>
                                    <td>
                                        {{ $sell_request->del_discount }}
                                        @php
                                            $discount += $sell_request->del_discount
                                        @endphp
                                    </td>
                                    <td>
                                        {{ $single_sales_amount - $sell_request->del_discount }}
                                        @php
                                            $total_after_discount += ( $single_sales_amount - $sell_request->del_discount )
                                        @endphp
                                    </td>
                                    <td>{{ $sell_request->seller->name }} - {{$sell_request->seller->user_id}}</td>
                                    <td>{{ $sell_request->remarks }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="14" class="text-center">No Data Found</td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <td colspan="7"> Total : </td>
                                <td>{{ $amount }}</td>
                                <td></td>
                                <td>{{ $discount }}</td>
                                <td>{{ $total_after_discount }}</td>
                                <td></td>
                                <td></td>
                            </tfoot>
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


@endsection
