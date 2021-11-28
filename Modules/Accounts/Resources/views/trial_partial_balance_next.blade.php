@foreach($childs as $child)
<tr class="text-center">
	@if($child->IsGL == 1)
	<?php
		$data = Modules\Accounts\Entities\Accounts::get_trial_balance($child->HeadCode);
	?>
    <td >{{$child->HeadCode}}</td>
    <td >{{$child->HeadName}}</td>
    <td>{{$data[0]->Debit ?? '0.00'}}</td>
    <td>{{$data[0]->Credit ?? '0.00'}}</td>
  	@endif
</tr>
	@if(count($child->childrenRecursive))
    	@include('accounts::trial_partial_balance_next ',['childs' => $child->childrenRecursive])
    @endif
@endforeach

