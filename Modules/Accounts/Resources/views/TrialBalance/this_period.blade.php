@foreach($childs as $child)

<tr class="table-active">
	@if($child->IsGL == 1)
    <td colspan="8" class="text-bold"><span class="ml-4">{{$child->HeadCode}} - {{$child->HeadName}}</span></td>
  	@endif
</tr>
	@if(count($child->childrenRecursive))
    	@include('accounts::TrialBalance.this_period_next ',['childs' => $child->childrenRecursive])
    @endif
    @if($child->PHeadCode == $head && $child->IsGL == 1)
<tr>
	<?php
		$data = Modules\Accounts\Entities\Accounts::get_trial_balance($child->HeadCode);
	?>
	<td colspan="2" class="text-bold">Total - {{$child->HeadName}}</td>
	<td class="text-center">{{$data[0]->Debit}}</td>
	<td class="text-center">{{$data[0]->Credit}}</td>
</tr>
@endif
@endforeach
