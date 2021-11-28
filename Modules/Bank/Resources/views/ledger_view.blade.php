<table id="ledgersTable" class="table table-bordered table-striped display responsive nowrap" width="100%">
    <thead>
        <tr>
            <th>@lang('layout.date')</th>
            <th>@lang('bank.bank_name')</th>
            <th>@lang('layout.description')</th>
            <th>@lang('bank.wd_id')</th>
            <th>@lang('bank.debit')</th>
            <th>@lang('bank.credit')</th>
            <th>@lang('bank.balance')</th>
        </tr>
    </thead>
    <tbody>
        <?php $sum_debit = 0 ?>
        <?php $sum_credit = 0 ?>
        <?php $balance = 0 ?>
        @forelse($transactions as $transaction)
        <tr>
            <td>{{$transaction->VDate}}</td>
            <td>{{$bank->bank_name}}</td>
            <td>{{$transaction->Narration}}</td>
            <td>{{$transaction->VNo}}</td>
            <td><span style="font-family: initial;">৳ </span>{{$transaction->Debit}}</td>
            <td><span style="font-family: initial;">৳ </span>{{$transaction->Credit}}</td>
            <td>
                @if($transaction->Credit > 0)
                <?php $sum_credit += $transaction->Credit ?>
                @else
                <?php $sum_debit += $transaction->Debit ?>
                @endif
                <?php $balance = $sum_debit-$sum_credit ?>
                <?php echo($balance) ?>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="text-center">No Data Found</td>
        </tr>
        @endforelse
        <tr>
            <td colspan="4" class="text-right">Grand total :</td>
            <td><span style="font-family: initial;">৳ </span><?php echo($sum_debit) ?></td>
            <td><span style="font-family: initial;">৳ </span><?php echo($sum_credit) ?></td>
            <td><span style="font-family: initial;">৳ </span><?php echo($balance) ?></td>
        </tr>
    </tbody>
</table>
