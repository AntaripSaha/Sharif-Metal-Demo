<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    <style>
        td {
            border-bottom: 1px solid black;
        }
    </style>
</head>

<body style="width: 90%;margin: auto;">
    <div class="card">
        <div class="card-header">
            <div class="col-md-6" style="text-align: center;margin-top: 20px;">
                <p style="font-size:22px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:45%;">
                    <span style="font-size:28px;">{{$title}}</span>
                </p><br>
                <span style="font-size: 34px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
                <span style="font-size: 20px;">{{$company_info->address}}</span><br><br>
            </div>
            <div class="col-md-12">
                <span style="font-size: 18px; text-align: right;float: right;">Date :
                    {{ Carbon\Carbon::parse($chalan->del_date)->format('d-m-Y') }}</span><br><br>
                <span style="font-size: 18px; text-align: left;float: left; width: 40%">Bill No :
                    {{$chalan->req_id}}</span>
                {{-- <span style="font-size: 18px; text-align: left;float: left;width: 0%">Challan No : {{$chalan->voucher_no}}</span>
                --}}
                {{-- <span style="font-size: 18px; text-align: left;float: right;">P.O No : {{$chalan->po_code}}</span>
                --}}
                <span style="font-size: 18px; text-align: left;float: right;">Challan No :
                    {{$chalan->voucher_no}}</span>
                <br>
            </div>
            <div class="col-md-12" style="margin-top: 7px">
                <span style="text-align: left;float: left;font-size: 18px;">Name :
                    {{$chalan->customer->customer_name}}</span>

                <span style="text-align: right;float: right;font-size: 18px;">P.O No : {{$chalan->po_code}}</span><br>
            </div>

            <div class="col-md-12" style="margin-top: 7px">
                <span style="text-align: left;float: left;font-size: 18px;">
                    Sales Person Name :
                    {{$sales_person_name->name}} - {{$sales_person_name->user_id}} </span>

            </div>
            <br>

            <div class="col-md-12" style="margin-top: 7px">
                <span style="text-align: left;float: left;font-size: 18px;">Address :
                    {{$chalan->customer->customer_address}}</span>
                <span style="text-align: right;float: right;font-size: 18px;">D/C.O Code :
                    {{$chalan->dco_code}}</span><br>

            </div>
        </div>
        <br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <table id="print_code" style="width: 630px;">
                <thead>
                    <tr style="width:100px">
                        <th style="width:20px">SL.No</th>
                        <th style="width:100px">Products Code</th>
                        <th style="width:300px !important;">Product Name</th>
                        <th style="width:20px">Head</th>
                        <th style="width:20px">QTY</th>
                        <th style="width:20px">Unit Price</th>
                        <th style="width:15px">Amount</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $sum_total = 0;
                    ?>

                    @foreach($products as $key => $product)
                    <tr style="margin-top:5px;">
                        <td style="text-align: center; width: 20px; height: 29px;">{{$loop->index + 1}}</td>
                        <td style="text-align: center; width: 10px;">{{$product->products->product_id}}</td>
                        <td style="max-width: 200px !important;">{{$product->products->product_name}}</td>
                        <td style="text-align: center; width: 20px;">
                            @if ($product->products->head_code)
                            {{ $product->products->head_code }}
                            @else
                            -
                            @endif
                        </td>
                        <td style="text-align: center; width: 20px;">{{$product->del_qnt}}</td>
                        <td style="text-align: center; width: 20px;">{{$product->products->price}}</td>
                        <td style="text-align: center; width: 15px;">{{$product->del_qnt * $product->products->price}}
                            <?php $sum_total += $product->del_qnt * $product->products->price ?></td>
                    </tr>
                    @endforeach

                    <?php $pcount = $products->count(); ?>
                    @if($pcount<10) @for ($i=$pcount; $i < 10; $i++) <tr>
                        <td style="padding: 8px; text-align: center; width: 20px;">{{$i+1}}</td>
                        <td style="padding: 8px; text-align: center; width: 10px;"></td>
                        <td style="padding: 8px; min-width: 200px !important;"></td>
                        <td style="padding: 8px; text-align: center; width: 20px;"></td>
                        <td style="padding: 8px; text-align: center; width: 20px;"></td>
                        <td style="padding: 8px; text-align: center; width: 20px;"></td>
                        <td style="padding: 8px; text-align: center; width: 15px;"></td>
                        </tr>
                        @endfor
                        @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-left" colspan="3" rowspan="4"><b style="font-size: 16px;">Taka(In
                                Words):</b><span style="font-size: 16px; margin-top: 0px;"> {{$amount}} </span></td>
                        <td colspan="2" style="padding: 5px;"><b style="font-size: 16px;">Total(Tk.)</b></td>
                        <td style="text-align: right;" colspan="2"><span style="margin-right: 13%;">=<?php echo ($sum_total) ?>/=</span></td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding: 5px;"><b style="font-size: 16px;">Discount(%)</b></td>
                        <td style="text-align: right;" colspan="2"><span style="margin-right: 13%;">
                                @php
                                $sale_discount = $chalan->sale_disc;
                                if($chalan->sale_discount_overwrite != null){
                                $sale_discount = $chalan->sale_discount_overwrite;
                                }else{
                                if($chalan->sale_disc == null){
                                $sale_discount = 0;
                                }else{
                                $sale_discount;
                                }
                                }
                                @endphp
                                {{ $sale_discount }}%
                            </span></td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding: 5px;"><b style="font-size: 16px;">Discount Amt.</b></td>
                        <td style="text-align: right;" colspan="2"><span style="margin-right: 13%;">({{(int)$chalan->del_discount}})</span></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 5px;"><b style="font-size: 16px;">Grand Total</b></td>
                        <td style="text-align: right;" colspan="2"><span style="margin-right: 13%;">={{ $sum_total - (int)$chalan->del_discount}}/=</span></td>
                    </tr>
                </tfoot>

            </table>
            <br>

            <div class="col-md-12">
                <div style="float: left;">
                    <br><br><br><br>
                    <span style="font-size: 18px; float: left;margin-top: 10px;border-top: 2px solid #000;"><b>Received
                            With Seal</b></span>
                </div>
                <div style="float: right;">
                    <br><br><br><br>
                    <span style="font-size: 18px; float: left;margin-top: 10px;border-top: 2px solid #000;"><b>For :
                            {{$company_info->name}}</b></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>