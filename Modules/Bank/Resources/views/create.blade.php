{!!Form::open(['route'=>'bank.addBank','id'=>'bank-create-form','enctype'=>"multipart/form-data"]) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('account.new account')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
<div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label>
                @lang('account.bank name')
            </label>
            <input type="text" name="bank_name" class="form-control m-input m-input--solid"
                placeholder="@lang('account.bank name')" required>
        </div>
        <div class="col-lg-6">
            <label>
                @lang('account.account name')
            </label>
            <input type="text" name="account_name" class="form-control m-input m-input--solid"
                placeholder="@lang('account.account name')" required>
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-6">
            <label class="">
                @lang('account.account no')
            </label>
            <input type="text" name="account_no" class="form-control m-input m-input--solid"
                placeholder="@lang('account.account no')" required>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('account.branch_name')
            </label>
            <input type="text" name="branch" class="form-control m-input m-input--solid"
                placeholder="@lang('account.branch_name')" required>
        </div>
    </div>

    <div class="form-group m-form__group row">
        <div class="col-lg-4">
            <label class="" for="sign">
                @lang('account.sign')
            </label>
            <input id="file-upload" class="form-control file_upload" name=img[] type="file"/> 
            <div class="image_uploaded mt-2">
            </div>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('account.status')
            </label>
            <select class="form-control" name="status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('account.prev_balance')
            </label>
            <input type="text" name="balance" class="form-control m-input m-input--solid"
                placeholder="@lang('account.prev_balance')">
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="AddNewBank()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader(); 
    reader.onload = function(e) {
      $('#sign').val(e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#file-upload").change(function() {
    $('.image_uploaded').empty();
    $('.image_uploaded').append($('<div class="link_item"><input type="hidden" name="sign"id="sign" ></div>'));
  readURL(this);
});
    function AddNewBank() {
        var form = $('#bank-create-form');
        var successcallback = function (a) {
            toastr.success("@lang('bank.bank_has_been_added')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
</script>
