<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    <title>Print Product Reports</title>
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
            <div class="col-md-6" style="text-align: center;margin-top: 50px;">
                <p
                    style="font-size:22px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:38%;">
                    <span style="font-size:22px;"><b>{{$title}}</b></span></p><br>
                <span style="font-size: 30px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
                <span style="font-size: 20px;">{{$company_info->address}}</span><br><br>
            </div>
            <hr>
            {{-- <div class="col-md-12">
                <span style="font-size: 18px; text-align: left;float: left; width: 40%">Date
                    <span style="margin-left: 42%;">:</span> All</span>
                <br>
            </div>

            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 18px; width: 40%">Warehouse Name <span
                        style="margin-left: 5%;">:</span>
                    <span style="font-size: 18px;">All</span>
                </span>
                <span style="text-align: right;float: right;font-size: 18px;"></span><br>
            </div>

            <div class="col-md-12" style="margin-top: 8px">
                <span style="text-align: left;float: left;font-size: 18px; width: 40%">Product Name
                    <span style="margin-left: 15%;">:</span> All</span>
            </div> --}}
        </div>
        <br><br><br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <div class="col-12">
                <div class="card-body table-responsive" id="stockReportDetails">
                    <table id="stockReportDetailsTable" class="table table-sm table-bordered  nowrap" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th>Sl No</th>
                                <th>Product Name</th>
                                <th style="text-align: center;">Product Code</th>
                                <th style="text-align: center;">Company Name</th>
                                <th style="text-align: center;">Production Price</th>
                                <th style="text-align: center;">Sale Price</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @foreach ($products as $product)
                            <tr>
                                <td style="text-align: center;">{{ $i }}</td>
                                <td>{{ $product->product_name }}</td>
                                <td style="text-align: center;">{{ $product->product_id }}</td>
                                <td style="text-align: center;">
                                    @if ($product->company_id == null)
                                    <span>-</span>
                                    @else
                                    <span>{{ $product->company[0]->name }}</span>
                                    @endif
                                </td>
                                <td style="text-align: center;">{{ $product->production_price }}</td>
                                <td style="text-align: center;">{{ $product->price }}</td>
                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach

                        </tbody>
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
