@foreach($childs as $child)
<tr class="text-center">
	@if($child->IsGL == 1)
	<?php
		$data = Modules\Accounts\Entities\Accounts::get_trial_balance_by_date($fromDate, $toDate, $child->HeadCode);
	?>
    <td>{{$child->HeadCode}}</td>
    <td>{{$child->HeadName}}</td>
    <td>{{$data[0]->Debit ?? '0.00'}}</td>
    <td>{{$data[0]->Credit ?? '0.00'}}</td>
  	@endif
</tr>
	@if(count($child->childrenRecursive))
    	@include('accounts::trial_partial_next_by_date',['childs' => $child->childrenRecursive])
    @endif
@endforeach

