<!DOCTYPE html>
<html>

<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
    <title>Print Stock Movement Report</title>
</head>

<body style="width: 100%;margin: auto;">
    <div class="card">
        <div class="card-header" style="margin-top: -30px;">
            <div class="col-md-6" style="text-align: center;">
                <p
                    style="font-size:22px;padding: 2px 8px 1px 8px;border:4px double #000;display: inline-block;margin-left:36%;">
                    <span style="font-size:22px;"><b>{{$title}}</b></span></p><br>
                <span style="font-size: 20px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
                <span style="font-size: 14px;">{{ $dateRange }}</span>
            </div>
            <hr>
        </div>
        <br><br><br>
        <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <div class="col-12">
                <div class="card-body table-responsive" id="stockReportDetails">
                    <table id="stockReportDetailsTable" class="table table-sm table-bordered  nowrap" width="100%">
                        <thead>
                            <tr class="text-center">
                                <th style="width: 60%">Particulars</th>
                                <th style="width: 6%">Head</th>
                                <th style="width: 12%">Inwards (Qnty)</th>
                                <th style="width: 12%">Outwards (Qnty)</th>
                                <th style="width: 10%">Closing Balance (Qnty)</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php 
                                $totalInwards = 0;
                                $totalOutwards = 0;
                                $total_closing_balance = 0;
                            ?>
                            @foreach ($stockMovementReport as $report)
                                {{-- Calculating Total Amount Start --}}
                                @php
                                 $totalInwards += $report['inwards'];
                                 $totalOutwards += $report['outwards'];
                                 $total_closing_balance += $report['closing_balance'];
                                @endphp
                                {{-- Calculating Total Amount End --}}

                                {{-- Show All Products In Out Start --}}
                                <tr>
                                    <td style="width: 70%"> {{ $report['particulars'] }}</td>
                                    <td style="width:5% !important; text-align: center">
                                        @if ($report['head_code'])
                                            {{ $report['head_code'] }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td style="width: 12%; text-align:right"> {{ $report['inwards'] }} Pcs.</td>
                                    <td style="width: 12%; text-align:right"> {{ $report['outwards'] }} Pcs.</td>
                                    <td style="width: 16%; text-align:right"> {{ $report['closing_balance'] }} Pcs.</td>
                                </tr>
                                {{-- Show All Products In Out End --}}
                            @endforeach
                                {{-- Show Grand Total Calculation Start --}}
                                <tr>
                                    <td style="width: 70%; text-align:right" colspan="2"> <strong>Grand Total</strong></td>
                                    <td style="width: 12%; text-align:right"> {{ $totalInwards }} Pcs.</td>
                                    <td style="width: 12%; text-align:right"> {{ $totalOutwards }} Pcs.</td>
                                    <td style="width: 16%; text-align:right"> {{ $total_closing_balance }} Pcs.</td>
                                </tr>
                                {{-- Show Grand Total Calculation End --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>

</html>
