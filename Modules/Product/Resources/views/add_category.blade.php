{!!Form::open(['route'=>'product.add_category','id'=>'category-create-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('category.new_category')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-5">
            <label>
                @lang('category.category_name')
            </label>
            <input type="text" name="category_name" id="category_name" class="form-control m-input m-input--solid"
                placeholder="@lang('category.category_name')" required>
        </div>
        <div class="col-lg-5">
            <label>
                @lang('category.category_slug')
            </label>
            <input type="text" name="category_slug" id="category_slug" class="form-control m-input m-input--solid"
                placeholder="@lang('category.category_slug')" required>
        </div>
        <div class="col-lg-2">
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
    <button type="button" onclick="AddNewCategory()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
    function AddNewCategory() {
        var form = $('#category-create-form');
        var successcallback = function (a) {
            toastr.success("@lang('category.category_has_been_added')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
</script>
