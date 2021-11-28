{!!Form::open(['route'=>'product.add_unit','id'=>'unit-create-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('unit.new_unit')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label>
                @lang('unit.unit_name')
            </label>
            <input type="text" name="unit_name" id="unit_name" class="form-control m-input m-input--solid"
                placeholder="@lang('unit.unit_name')" required>
        </div>
        <div class="col-lg-6">
            <label>
                @lang('layout.status')
            </label>
            <select class="form-control" name="status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="AddNewUnit()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    function AddNewUnit() {
        var form = $('#unit-create-form');
        var successcallback = function (a) {
            toastr.success("@lang('unit.unit_has_been_added')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
</script>
