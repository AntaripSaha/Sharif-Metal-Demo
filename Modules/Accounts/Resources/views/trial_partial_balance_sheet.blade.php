@foreach($childs as $child)

<tr class="table-active">
	@if($child->IsGL == 1)
    <td colspan="8" class="text-bold"><span class="ml-4">{{$child->HeadCode}} - {{$child->HeadName}}</span></td>
  	@endif
</tr>
	@if(count($child->childrenRecursive))
    	@include('accounts::trial_partial_balance_next ',['childs' => $child->childrenRecursive])
    @endif
    @if($child->PHeadCode == $head && $child->IsGL == 1)
<tr>
	<?php
		$data = Modules\Accounts\Entities\Accounts::get_trial_balance($child->HeadCode);
	?>
	<td colspan="2" class="text-bold">Total - {{$child->HeadName}}</td>
	<td class="text-center">{{$data[0]->Debit ?? '0.00' }}</td>
	<td class="text-center">{{$data[0]->Credit ?? '0.00'}}</td>
</tr>
@endif
@endforeach


