{{ Form::open(array('route' => array('product.edit_product', $product->id), 'id'=>'product-update-form')) }}
<div class="modal-header">
    <h5 class="modal-title">Update Product - {{$product->product_name}}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-4">
            <label>
                @lang('product.bar_code')
            </label>
            <input type="text" name="product_id" id="product_id" class="form-control m-input m-input--solid" value="{{$product->product_id}}" required>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('product.category')
            </label>
            <select class="form-control" name="category_id">
                @forelse($categories as $category)
                <option value="{{$category->id}}" @if($category->id == $product->category_id) selected @endif >{{$category->category_name}}</option>
                @empty
                <option value="0">@lang('layout.select')</option>
                @endforelse
            </select>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('product.supplier')
            </label>
            <select class="form-control" name="supplier_id" id="supplier_Select">
                @forelse($suppliers as $supplier)
                <option value="{{$supplier->id}}" @if($supplier->id == $product->supplier_id) selected @endif >{{$supplier->supplier_id}} - {{$supplier->supplier_name}}</option>
                @empty
                <option value="0">@lang('layout.select')</option>
                @endforelse
            </select>
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-3">
            <label>
                @lang('product.product_name')
            </label>
            <input type="text" name="product_name" id="product_name" class="form-control m-input m-input--solid" value="{{$product->product_name}}" required>
        </div>
        <div class="col-lg-3">
            <label>
                Product Head Code
            </label>
            <input type="text" name="head_code" class="form-control m-input m-input--solid" value="{{$product->head_code}}" required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('product.product_model')
            </label>
            <input type="text" name="product_model" id="product_model" class="form-control m-input m-input--solid" value="{{$product->product_model}}" required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('product.unit')
            </label>
            <select class="form-control" name="unit_id">
                @forelse($units as $unit)
                <option value="{{$unit->id}}" @if($unit->id == $product->unit_id) selected @endif>{{$unit->unit_name}}</option>
                @empty
                <option value="0">@lang('layout.select')</option>
                @endforelse
            </select>
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label>
                @lang('product.s_price')
            </label>
            <input type="text" name="price" id="price" class="form-control m-input m-input--solid" value="{{$product->price}}" required>
        </div>
        <div class="col-lg-6">
            <label>
                @lang('product.image')
            </label>
            <input id="file-upload" class="form-control file_upload" name=img[] type="file"/> 
            <div class="image_uploaded mt-2">
            </div>
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-12">
            <label>
                @lang('product.c_image')
            </label>
            <div class="col-6">
                @if( $product->image )
                <img src="{{asset('storage/uploads/'.$product->image)}}" class="img-thumbnail w-50" alt="Product Image" id="uploaded">
                @else
                <span class="badge badge-warning">No Image Found</span>
                @endif
            </div>
            
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="UpdateProduct()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader(); 
    reader.onload = function(e) {
      $('#image').val(e.target.result);
      $('#uploaded').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#file-upload").change(function() {
    $('.image_uploaded').empty();
    $('.image_uploaded').append($('<div class="product_image"><input type="hidden" name="image"id="image" ></div>'));
  readURL(this);
});

    function UpdateProduct() {
        var form = $('#product-update-form');
        var successcallback = function (a) {
            toastr.success("@lang('category.category_has_been_added')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }

$("#supplier_Select").select2()
    .on("select2:select", function(e) {
        var supplier_element = $(e.currentTarget);
        var supplier_val = supplier_element.val();
        $('#supplier_Select').val(supplier_val);
});    
</script>
