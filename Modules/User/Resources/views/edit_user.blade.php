{{ Form::open(array('route' => array('users.edit_user', $user->id), 'id'=>'user-update-form')) }}
<div class="modal-header">
    <h5 class="modal-title">@lang('user.user_edit') - {{$user->name}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-2">
            <label>
                @lang('user.user_id')
            </label>
            <input type="text" name="user_id" id="user_id" class="form-control m-input m-input--solid" value="{{$user->user_id}}"
                required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('user.name')
            </label>
            <input type="text" name="name" id="name" class="form-control m-input m-input--solid" value="{{$user->name}}"
                required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('user.email')
            </label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}">
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.phone_no')
            </label>
            <input type="text" name="phone_no" class="form-control" value="{{ $user->phone_no }}">
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.address')
            </label>
            <input type="text" name="address" class="form-control" value="{{ $user->address }}">
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.role')
            </label>
            <select class="form-control" name="role_id">
                <option selected disabled value="{{ $user->role_id }}">{{ $user->role->name }}</option>
                <option value="4"> Seller </option>
                @foreach($user_roles as $role)
                    <option value="{{$role->id}}">{{$role->name}}</option>
                @endforeach
            </select>
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.status')
            </label>
            <select class="form-control" name="status">
                @if($user->status == 1)
                <option selected value="1">Active</option>
                <option value="2">Inactive</option>
                @else
                <option selected value="2">Inactive</option>
                <option value="1">Active</option>
                @endif
            </select>
        </div>

        <!-- Is Manger Seller Start -->
        @if($user->role_id == 10)
        <div class="col-lg-6">
            <label>Is Manager Seller</label>
            <select class="form-control" name="is_manager_seller">
                @if($user->is_manager_seller == 1)
                    <option selected value="1">Yes</option>
                    <option value="0">No</option>
                @else
                    <option selected disabled> -- Select Here --</option>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                @endif
            </select>
        </div>
        @endif
        <!-- Is Manger Seller End -->

        {{-- Parent User Info Start --}}
{{--        <div class="col-lg-6">--}}
{{--            <label>Parent User Name</label>--}}
{{--            <input type="text" name="parent_user_name" id="" value="{{ $user->parent_id }}">--}}
{{--        </div>--}}
        {{-- Parent User Info End --}}

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="UpdateUser()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}
<script type="text/javascript">
    function UpdateUser() {
        var form = $('#user-update-form');
        var successcallback = function (a) {
            toastr.success("@lang('user.user_has_been_updated')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }

</script>
