<div class="modal-header">
    <h5 class="modal-title">@lang('warehouse.warehouse_details')</h5>
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
            <span class="form-control">{{$warehouse->name}}</span>
        </div>
        <div class="col-lg-5">
            <label>
                @lang('warehouse.location')
            </label>
            <span class="form-control">{{$warehouse->location}}</span>
        </div>
        <div class="col-lg-2">
            <label>
                @lang('layout.status')
            </label>
            <span class="form-control">
                @if($warehouse->status == 1) Active @else Inactive @endif
            </span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.close')</button>
</div>
