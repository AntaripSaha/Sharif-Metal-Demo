<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    <title>Undelivered Product Print</title>
    <style>
        .ftr {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;
        }
    </style>
</head>

<body style="width: 100%;margin: auto;">
    <div class="card">
        <div class="card-header">
            <div class="col-md-6" style="text-align: center;margin-top: 0px;">
                <p><u style="font-size: 22px">{{ $title }}</u></p>
                <span style="font-size: 18px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
                <span style="font-size: 15px;">{{$company_info->address}}</span><br>
                <span style="font-size: 15px;">Date Range : All</span>
            </div>
        </div>
        <br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <div class="col-12">
                <div class="card-body table-responsive" id="stockReportDetails">
                    <table id="stockReportDetailsTable" class="table table-sm table-bordered  nowrap" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th style="text-align: center;">Sl No</th>
                                <th style="text-align: center;">Product Code</th>
                                <th style="text-align: center;">Product Name</th>
                                <th style="text-align: center;">Unit Price</th>
                                <th style="text-align: center;">Undelivered Qnty</th>
                                <th style="text-align: center;">Total Amount</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $i = 1;
                            $in_total_amount = 0;
                            $total_product_qnty = 0;
                            @endphp
                            @foreach ($undelivered_products as $undelivered)
                            @if ($undelivered->undelivered_product != 0)
                            <tr class="text-center">
                                <td style="text-align: center" width="8%">{{ $i }}</td>
                                <td style="text-align: center" width="10%">{{ $undelivered->products->product_id }}</td>
                                <td style="text-align: center" width="50%">{{ $undelivered->products->product_name }}</td>
                                <td style="text-align: center" width="10%">{{ $undelivered->products->price }}</td>
                                <td style="text-align: center" width="10%">{{ $undelivered->undelivered_product }}</td>
                                <td style="text-align: center" width="12%">{{ $undelivered->products->price * $undelivered->undelivered_product }}</td>
                            </tr>
                            @php
                            $i++;
                            $in_total_amount += ($undelivered->products->price * $undelivered->undelivered_product);
                            $total_product_qnty += $undelivered->undelivered_product;
                            @endphp
                            @endif
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" style="text-align: right; font-weight:600;"><strong>Total</strong></td>
                                <td style="text-align: center; font-weight:600;">{{ $total_product_qnty }}</td>
                                <td style="text-align: center; font-weight:600;">{{ $in_total_amount }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="ftr">
            <?php
                $date = date('d/m/Y');
                $time = date('h:i:A');
                echo "Print Date & Time : ".$date." , ".$time; 
            ?>
        </div>
    </div>
</body>

</html>
