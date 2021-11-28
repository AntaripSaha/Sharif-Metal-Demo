<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    
 
</head>

<body style="width: 90%;margin: auto;">
    <div class="card">
        <div class="card-header">
            <div class="col-md-6" style="text-align: center;margin-top: 20px;">
                <span
                        style="font-size:22px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:45%;">
                    <span style="font-size:28px;">{{$title}}</span>
                </span>
                <br>
                <span style="font-size: 28px;font-weight: bold">{{$company_info->name}}</span><br>
                <span style="font-size: 20px;">{{$company_info->address}}</span><br>
                <span style="font-size: 20px;">BIN-{{$company_info->company_no}}</span><br>
            </div><br><br>
            <div class="col-md-12" style="width: 100%; display: block;margin-bottom: -10px">
                <span style="font-size: 18px; text-align: left;float: left;">Challan No : <span
                        style="font-size: 18px;">{{$chalan->challan_no}}</span></span>

                <span style="font-size: 18px; text-align: right;float: right;">Date : <span
                        style="font-size: 18px;">{{ Carbon\Carbon::parse($chalan->del_date)->format('d-m-Y') }}</span></span><br>
            </div><br>

            <div class="col-md-12" style="width: 100%;display: block;margin-bottom: -10px">
                <span style="font-size:18px;text-align: left;float: left;">Name : <span
                        style="font-size: 18px;">{{$chalan->customer->customer_name}}</span></span>
                <span style="font-size:18px;text-align: right;float: right;">P.O No : <span
                        style="font-size: 18px;">{{$chalan->po_code}}</span>
                </span>
                <br>
            </div><br>
            
            

            <div class="col-md-12" style="width: 100%;display: block;margin-bottom: -10px">
                <span style="font-size:18px;text-align: left;float: left;">Address : <span
                        style="font-size: 18px;">{{$chalan->customer->customer_address}}</span></span>
                <span style="font-size:18px;text-align: right;float: right;">D/C O Code : <span
                        style="font-size: 18px;">{{$chalan->dco_code}}</span></span><br>
            </div><br>

            <div class="col-md-12" style="width: 100%;display: block;margin-bottom: -10px">
                <span style="font-size:18px;text-align: left;float: left;">Project Name & Address : <span
                        style="font-size: 18px;">{{$chalan->pname}}</span></span><br>
            </div><br><br>
            
            <div class="col-md-12" style="width: 100%;display: block;margin-bottom: -10px">
                <span style="font-size:18px;text-align: left;float: left;">Sales Person Name : <span
                        style="font-size: 18px;">{{$sales_person_name->name}} - {{$sales_person_name->user_id}}</span></span><br>
            </div><br>

            <div class="col-md-12" style="width: 100%;display: block;margin-bottom: -10px">
                <span style="font-size:18px;text-align: left;float: left;">Receiver Name : <span
                        style="font-size: 18px;">{{$chalan->receiver}}</span></span>
                <span style="font-size: 18px; text-align: left;float: right;">Mobile No : <span
                        style="font-size: 18px;">{{$chalan->phn_no}}</span></span>
                        
                        
            </div>
            <br><br>
            
            @if( $chalan->gift )
            <div class="col-md-12" style="width: 100%;display: block;margin-bottom: -10px">
                <span style="font-size:18px;text-align: left;float: left;">Remarks : <span
                        style="font-size: 18px;">{{$chalan->gift}}</span></span>
            </div>
            <br><br>
            @endif
            
            
        </div>
        <br><br><br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <table id="print_code" style="width: 630px; margin-top: -50px ">
                <thead>
                    <tr>
                        <th style="font-size:18px; width: 10px;padding:2px;">SL.No</th>
                        <th style="font-size:18px; width: 100px;padding:2px;">Product Code</th>
                        <th style="font-size:18px; width: 300px !important;padding:2px;">Product Name</th>
                        <th style="font-size:18px;padding:10px; width: 5px;">Head</th>
                        <th style="font-size:18px;padding:10px; width: 2px;">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td style="padding: 8px; text-align: left; width: 10px;">{{$loop->index + 1}}</td>
                        <td style="padding: 8px; text-align: left; width: 100px">{{$product->products->product_id}}</td>
                        <td style="padding: 8px; text-align: left; width: 300px !important;">{{$product->products->product_name}}</td>
                        <td style="padding: 8px; text-align: left;width: 5px">
                            @if ($product->products->head_code)
                                {{ $product->products->head_code }}
                            @else
                                -
                            @endif
                        </td>
                        <td style="padding: 8px; text-align: left; width: 5px;">
                            {{$product->del_qnt}} </td>
                    </tr>
                    @endforeach
                    <?php $pcount = $products->count();?>
                    @if($pcount<10) 
                        @for ($i=$pcount; $i < 10; $i++) <tr>
                        <td style="padding:8px;text-align: left; width: 10px;">{{$i+1}}</td>
                        <td style="padding:8px;text-align: left; width: 10px"></td>
                        <td style="padding:8px;text-align: left; width: 200px !important;"></td>
                        <td style="padding:8px;text-align: left; width: 5px"></td>
                        <td style="padding:8px;text-align: left; width: 2px;"></td>
                        </tr>
                        @endfor
                    @endif
                </tbody>
            </table>
            <br>
            <div class="col-md-12">
                <p style="text-align: left;">
                    <span style="font-size: 18px;display: block">Transport Name : <span
                            style="font-size: 18px;">{{$chalan->transp_name}}</span></span>
                </p>
                <span style="font-size:18px;float: left;">Delivery Person Name : {{$chalan->deliv_pname}}</span>
                <span style="font-size:18px;float: right;text-align: left;">Signature :
                    .................................. </span>
            </div>
            <div class="col-md-12" style="margin-top: 20px">
                <br><br><br><br><br>
                <span style="font-size: 18px;text-align: left;border-top: 2px solid #000;float: left;">
                    Received With Seal
                </span>
                <span style="font-size: 18px;text-align: right;float: right; border-top: 2px solid #000">
                    For: {{$company_info->name}}
                </span>
            </div>
        </div>
    </div>
</body>

</html>
