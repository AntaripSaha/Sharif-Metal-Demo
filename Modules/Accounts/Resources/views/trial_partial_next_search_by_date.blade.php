@foreach($childs as $child)
    <tr class="text-center">
        @if($child->IsGL == 1)
            <?php
            $brought_forward_data = Modules\Accounts\Entities\Accounts::get_trial_balance_brought_forward_by_date($child->HeadCode, $from, $to);
            $this_period_data = Modules\Accounts\Entities\Accounts::get_trial_balance_this_period_by_date($child->HeadCode, $from, $to);
            $data = Modules\Accounts\Entities\Accounts::get_trial_balance($child->HeadCode);

            $totalDebit = 0;
            $totalCredit = 0;
            ?>
            <td >{{$child->HeadCode}}</td>
            <td >{{$child->HeadName}}</td>
            {{-- Broutht Forward Debit Credit --}}
            <td>{{$brought_forward_data[0]->Debit ?? '0.00'}}</td>
            <td>{{$brought_forward_data[0]->Credit ?? '0.00'}}</td>

            {{-- This Period Debit Credit --}}
            <td>{{$this_period_data[0]->Debit ?? '0.00'}}</td>
            <td>{{$this_period_data[0]->Credit ?? '0.00'}}</td>

            {{-- Balance Debit Credit --}}
            <td>{{ ($brought_forward_data[0]->Debit + $this_period_data[0]->Debit) ?? '0.00'}}</td>
            <td>{{ ($brought_forward_data[0]->Credit + $this_period_data[0]->Credit) ?? '0.00'}}</td>
        @endif
    </tr>
    @if(count($child->childrenRecursive))
        @include('accounts::trial_partial_next_search_by_date ',['childs' => $child->childrenRecursive, 'from' => $from, 'to'=>$to])
    @endif
@endforeach


