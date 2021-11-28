{!!Form::open(['route'=>array('modules.permission.save',$company_id),'data-toggle'=>'validation','id'=>'modulePermissionForm']) !!}

<input type="hidden" name="company_id" value="{{$company_id}}">
<div class="form-group clearfix">
	@foreach($modules as $key=>$value)
	<div class="icheck-info">
       	<input type="checkbox" id="permission-{{$value->id}}" name="permissions[]" class="the-permission" value="{{$value->id}}" @if(in_array($value->id, $modules_permissions)) checked @endif >
        <label for="permission-{{$value->id}}">{{ $value->display_name }}</label>
    </div>
    @endforeach
</div>
<button type="button" onclick="submitModulePermission()" class="btn btn-primary btn-sm m-btn m-btn--custom">{{__('Update Module Permission')}}</button>
{!! Form::close() !!}
<script type="text/javascript">

    function submitModulePermission(){
        var form=$('#modulePermissionForm');
        var successcallback=function(a){
            toastr.success("{{__('Module Permission Updated Successfully!')}}", "{{__('Success!!!')}}");           
        }        
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
    }

</script>