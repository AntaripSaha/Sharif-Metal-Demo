<table id="ledgersTable" class="table table-bordered table-striped nowrap" width="100%">
    <thead>
        <tr>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">Id No</th>
            <th rowspan="2" style="vertical-align: middle;text-align: center;">SP</th>
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
            <th>S</th>
            <th>C</th>
            <th>A</th>
            @endforeach
            <th>S</th>
            <th>C</th>
            <th>A</th>
        </tr>
    </thead>
    <tbody>
        <?php $ts = 0;?>
        <?php $tc = 0;?>
        <?php $s = 0;?>
        <?php $c = 0;?>
        <tr>
            <td>{{$seller->user_id}}</td>
            <td>{{$seller->name}}</td>
            @foreach($companies as $company)
            <td>
                @forelse($seller->sales_details as $sale)
                @if($company->id == $sale['company_id'])
                {{$sale['amount']}}
                <?php $s = $sale['amount'];?>
                <?php $ts = $s + $ts;?>
                @endif
                @empty
                <p>-</p>
                @endforelse
            </td>
            <td>
                @forelse($seller->customer_receive as $receive)
                @if($company->id == $receive['company_id'])
                {{$receive['amount']}}
                <?php $c = $receive['amount'];?>
                <?php $tc = $c + $tc;?>
                @endif
                @empty
                <p>-</p>
                @endforelse
            </td>
            <td></td>
            <td>
                @forelse($seller->customer_receive as $receive)
                @if($company->id == $receive['company_id'])
                <?php echo($s-$c);?>
                @endif
                @empty
                <p>-</p>
                @endforelse
            </td>
            @endforeach
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

            <td class="text-center">
                <a data-toggle="tooltip" data-placement="top" title="View Details"
                    href="{{ route('reports.seller_details',["id"=>$seller->id, "company_id"=>$company->id]) }}">
                    <i class="fas fa-desktop" style="cursor:pointer;"></i>
                </a>
            </td>
        </tr>
    </tbody>
</table>
