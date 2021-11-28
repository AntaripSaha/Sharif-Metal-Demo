      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">{{$role->name}} - Permissions</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">{{$role->name}}- Permissions</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
        {!!Form::open(['route'=>array('roles.permission.save',$role->id),'data-toggle'=>'validation','id'=>'rolePermissionForm']) !!}
        <div>
            <div id="permissions" class="tab-pane  active in" role="tabpanel">
                <input type="hidden" name="role_id" value="{{$role->id}}">
                <ul class="permissions checkbox row" style="list-style: none">
                    @foreach($permissions as $key=>$value)
                        <li class="col-4 border p-2">
                                <input type="checkbox" class="permission-group" id="id_{{$key}}">
                                <label for="id_{{$key}}">{{ $value->display_name }}</label>
                            <hr>
                            <ul class="list-child list-inline">
                                @foreach($value->permissions as $k=>$v)
                                    <li>
                                        <input type="checkbox" id="permission-{{$v->id}}" name="permissions[]" class="the-permission" value="{{$v->id}}" @if(in_array($v->id, $role_permission)) checked @endif >
                                        <label for="permission-{{$v->id}}">{{$v->display_name}}</label>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {!! Form::close() !!}
        @if(Auth::user()->can('edit',app('Modules\Role\Entities\Role')))
            <button type="button" onclick="submitRolePermission()" class="btn btn-primary btn-sm m-btn m-btn--custom">{{__('Update Module Permission')}}</button>
        @endif
{!! Form::close() !!}
<script type="text/javascript">

    function submitRolePermission(){
        var form=$('#rolePermissionForm');
        var successcallback=function(a){
            toastr.success("{{__('Role Permission Updated Successfully!')}}", "{{__('Success!!!')}}");
            var url= baseUrl+"roles";
            location.href= url;         
        }        
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
    }

</script>