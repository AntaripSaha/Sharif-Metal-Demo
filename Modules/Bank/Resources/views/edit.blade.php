{{ Form::open(array('route' => array('bank.editBank', $bank_details->id), 'id'=>'bank-update-form')) }}
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
            <input type="text" name="bank_name" value="{{$bank_details->bank_name}}" class="form-control m-input m-input--solid"
                placeholder="@lang('account.bank name')" required>
        </div>
        <div class="col-lg-6">
            <label>
                @lang('account.account name')
            </label>
            <input type="text" name="account_name" value="{{$bank_details->account_name}}" class="form-control m-input m-input--solid"
                placeholder="@lang('account.account name')" required>
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label class="">
                @lang('account.account no')
            </label>
            <input type="text" name="account_no" value="{{$bank_details->account_no}}" class="form-control m-input m-input--solid"
                placeholder="@lang('account.account no')" required>
        </div>
        <div class="col-lg-6">
            <label>
                @lang('account.branch_name')
            </label>
            <input type="text" name="branch_name" value="{{$bank_details->branch}}" class="form-control m-input m-input--solid"
                placeholder="@lang('account.branch_name')" required>
        </div>
    </div>

    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label class="" for="sign">
                @lang('account.sign')
            </label>
            <input id="file-upload" class="form-control file_upload" name=img[] type="file"/> 
            <div class="image_uploaded mt-2">
            </div>
        </div>
        <div class="col-lg-6">
            <label>
                @lang('account.status')
            </label>
            <select class="form-control" name="status">
                <option value="1" @if($bank_details->status == 1) selected @endif>Active</option>
                <option value="0" @if($bank_details->status == 0) selected @endif>Inactive</option>
            </select>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="UpdateBankInformation()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    function UpdateBankInformation() {
        var form = $('#bank-update-form');
        var successcallback = function (a) {
            toastr.success("@lang('account.bank_updated')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }

</script>
