@foreach($childs as $child)
<tr class="text-center">
	@if($child->IsGL == 1)
	<?php
		$data = Modules\Accounts\Entities\Accounts::get_trial_balance($child->HeadCode);
	?>
    <td >{{$child->HeadCode}}</td>
    <td >{{$child->HeadName}}</td>
    <td>ABC </td>
    <td>DEF </td>

    <td>Laravel</td>
    <td>Vue JS</td>
  	@endif
</tr> 
	@if(count($child->childrenRecursive))
    	@include('accounts::TrialBalance.this_period_next ',['childs' => $child->childrenRecursive])
    @endif
@endforeach

