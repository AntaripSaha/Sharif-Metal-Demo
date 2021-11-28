@foreach($childs as $key => $child)

<tr class="table-active">
    @if($child->IsGL == 1)
    <td colspan="8" class="text-bold"><span class="ml-4">{{$child->HeadCode}} - {{$child->HeadName}}</span></td>
    @endif
</tr>
@if(count($child->childrenRecursive))
@include('accounts::trial_partial_next ',['childs' => $child->childrenRecursive])
@endif
@if($child->PHeadCode == $head && $child->IsGL == 1)
<tr>
    <?php
		$brought_forward_data = Modules\Accounts\Entities\Accounts::get_trial_balance_brought_forward($child->HeadCode);
		$this_period_data = Modules\Accounts\Entities\Accounts::get_trial_balance_this_period($child->HeadCode);
		$data = Modules\Accounts\Entities\Accounts::get_trial_balance($child->HeadCode);

		$totalDebit = 0;
		$totalCredit = 0;

		$grand_total_brought_forward_debit = 0;
		$grand_total_brought_forward_credit = 0;

		$grand_total_this_period_debit = 0;
		$grand_total_this_period_credit = 0;

		$grand_total_balance_debit = 0;
		$grand_total_balance_credit = 0;

	?>
    <td colspan="2" class="text-bold">Total - {{$child->HeadName}}</td>

    {{-- Brought Forward Balance --}}
    <td class="text-center text-bold">{{$brought_forward_data[0]->Debit ?? '0.00'}}

    </td>
    <td class="text-center text-bold">{{$brought_forward_data[0]->Credit ?? '0.00'}}

    </td>
    {{-- This Period Total Balance --}}
    <td class="text-center text-bold">{{$this_period_data[0]->Debit ?? '0.00'}}

    </td>
    <td class="text-center text-bold">{{$this_period_data[0]->Credit ?? '0.00'}}

    </td>
    {{-- Balance Total --}}
    <td class="text-center text-bold">
        {{$totalDebit += ($brought_forward_data[0]->Debit + $this_period_data[0]->Debit) ?? '0.00'}}

    </td>
    <td class="text-center text-bold">
        {{$totalCredit += ($brought_forward_data[0]->Credit + $this_period_data[0]->Credit) ?? '0.00'}}

    </td>
</tr>

@endif
@endforeach



