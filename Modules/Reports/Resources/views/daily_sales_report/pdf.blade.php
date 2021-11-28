<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    <style>
        td{
            border-bottom: 1px solid black;
        }
        tfoot{
            border-bottom: 1px solid black;
        }
        td,th{
            text-align : center;
        }
        thead tr th{
            font-size: 12px;
        }
    </style>
</head>

<body style="width: 100%;margin: auto;">
<div class="card">
    
    <!-- CARD HEADER START -->
    <div class="card-header" style="margin-top: -40px;">
        <div class="col-md-6" style="text-align: center;margin-top: 20px;">
            <p style="font-size:16px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:37%;">
                <span style="font-size:28px;">
                    {{$title}}
                </span>
            </p>
            <br>
            <span style="font-size: 25px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
            <span style="font-size: 20px;">{{$company_info->address}}</span><br>
            <span style="font-size: 20px;">Date : {{ Carbon\Carbon::now()->toDateString() }}</span><br><br>
        </div>
    </div>
    <!-- CARD HEADER END -->
    
    <br>
    
    <!-- CARD BODY START -->
    <div class="card-body" id="ledger_view">
        <div class="col-md-12" >
            <table id="ledgersTable" class="table table-sm table-bordered table-striped" width="1260px" >
                <thead>
                    <tr>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 10px">SI</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 80px">CH No</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 100px">CH Date</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 150px">Bill No</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 80px">Bill Date</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 80px">Party Code</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 150px">Party Name</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 100px">Sales Amount</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 10px">Per</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 80px">Discount Amount</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 80px">Total Amount <br>After Discount</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 150px">Sales Person</th>
                        <th rowspan="2" style="vertical-align: middle;text-align: center;width: 100px;">Remarks</th>
                        <th rowspan="2"></th>
                    </tr>
                    <tr></tr>
                </thead>
                <tbody>
                    @php
                        $amount = 0;
                        $discount = 0;
                        $total_after_discount = 0;
                    @endphp
                    @foreach( $sell_requests as $key => $sell_request )
                    
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
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="border-collapse: collapse;border-bottom: 2px solid #000;">
                    <td colspan="7"> Total : </td>
                    <td>{{ $amount }}</td>
                    <td></td>
                    <td>{{ $discount }}</td>
                    <td>{{ $total_after_discount }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tfoot>
            </table>
        </div>
       
        
    </div>
    <!-- CARD BODY END -->
    
    <div class="card-body" id="ledger_view" style="border-top: 2px solid #000; width: 1260px">
 
    
</div>
</body>

</html>
