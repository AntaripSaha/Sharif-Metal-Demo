<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    <title>Print Party Wise Report</title>
</head>

<body style="width: 100%;margin: auto;">
    <div class="card">
        <div class="card-header">
            <div class="col-md-6" style="text-align: center;margin-top: 5px;">
                <p
                    style="font-size:22px;padding: 2px 8px 2px 8px;border:4px double #000;display: inline-block;margin-left:43%;">
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
                    <table id="ledgersTable" class="table table-bordered table-striped" width="100%">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle;text-align: center;">Id No</th>
                                <th rowspan="2" style="vertical-align: middle;text-align: center;">Party</th>
                                @foreach($companies as $company)
                                <th colspan="3" style="text-align: center;">{{$company->name}}</th>
                                <th rowspan="2" style="vertical-align: middle;text-align: center;">OB</th>
                                @endforeach
                                <th colspan="3" style="text-align: center;">Total</th>
                                <th rowspan="2" style="vertical-align: middle;text-align: center;">D/I</th>
                                <th rowspan="2" style="vertical-align: middle;text-align: center;">OB</th>
                            </tr>
                            <tr>
                                @foreach($companies as $company)
                                <th>Sells</th>
                                <th>Collection</th>
                                <th>Due</th>
                                @endforeach
                                <th>Sells</th>
                                <th>Collection</th>
                                <th>Due</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <?php $ts = 0;?>
                            <?php $tc = 0;?>
                            <?php $td = 0;?>
                            <?php $s = 0;?>
                            <?php $d = 0;?>
                            <?php $c = 0;?>
                            <tr>
                                <td style="text-align: center;">{{$customer->customer_id}}</td>
                                <td style="text-align: center;">{{$customer->customer_name}}</td>
                                @foreach($companies as $company)
                                <td style="text-align: center;">
                                    @foreach($customer->sales_details as $sale)
                                    @if($company->id == $sale['company_id'])
                                    {{$sale['amount']}}
                                    <?php $s = $sale['amount'];?>
                                    <?php $ts = $s + $ts;?>
                                    @endif
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    @foreach($customer->customer_receive as $receive)
                                    @if($company->id == $receive['company_id'])
                                    {{$receive['amount']}}
                                    <?php $c = $receive['amount'];?>
                                    <?php $tc = $c + $tc;?>
                                    @endif
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    @foreach($customer->sales_details as $sale)
                                    @if($company->id == $sale['company_id'])
                                    {{$sale['del_discount']}}
                                    <?php $d = $sale['del_discount'];?>
                                    <?php $td = $d + $td;?>
                                    @endif
                                    @endforeach
                                </td>
                                <td style="text-align: center;">
                                    @foreach($customer->customer_receive as $receive)
                                    @if($company->id == $receive['company_id'])
                                    <?php echo($s-$c);?>
                                    @endif
                                    @endforeach
                                </td>
                                @endforeach
                                <td style="text-align: center;"><?php echo($ts)?></td>
                                <td style="text-align: center;"><?php echo($tc)?></td>
                                <td style="text-align: center;"><?php echo($td)?></td>
                                <td style="text-align: center;"><?php echo($ts - $tc);?></td>
                                <td style="text-align: center;"><?php echo($ts - $tc);?></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
