{!!Form::open(['route'=>'supplier.add_supplier','id'=>'supplier-create-form','enctype'=>"multipart/form-data"]) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('supplier.new_supplier')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-4">
            <label>
                @lang('supplier.supplier_id')
            </label>
            <input type="text" name="supplier_id" class="form-control m-input m-input--solid"
                placeholder="@lang('supplier.supplier_id')" required>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('supplier.supplier_name')
            </label>
            <input type="text" name="supplier_name" class="form-control m-input m-input--solid"
                placeholder="@lang('supplier.supplier_name')" required>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('supplier.supplier_email')
            </label>
            <input type="text" name="email" class="form-control m-input m-input--solid"
                placeholder="@lang('supplier.supplier_email')" >
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-6">
            <label class="">
                @lang('supplier.supplier_phone')
            </label>
            <input type="text" name="mobile" class="form-control m-input m-input--solid"
                placeholder="@lang('supplier.supplier_phone')" required>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('supplier.secondery_phone')
            </label>
            <input type="text" name="sec_mobile" class="form-control m-input m-input--solid"
                placeholder="@lang('supplier.secondery_phone')" >
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.address')
            </label>
            <input type="text" name="address" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.address')" required>
        </div>
        <div class="col-lg-6">
            <label class="">
                @lang('layout.secondary_address')
            </label>
            <input type="text" name="address2" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.secondary_address')" >
        </div>

    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-4">
            <label class="">
                @lang('layout.city')
            </label>
            <input type="text" name="city" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.city')" >
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.state')
            </label>
            <input type="text" name="state" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.state')" >
        </div>
        <div class="col-lg-4">
            <label class="">
                @lang('layout.country')
            </label>
            <input type="text" name="country" class="form-control m-input m-input--solid"
                placeholder="@lang('layout.country')" >
        </div>
    </div>
    <div class="form-group m-form__group row">

        <div class="col-lg-12">
            <label class="">
                @lang('supplier.pre_balance')
            </label>
            <input type="number" name="balance" class="form-control m-input m-input--solid"
                placeholder="@lang('supplier.pre_balance')">
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="AddNewSupplier()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    function AddNewSupplier() {
        var form = $('#supplier-create-form');
        var successcallback = function (a) {
            toastr.success('Supplier Added Successfully !!');
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
</script>
