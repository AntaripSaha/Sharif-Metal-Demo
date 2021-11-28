{{ Form::open(array('route' => array('warehouse.edit_warehouse', $warehouse->id), 'id'=>'warehouse-update-form')) }}
<div class="modal-header">
    <h5 class="modal-title">@lang('unit.update_unit') - {{$warehouse->name}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-5">
            <label>
                @lang('warehouse.warehouse_name')
            </label>
            <input type="text" name="name" id="name" class="form-control m-input m-input--solid"
                value="{{$warehouse->name}}" required>
        </div>
        <div class="col-lg-5">
            <label>
                @lang('warehouse.location')
            </label>
            <input type="text" name="location" id="location" class="form-control m-input m-input--solid"
                value="{{$warehouse->location}}" required>
        </div>
        <div class="col-lg-2">
            <label>
                @lang('layout.status')
            </label>
            <select class="form-control" name="status">
                <option value="1" @if($warehouse->status == 1) selected @else @endif>Active</option>
                <option value="0" @if($warehouse->status == 0) selected @else @endif>Inactive</option>
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="UpdateWarehouse()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}
<script type="text/javascript">
    function UpdateWarehouse() {
        var form = $('#warehouse-update-form');
        var successcallback = function (a) {
            toastr.success("@lang('warehouse.warehouse_has_been_updated')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }

</script>
