<!DOCTYPE html>
<html>
<head>
    {!! isset($pdf_style) ? $pdf_style : '' !!}
</head>
<body style="width: 90%;margin: auto;" >
    <div class="card">
        <div class="card-header">
            <div class="col-md-6" style="text-align: center;">
                <span style="font-size: 26px;font-weight: bold">{{strtoupper($company_info->name)}}</span><br>
                <span>{{$company_info->address}}</span><br>
                <span style="font-size: 18px;text-align: center; margin-top: 10px;">{{$title}}</span><br>
            </div><br>
            <div style="float: left">
                <span>Voucher No : </span>
                <span style="margin-left: 5px;"><b>{{$v_details[0]->VNo}}</b></span>
            </div>
            <div style="float: right">
                <span>Date : </span>
                <span style="margin-left: 5px;">{{$v_details[0]->VDate}}</span>
            </div>
            <br>
        </div>
        <br><br><br>
         <!-- /.card-header -->
        <div class="card-body" id="ledger_view">
            <table id="print_code">
                <thead>
                    <tr>
                        <th colspan="2">Account Head</th>
                        <th>Debit(In BDT)</th>
                        <th>Credit(In BDT)</th>
                    </tr>
                    </thead>
                <tbody>
                </tbody>
                    @foreach($vouchers as $voucher)
                    <tr>
                        <td colspan="2" style="padding: 5px;">
                            {{  $voucher->coa->HeadName }}
                        </td>
                        <td style="text-align: center;">{{$voucher->Debit}}</td>
                        <td style="text-align: center;">{{$voucher->Credit}}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td class="text-center" colspan="2" style="padding: 5px;"><b>Total</b><span> </span></td>
                        <td style="text-align: center;"><b>{{$v_details[0]->Debit}}</b></td>
                        <td style="text-align: center;"><b>{{$v_details[0]->Credit}}</b></td>
                    </tr>
                    <tr>
                        <td class="text-center" colspan="4" style="padding: 5px;"><b>Amount In Word (In BDT) :</b>{{ $amount }} Only<span> </span></td>
                    </tr>
                </tfoot>                
            </table>
            <p><b>Narration :</b> {{$narration->Narration}}</p>
            <div style="margin-top: 50%">
                <div style="float: left;width: 25%;text-align: center;">
                    <br><br>
                    <span>.................................</span><br>
                    <span style="margin-top: 5px;font-size: 13px;">Prepared by</span>  
                </div>
                <div style="float: left;width: 25%;text-align: center;">
                    <br><br>
                    <span>.................................</span><br>
                    <span style="margin-top: 5px;font-size: 13px;">Checked by</span>  
                </div>
                <div style="float: left;width: 25%;text-align: center;">
                    <br><br>
                    <span>.................................</span><br>
                    <span style="margin-top: 5px;font-size: 13px;">Recommended by</span>  
                </div>
                <div style="float: left; width: 25%;text-align: center;" >
                    <br><br>
                    <span>.................................</span><br>
                    <span style="margin-top: 5px;font-size: 13px;">Approved by</span>  
                </div>
            </div>
        </div>
    </div>
</body>
</html>