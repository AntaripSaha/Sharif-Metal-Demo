<!-- First Print Copy Start -->
<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
</head>

<body style="width: 90%;margin: auto;">
    <!-- First Print Copy Start -->
    <div class="card">
        <div class="card-header">
            <div class="col-md-12" style="text-align: center;margin-top: 30px;">
                <p style="font-size:22px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:38%;">
                    <span style="font-size:30px;">{{$title}}</span>
                </p><br>
                {{-- <span style="font-size: 30px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
                <span style="font-size: 20px;">{{$company_info->address}}</span><br><br> --}}
            </div>
            <hr>
            <!-- PartyCode & RequisitionNo Start -->
            <div class="col-md-12">
                <span style="font-size: 16px; text-align: left;float: left; width: 63%;">PartyCode
                    <span style="margin-left:25px; font-weight: bolder;">:</span> {{$data->customer_id}}</span>
                <span style="font-size: 16px; text-align: left;float: left;">RequisitionNo :
                    {{$sale_request->req_id}}
                </span>
                <br>
            </div>
            <!-- PartyCode & RequisitionNo End -->

            <!-- PartyName Start -->
            <div class="col-md-6" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 100%">PartyName
                    <span style="margin-left:20px; font-weight: bolder;">:</span> {{ $data->customer_name }}</span>
                <br>
            </div>
            <!-- PartyName  End -->

            <!-- PartyName Start -->
            <div class="col-md-6" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 100%">Party Address
                    <span style="margin-left:2px; font-weight: bolder;">:</span> {{$sale_request->pname}} </span>
                <br>
            </div>
            <!-- PartyName  End -->

            <!-- seller name Start -->
            <div class="col-md-6" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 100%">S/E Name
                    <span style="margin-left:25px; font-weight: bolder;">:</span> {{$sale_request->seller->name}} </span>
                <br>
            </div>
            <!-- seller name  End -->

            <!-- Req. Date Start and phone No start -->
            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 63%">Req. Date
                    <span style="margin-left:27px; font-weight: bolder;">:</span>
                    {{ Carbon\Carbon::parse($sale_request->v_date)->format('d-m-Y') }}
                </span>
                <span style="text-align: right;float: left;font-size: 16px;">Phone No
                    <span style="margin-left:30px; font-weight: bolder;">:</span> {{ $sale_request->phn_no }} </span><br>
            </div>
            <!-- Req. Date Start and phone No end -->


            <!-- P/O Code & Seller Name Start -->
            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 63%">P/O Code
                    <span style="margin-left:30px; font-weight: bolder;">:</span>
                    @if($sale_request->po_code)
                    {{$sale_request->po_code}}
                    @else
                    N/A
                    @endif
                </span>
                <span style="text-align: right;float: left;font-size: 16px;">Approval
                    <span style="margin-left:31px; font-weight: bolder;">:</span>
                    @if ($sale_request->is_approved == 0)
                    <span style="font-size: 16px;">Not Approved</span>
                    @else
                    <span style="font-size: 16px;">Approved</span>
                    @endif
                </span><br>
            </div>
            <!-- P/O Code & Seller Name Start -->

            <!--  Due Amount Start -->
            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 63%">Due Amount
                    <span style="margin-left:10px; font-weight: bolder;">:</span> {{$sale_request->due_amount}}</span>

                <span style="text-align: left;float: left;font-size: 16px; width: 27%">Voucher No
                    <span style="margin-left:10px; font-weight: bolder;">:</span> {{$sale_request->voucher_no}}</span>

            </div>
            <!-- Due Amount End -->

        </div>
        <br><br><br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <table id="print_code">
                <thead>
                    <tr>
                        <th style="width: 20%; font-size: 15px; background-color: #d5d5d5;">SL.No</th>
                        <th style="width: 35%; font-size: 15px; background-color: #d5d5d5;">Products Code</th>
                        <th style="font-size: 15px; background-color: #d5d5d5;">Product Name</th>
                        <th style="width: 25%; font-size: 15px; background-color: #d5d5d5;">QTY</th>
                        <th style="width: 25%; font-size: 15px; background-color: #d5d5d5;">Head</th>
                        <th style="width: 35%; font-size: 15px; background-color: #d5d5d5;">Unit Price</th>
                        <th style="width: 15%; font-size: 15px; background-color: #d5d5d5;">Discount(%)</th>
                        <th style="width: 35%; font-size: 15px; background-color: #d5d5d5;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sum_total = 0 ?>
                    @php
                    // Declear Some Varibale
                    $sum_total = 0;
                    $in_total = 0;
                    @endphp
                    @foreach($req_products as $product)
                    <tr>
                        <td style="text-align: center; font-size: 15px; width: 20%;">{{$loop->index + 1}}</td>
                        <td style="text-align: center; font-size: 15px; width: 30%">{{$product->products->product_id}}
                        </td>
                        <td style="text-align: center; font-size: 15px;">{{$product->products->product_name}}</td>
                        <td style="text-align: center; font-size: 15px; width: 25%">{{$product->qnty}}</td>
                        <td style="text-align: center; font-size: 15px; width: 25%">
                            @if ($product->products->head_code)
                            {{ $product->products->head_code }}
                            @else
                            -
                            @endif
                        </td>
                        <td style="text-align: center; font-size: 15px; width: 35%">{{$product->products->price}}</td>
                        <td style="text-align: center; font-size: 15px; width: 15%">{{ $product->prod_disc }}</td>
                        <td style="text-align: center; font-size: 15px; width: 35%">
                            @if ($product->prod_disc == 0 || $product->prod_disc == null)
                            @php
                            $prod_new_price = $product->qnty * $product->products->price
                            @endphp
                            <span style="font-size: 15px">{{ $prod_new_price }}</span>
                            @else
                            @php
                            $prod_new_price = ($product->qnty * $product->products->price) - ((($product->qnty *
                            $product->products->price) * $product->prod_disc) / 100);
                            @endphp
                            <span style="font-size: 15px">{{ $prod_new_price }}</span>
                            @endif
                        </td>
                        @php
                        $in_total += $prod_new_price;
                        @endphp
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-left" colspan="5" rowspan="2"><b style="font-size: 14px;">Transport Name :
                            </b><span style="font-size: 16px;"> {{ $sale_request->transp_name }} </span></td>
                        <td colspan="2" style="padding: 5px; background-color: #d5d5d5;"><b style="font-size: 14px;">In
                                Total
                                (Tk)</b></td>
                        <td style="text-align: center;background-color: #d5d5d5;"><span style="font-size: 14px;">{{ $in_total }}</span></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 5px;"><b style="font-size: 14px;">Sale Discount(%)</b></td>
                        <td style="text-align: center;">
                            <span style="font-size: 14px;">
                                @php
                                $sale_discount = $sale_request->sale_disc;
                                if($sale_request->sale_discount_overwrite != null){
                                $sale_discount = $sale_request->sale_discount_overwrite;
                                }else{
                                if($sale_discount == null){
                                $sale_discount = 0;
                                }else{
                                $sale_discount;
                                }
                                }
                                @endphp
                                <span style="font-size: 14px;">{{ $sale_discount }} (%)</span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-left" colspan="5" rowspan="2"><b style="font-size: 14px;">Remarks : </b><span style="font-size: 16px;"> {{ $sale_request->remarks }} </span></td>
                        <td colspan="2" style="padding: 5px; background-color: #d5d5d5;"><b style="font-size: 14px; ">Discount
                                Amount (Tk.)</b></td>
                        <td style="text-align: center; background-color: #d5d5d5;">
                            <span style="font-size: 14px;">
                                @php
                                $discount_amount = ($in_total * $sale_discount) / 100;
                                @endphp
                                <span style="font-size: 14px;">{{ $discount_amount }}</span>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding: 5px;"><b style="font-size: 14px;">Grand Total(Tk.)</b>
                        </td>
                        <td style="text-align: center;"><span style="font-size: 14px;">{{ $sale_request->amount }}</span></td>
                    </tr>
                </tfoot>
            </table>
            <br>
            <div class="col-md-12" style="position: fixed; bottom:125px; left:0px; right:0px; ">
                <div style="float: left;">
                    <br><br><br>
                    <span style="font-size: 16px; float: left;margin-top: 10px;border-top: 2px solid #000;"><b>Approved
                            With Seal</b></span>
                </div>
                <div style="float: right;">
                    <br><br><br>
                    <span style="font-size: 16px; float: left;margin-top: 10px;border-top: 2px solid #000;"><b>For :
                            {{$sale_request->company->name}}</b></span>
                </div>
            </div>
        </div>
    </div>
    <!-- First Print Copy End -->
</body>


<!-- Second Print Copy Start -->

<body style="width: 90%;margin: auto;">
    <div class="card">
        <div class="card-header">
            <div class="col-md-12" style="text-align: center;margin-top: 30px;">
                <p style="font-size:22px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:38%;">
                    <span style="font-size:30px;">{{$title}}</span>
                </p><br>
                {{-- <span style="font-size: 30px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
                <span style="font-size: 20px;">{{$company_info->address}}</span><br><br> --}}
            </div>
            <hr>
            <!-- PartyCode & RequisitionNo Start -->
            <div class="col-md-12">
                <span style="font-size: 16px; text-align: left;float: left; width: 63%;">PartyCode
                    <span style="margin-left:25px; font-weight: bolder;">:</span> {{$data->customer_id}}</span>
                <span style="font-size: 16px; text-align: left;float: left;">RequisitionNo :
                    {{$sale_request->req_id}}
                </span>
                <br>
            </div>
            <!-- PartyCode & RequisitionNo End -->

            <!-- PartyName Start -->
            <div class="col-md-6" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 100%">PartyName
                    <span style="margin-left:20px; font-weight: bolder;">:</span> {{ $data->customer_name }}</span>
                <br>
            </div>
            <!-- PartyName  End -->

            <!-- PartyName Start -->
            <div class="col-md-6" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 100%">Party Address
                    <span style="margin-left:2px; font-weight: bolder;">:</span> {{$sale_request->pname}} </span>
                <br>
            </div>
            <!-- PartyName  End -->

            <!-- seller name Start -->
            <div class="col-md-6" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 100%">S/E Name
                    <span style="margin-left:25px; font-weight: bolder;">:</span> {{$sale_request->seller->name}} </span>
                <br>
            </div>
            <!-- seller name  End -->

            <!-- Req. Date Start and phone No start -->
            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 63%">Req. Date
                    <span style="margin-left:27px; font-weight: bolder;">:</span>
                    {{ Carbon\Carbon::parse($sale_request->v_date)->format('d-m-Y') }}
                </span>
                <span style="text-align: right;float: left;font-size: 16px;">Phone No
                    <span style="margin-left:30px; font-weight: bolder;">:</span> {{ $sale_request->phn_no }} </span><br>
            </div>
            <!-- Req. Date Start and phone No end -->


            <!-- P/O Code & Seller Name Start -->
            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 63%">P/O Code
                    <span style="margin-left:30px; font-weight: bolder;">:</span>
                    @if($sale_request->po_code)
                    {{$sale_request->po_code}}
                    @else
                    N/A
                    @endif
                </span>
                <span style="text-align: right;float: left;font-size: 16px;">Approval
                    <span style="margin-left:31px; font-weight: bolder;">:</span>
                    @if ($sale_request->is_approved == 0)
                    <span style="font-size: 16px;">Not Approved</span>
                    @else
                    <span style="font-size: 16px;">Approved</span>
                    @endif
                </span><br>
            </div>
            <!-- P/O Code & Seller Name Start -->

            <!--  Due Amount Start -->
            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 16px; width: 63%">Due Amount
                    <span style="margin-left:10px; font-weight: bolder;">:</span> {{$sale_request->due_amount}}</span>

                <span style="text-align: left;float: left;font-size: 16px; width: 27%">Voucher No
                    <span style="margin-left:10px; font-weight: bolder;">:</span> {{$sale_request->voucher_no}}</span>

            </div>
            <!-- Due Amount End -->

        </div>
        <br><br><br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <table id="print_code">
                <thead>
                    <tr>
                        <th style="width: 20%; font-size: 15px; background-color: #d5d5d5;">SL.No</th>
                        <th style="width: 35%; font-size: 15px; background-color: #d5d5d5;">Products Code</th>
                        <th style="font-size: 15px; background-color: #d5d5d5;">Product Name</th>
                        <th style="width: 25%; font-size: 15px; background-color: #d5d5d5;">Head</th>
                        <th style="width: 25%; font-size: 15px; background-color: #d5d5d5;">Qnty</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $sum_total = 0 ?>
                    @php
                    // Declear Some Varibale
                    $sum_total = 0;
                    $in_total = 0;
                    $total_product_quantity = 0;
                    @endphp
                    @foreach($req_products as $product)
                    <tr>
                        <td style="text-align: center; font-size: 15px; width: 20%;">{{$loop->index + 1}}</td>
                        <td style="text-align: center; font-size: 15px; width: 30%">{{$product->products->product_id}}</td>
                        <td style="text-align: center; font-size: 15px;">{{$product->products->product_name}}</td>
                        <td style="text-align: center; font-size: 15px; width: 25%">
                            @if ($product->products->head_code)
                            {{ $product->products->head_code }}
                            @else
                            -
                            @endif
                        </td>
                        <td style="text-align: center; font-size: 15px; width: 25%">{{$product->qnty}}</td>
                        @php
                        $in_total += $prod_new_price;
                        $total_product_quantity += $product->qnty;
                        @endphp
                    </tr>
                    @endforeach

                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-left" colspan="5" rowspan="2"><b style="font-size: 14px;">Transport Name :
                            </b><span style="font-size: 16px;"> {{ $sale_request->transp_name }} </span></td>
                    </tr>
                    <tr>

                    </tr>
                    <tr>
                        <td class="text-left" colspan="5" rowspan="2"><b style="font-size: 14px;">Remarks : </b><span style="font-size: 16px;"> {{ $sale_request->remarks }} </span></td>
                    </tr>
                    <tr>

                    </tr>
                </tfoot>
            </table>
            <br>
            <div class="col-md-12" style="position: fixed; bottom:125px; left:0px; right:0px; ">
                <div style="float: left;">
                    <br><br><br>
                    <span style="font-size: 16px; float: left;margin-top: 10px;border-top: 2px solid #000;"><b>Approved
                            With Seal</b></span>
                </div>
                <div style="float: right;">
                    <br><br><br>
                    <span style="font-size: 16px; float: left;margin-top: 10px;border-top: 2px solid #000;"><b>For :
                            {{$sale_request->company->name}}</b></span>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- Second Print Copy End -->

</html>