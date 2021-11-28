{!!Form::open(['route'=>'fiscalyears.add','id'=>'fiscal-add-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">Add New Fiscal Years</h5>
    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
<div class="modal-body">
    <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label">@lang('fiscalyears.starting_date')</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" required  name="starting_date">
        </div>
    </div>
    <div class="form-group row">
        <label for="display_name" class="col-sm-2 col-form-label">@lang('fiscalyears.ending_date')</label>
        <div class="col-sm-10">
            <input type="date" class="form-control" required  name="ending_date" >
            <input type="hidden" value="1" name="status">
        </div>
    </div>
</div>
<div class="modal-footer fiscal_year_submit">
    <button type="button" class="btn btn-danger" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" id="add_fiscal_year" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script src="{{asset('js/Modules/FiscalYears/create.js')}}"></script>