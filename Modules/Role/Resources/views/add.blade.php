{!!Form::open(['route'=>'roles.add','id'=>'roles-add-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('roles.add_role')</h5>
    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">@lang('roles.role')</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" required id="name" name="name" placeholder="Role Name">
        </div>
    </div>
    <div class="form-group row">
        <label for="display_name" class="col-sm-2 col-form-label">@lang('roles.display_role')</label>
        <div class="col-sm-10">
            <input type="text" class="form-control" required id="display_name" name="display_name" placeholder="Role Name">
        </div>
    </div>
</div>
<div class="modal-footer role_submit">
    <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" id="add_role" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script src="{{asset('js/Modules/Role/create.js')}}"></script>