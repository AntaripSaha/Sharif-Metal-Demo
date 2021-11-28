{!!Form::open(['route'=>'users.add','id'=>'user-create-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('user.add_user')</h5>
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
            <input type="text" name="user_id" id="user_id" class="form-control m-input m-input--solid" required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('user.name')
            </label>
            <input type="text" name="name" id="name" class="form-control m-input m-input--solid" required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('user.email')
            </label>
            <input type="email" name="email" id="email" class="form-control m-input m-input--solid">
        </div>
        <div class="col-lg-4">
            <label>
                @lang('user.phone_no')
            </label>
            <input type="text" name="phone_no" id="phone_no" class="form-control m-input m-input--solid">
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.address')
            </label>
            <input type="text" class="form-control" name="address">
        </div>

        <div class="col-lg-4">
            <label>
                @lang('user.role')
            </label>
            <select class="form-control" name="role_id" id="role_Select">
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
                <option value="1">Active</option>
                <option value="2">Inactive</option>
            </select>
        </div>

        {{-- Select Parent User Start --}}
        <div class="col-lg-6">
            <label>Select Parent User</label>
            <select class="form-control" name="parent_id" id="parent_Select">
                <option value="null" selected disabled>-- Select Parent User Name --</option>
                @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->name}}</option>
                @endforeach
            </select>
        </div>
        {{-- Select Parent User End --}}

        <div class="col-lg-6">
            <label>
                @lang('user.set_password')
            </label>
            <input type="password" name="password" class="form-control">
        </div>

        <!-- Is Manager Seller Start -->
        <div class="col-lg-6" id="is_manager_seller">
            <label>Is Manager Seller</label>
            <select class="form-control" name="is_manager_seller">
                <option selected disabled>Select Here</option>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <!-- Is Manager Seller End -->
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="AddNewUser()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    $('#is_manager_seller').hide();
$("#role_Select").select2()
    .on("select2:select", function(e) {
        var sel_element = $(e.currentTarget);
        var role_val = sel_element.val();
        $('#role_Select').val(role_val);

        if(role_val == 10){
            $('#is_manager_seller').show();
        }else{
            $('#is_manager_seller').hide();
        }
    });

    // Parent ID Select2 Start
    $("#parent_Select").select2().on("select2:select", function(e){
        var select_parent_user = $(e.currentTarget);
        var parent_value = select_parent_user.val();
        $("#parent_Select").val(parent_value);
    });
    // Parent ID Select2 End


    function AddNewUser() {
        var form = $('#user-create-form');
        var successcallback = function (a) {
            toastr.success("@lang('user.user_has_been_saved')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }

</script>
