{!!Form::open(['route'=>'warehouse.add_wareproduct','id'=>'wareproduct-create-form']) !!}
<div class="modal-header">
    <h5 class="modal-title">Add Products By Chalan No</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="form-group m-form__group row">
        <div class="col-lg-4">
            <label>
                @lang('warehouse.chalan_no')
            </label>
            <input type="text" class="form-control" name="chalan_no">
        </div>
        <div class="col-lg-4">
            <label>
                @lang('layout.date')
            </label>
            <input type="date" class="form-control" name="v_date">
        </div>
    </div>
    <div class="form-group m-form__group row">
        <div class="col-lg-3">
            <label>
                @lang('warehouse.warehouse')
            </label>
            <select class="form-control" id="warehouse_id" onchange="getware(this);">
                <option >Select</option>
                @forelse($warehouses as $warehouse)
                <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
                @empty
                <option value="0">@lang('layout.select')</option>
                @endforelse
            </select>
        </div>
        <div class="col-lg-3">
            <label>
                @lang('product.product')
            </label>
            <select class="form-control js-example-basic-single" onchange="getprod(this)" id="product_id">
                <option>Select</option>
                @forelse($products as $product)
                <option value="{{$product->id}}">{{$product->product_name}} - {{$product->product_id}}
                @if ($product->head_code)
                    ({{ $product->head_code }} Head)
                @endif
                </option>
                @empty
                <option value="0">@lang('layout.select')</option>
                @endforelse
            </select>
        </div>

        {{-- Product Head Code Start --}}
        <div class="col-lg-2">
            <label>
                Head Code
            </label>
            <input type="text" readonly class="form-control" id="head_code">
        </div>
        {{-- Product Head Code End --}}

        <div class="col-lg-2">
            <label>
                @lang('warehouse.stq_q')
            </label>
            <input type="text" class="form-control" onkeyup="stckq(this)" id="stck_q">
        </div>
        
        <div class="col-lg-2">
            <label>
               <span class="d-none">
                   Add
               </span>
            </label>
            <br>
            <button type="button" class="btn btn-secondary btn-md mt-2" onclick="addRowProduct()">Add</button>
        </div> 
    </div>
    <div class="form-group m-form__group row">
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="normalinvoice">
                <thead>
                    <tr>
                        <th class="text-center">Warehouse</th>
                        <th class="text-center product_field">Item Name</th>
                        <th class="text-center">HeadCode</th>
                        <th class="text-center">Qnty<i class="text-danger">*</i></th>
                        <th class="text-center">Actions <i class="text-danger">*</i></th>
                    </tr>
                </thead>
                <tbody id="addinvoiceItem">

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('layout.cancel')</button>
    <button type="button" onclick="AddNewProduct()" class="btn btn-success">@lang('layout.save')</button>
</div>
{!! Form::close() !!}

<script type="text/javascript">
   var produ_name='';
   var product_id;
   var stck_q;
   var head_code;
   var ware_name='';
   var w_id;
   var produ_head_code;

function getware(e) {
    w_id = e.value;
    ware_name = $(e).find("option:selected").text();
}
function getprod(e) {
    product_id = e.value;
    produ_name = $(e).find("option:selected").text();

    var url = baseUrl + "product/get_price/"+product_id;
    getAjaxdata(url, requestCallback, 'get');
}

var requestCallback = function (response) {
        produ_head_code = response.head_code;
        $('#head_code').val(response.head_code);
    }

function stckq(e) {
    stck_q = e.value;
}


var count = 1;
function addRowProduct(e) {
    $("#addinvoiceItem").append('<tr>'+

        '<td>'+
            '<input type="hidden" name="warehouse_id[]" id="warehouse_id_' + count + '" class="form-control form-control-sm">'+
            '<input type="text" readonly class="form-control" id="ware_name_' + count + '">'+
        '</td>'+

        '<td class="product_field">'+
            '<input type="text" id="prod_name_' + count + '" value="" readonly class="form-control form-control-sm">'+
            '<input type="hidden" id="prod_id_' + count + '" name=product_id[] readonly class="form-control form-control-sm">'+
        '</td>'+

        '<td>'+
            '<input type="text" name=head_code[] readonly id="produ_head_code_' + count + '" class="form-control form-control-sm">'+
        '</td>'+

        '<td>'+
            '<input type="text" name="stck_q[]" readonly id="stck_q_' + count + '" class="form-control form-control-sm">'+
        '</td>'+

        '<td>'+
            '<button class="btn btn-danger btn-sm text-right" type="button" value="Delete" onclick="deleteRow(this)">'+
                '<i class="fa fa-trash"></i>'+
            '</button>'+
        '</td></tr>');

    $('#prod_name_' + count + '').val(produ_name);
    $('#prod_id_' + count + '').val(product_id);
    $('#prod_name_' + count + '').val(produ_name);
    $('#ware_name_' + count + '').val(ware_name);
    $('#warehouse_id_' + count + '').val(w_id);
    $('#stck_q_' + count + '').val(stck_q);
    $('#produ_head_code_'+ count +'').val(produ_head_code);
    $('#head_code_'+count+'').val(head_code);
    count = count + 1;
    $('#stck_q').val('');
    $('#head_code').val('');

}
function deleteRow(e) {
    $(e).closest("tr").remove();
}
    function AddNewProduct() {
        var form = $('#wareproduct-create-form');
        var successcallback = function (a) {
            toastr.success("@lang('warehouse.product_has_been_added')", "@lang('layout.success')!");
            $('#ajax-modal').modal('hide');
            // location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
$(document).ready(function() {
    $('.js-example-basic-single').select2();
});
</script>
