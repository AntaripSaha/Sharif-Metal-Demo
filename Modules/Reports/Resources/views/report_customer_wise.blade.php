<table id="ledgersTable" class="table table-bordered table-striped nowrap" width="100%">
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">Id No</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">Party</th>
            @foreach($companies as $company)
            <th colspan="3" style="text-align: center;">{{$company->name}}</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">OS</th>
            @endforeach
            <th colspan="3" style="text-align: center;">Total</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">D/I</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">OS</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">Action</th>
        </tr>
        <tr>
            @foreach($companies as $company)
            <th>Sale</th>
            <th>Collection</th>
            <th>Discount</th>
            @endforeach
            <th>Sale</th>
            <th>Collection</th>
            <th>Discount</th>
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
            <td>{{$customer->customer_id}}</td>
            <td>{{$customer->customer_name}}</td>
            @foreach($companies as $company)
            <td>
                @foreach($customer->sales_details as $sale)
                @if($company->id == $sale['company_id'])
                {{$sale['amount']}}
                <?php $s = $sale['amount'];?>
                <?php $ts = $s + $ts;?>
                @endif
                @endforeach
            </td>
            <td>
                @foreach($customer->customer_receive as $receive)
                @if($company->id == $receive['company_id'])
                {{$receive['amount']}}
                <?php $c = $receive['amount'];?>
                <?php $tc = $c + $tc;?>
                @endif
                @endforeach
            </td>
            <td>
                @foreach($customer->sales_details as $sale)
                @if($company->id == $sale['company_id'])
                {{$sale['del_discount']}}
                <?php $d = $sale['del_discount'];?>
                <?php $td = $d + $td;?>
                @endif
                @endforeach
            </td>
            <td>
                @foreach($customer->customer_receive as $receive)
                @if($company->id == $receive['company_id'])
                <?php echo($s-$c);?>
                @endif
                @endforeach
            </td>
            @endforeach
            <td>
                <?php echo($ts)?>
            </td>
            <td>
                <?php echo($tc)?>
            </td>
            <td>
                <?php echo($td)?>
            </td>
            <td>
                <?php echo($ts - $tc);?>
            </td>
            <td>
                <?php echo($ts - $tc);?>
            </td>
            @php
                $c_id;
                if($company_id){
                    $c_id = $company_id;
                }else{
                    $c_id = 0;
                }
                // if($sdate && $edate){
                //     $from = $sdate;
                //     $to = $edate;
                // }else{
                //     $from = null;
                //     $to = null;
                // }
            @endphp
            <td>
                <a href="{{ route('reports.customer_details', ['customer_id' => $customer->id, "company_id" => $c_id, "from" => $sdate, "to" => $edate]) }}">
                <button class="btn btn-info btn-sm">Details</button></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
