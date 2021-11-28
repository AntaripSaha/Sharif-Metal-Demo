<table id="ledgersTable" class="table table-bordered table-striped" width="100%">
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">Id No</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">SP</th>
            <th colspan="3" style="text-align: center;">{{$company_info->name}}</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">OB</th>
            <th colspan="3" style="text-align: center;">Total</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">D/I</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">OB</th>
        </tr>
        <tr>
            <th>Sells</th>
            <th>Collection</th>
            <th>Adjustment</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sellers as $seller)
        <?php $ts = 0;?>
        <?php $tc = 0;?>
        <?php $s = 0;?>
        <?php $c = 0;?>
        <tr>
            <td>{{$seller->user_id}}</td>
            <td>{{$seller->name}}</td>
            <td>
                @forelse($seller->sales_details as $sale)
                {{$sale['amount']}}
                <?php $s = $sale['amount'];?>
                <?php $ts = $s + $ts;?>
                @empty
                <p> - </p>
                @endforelse
            </td>
            <td>
                @forelse($seller->customer_receive as $receive)
                {{$receive['amount']}}
                <?php $c = $receive['amount'];?>
                <?php $tc = $c + $tc;?>
                @empty
                <p> - </p>
                @endforelse
            </td>
            <td></td>
            <td>
                @forelse($seller->customer_receive as $receive)
                <?php echo($s-$c);?>
                @empty
                <p> - </p>
                @endforelse
            </td>
            <td>
                <?php echo($ts)?>
            </td>
            <td>
                <?php echo($tc)?>
            </td>
            <td></td>
            <td>
                <?php echo($ts - $tc);?>
            </td>
            <td>
                <?php echo($ts - $tc);?>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
