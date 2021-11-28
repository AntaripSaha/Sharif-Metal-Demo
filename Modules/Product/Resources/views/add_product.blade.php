{!!Form::open(['route'=>'product.add_product','id'=>'product-create-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">@lang('product.new_product')</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-3">
            <input type="checkbox" id="is_set" name="is_set" value="1">
            <label for="is_set"> Product is Set</label><br>
        </div>
        <div class="col-lg-6 d-none" id="p_select">
            <span><b>Select Product Of Set :</b></span>
            <select class="js-multiple" name="set_prod[]" multiple="multiple">
                @forelse($products as $product)
                <option value="{{$product->id}}">{{$product->product_id}}</option>
                @empty
                <option value="0">@lang('layout.select')</option>
                @endforelse
            </select>
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-4">
            <label>
                @lang('product.bar_code')
            </label>
            <input type="text" name="product_id" id="product_id" class="form-control m-input m-input--solid"
                placeholder="@lang('product.bar_code')" required>
        </div>
        <div class="col-lg-4">
            <label>
                @lang('product.category')
            </label>
            <select class="form-control" name="category_id">
                @forelse($categories as $category)
                <option value="{{$category->id}}">{{$category->category_name}}</option>
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
                <option selected disabled>@lang('layout.select')</option>
                @foreach($suppliers as $supplier)
                    <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-6">
            <label>
                @lang('product.product_name')
            </label>
            <input type="text" name="product_name" id="product_name" class="form-control m-input m-input--solid"
                placeholder="@lang('product.product_name')" required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('product.product_model')
            </label>
            <input type="text" name="product_model" id="product_model" class="form-control m-input m-input--solid"
                placeholder="@lang('product.product_model')" required>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('product.unit')
            </label>
            <select class="form-control" name="unit_id">
                @forelse($units as $unit)
                <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                @empty
                <option value="0">@lang('layout.select')</option>
                @endforelse
            </select>
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-3">
            <label>
                @lang('product.s_price')
            </label>
            <input type="text" name="price" id="price" class="form-control m-input m-input--solid"
                placeholder="@lang('product.s_price')" required>
        </div>

        <!--New Added By Nazib-->
        <div class="col-lg-3">
            <label>
                Production Price
            </label>
            <input type="text" name="production_price" id="production_price" class="form-control m-input m-input--solid"
                placeholder="Production Price" required>
        </div>
        <!--End New -->
        
        <div class="col-lg-6">
            <label>
                Company
            </label>
            {{-- <input id="file-upload" class="form-control file_upload" name=img[] type="file"/>  --}}
            <select class="form-control" name="company_id">
                <option value='0'>All</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
            <div class="image_uploaded mt-2">
            </div>
        </div>

        {{-- Product Have Head or Not Start --}}
        <div class="col-lg-6">
            <label>Product Have Head ?</label> &nbsp;&nbsp;
            <input type="radio" name="is_head" class="radioButtons" id="head_no" value="0" checked> &nbsp;
            <label for="">No</label>
            &nbsp;
            <input type="radio" name="is_head" class="radioButtons" id="head_yes" value="1"> &nbsp;
            <label for="">Yes</label>
        </div>
        {{-- Product Have Head Or Not End --}}

        {{-- Product Head Info Start --}}
        <div class="col-md-12 row" id="head_product_info">
            <div class="col-md-5">
                <label>Head Code</label>
                <input type="text" class="form-control" id="head_code">
            </div>

            <div class="col-md-5">
                <label>Sale Price</label>
                <input type="number" class="form-control" id="head_price">
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-info mt-4" id="addHead" onclick="addHeadItem()">Add Head</button>
            </div>
        </div>

        {{-- Head Name and Price Start --}}
        <div class="col-md-12 row ml-1 mr-1 mt-1" id="headTbaleInfo">
            <table class="table table-sm table-bordered">
                <thead class="text-center">
                    <th>Head Code</th>
                    <th>Sale Price</th>
                    <th>Action</th>
                </thead>

                <tbody id="headInfoList">
                    
                </tbody>
            </table>
        </div>
        {{-- Head Name and Price End --}}

        {{-- Product Head Info End --}}

    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="AddNewProduct()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">

$('#head_product_info').hide();
$('#headTbaleInfo').hide();

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader(); 
    reader.onload = function(e) {
      $('#image').val(e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]);
  }
}

$("#file-upload").change(function() {
    $('.image_uploaded').empty();
    $('.image_uploaded').append($('<div class="product_image"><input type="hidden" name="image"id="image" ></div>'));
  readURL(this);
});

    function AddNewProduct() {
        var form = $('#product-create-form');
        var successcallback = function (a) {
            toastr.success("@lang('product.product_has_been_added')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
$(document).ready(function() {
    $('.js-multiple').select2();
});


$("#is_set").change(function() {
    if(this.checked) {
        var s = 1;
        $('#p_select').removeClass('d-none');
    }else{
        var s = 0;
        $('#p_select').addClass('d-none');
    }
    $('#is_set').val(s);
});

$("#supplier_Select").select2()
    .on("select2:select", function(e) {
        var supplier_element = $(e.currentTarget);
        var supplier_val = supplier_element.val();
        $('#supplier_Select').val(supplier_val);
});

// Product Head Checked or Not Start
$('.radioButtons').click(function(){
    
    // If Head Yes Button is Clicked Start
    if($("#head_yes")[0].checked){
        $('#head_product_info').show();
        $('#headTbaleInfo').show();
    }
    // If Head Yes Button is Clicked End
    else{
        $('#head_product_info').hide();
        $('#headTbaleInfo').hide();
    }
    
});
  
// Product Head Checked or Not End

// Add Product Head Item Start
function addHeadItem(){
    var headCode = $('#head_code').val();
    var price = $('#head_price').val();

    if(headCode && price){
        
        $('#headInfoList').append(`
            <tr class="text-center">
                <td>
                    <input type="text" class="form-control form-control-sm" value=${headCode} name="head_code[]">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm" value=${price} name="head_price[]">
                </td>
                <td>
                    <button class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                </td>
            </tr>
        `);

        //Clear Head and Price Input Field
        $('#head_code').val('');
        // $('#head_price').val('');
    }else{
        swal("", "Please Add Head Code or Price", "error");
        return false;
    }
}
// Add Product Head Item End

</script>
