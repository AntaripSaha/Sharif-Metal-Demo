@extends('layouts.app')
@section('css')
@endsection
@section('content')
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">@lang('menu.Direct Sale')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('menu.Direct Sale')</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card_buttons">
                        <h3 class="card-title">@lang('menu.Direct Sale')</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>'seller.direct_sale','id'=>'sell_req-add-form','enctype'=>"multipart/form-data"]) !!}
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('customer.customer_name')
                                </label>
                                <select class="form-control" id="customer_Select" name="customer_id">
                                    @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" name="customer_id">{{$customer->customer_id}} - {{$customer->customer_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('warehouse.dco_code')
                                </label>
                                <input type="text" id="dco_code" name="dco_code" class="form-control" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('company.company_name')
                                </label>
                                <select class="form-control js-example-basic-single" name="company_id">
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}" name="company_id">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.date')
                                </label>
                                <input type="date" id="v_date" name="v_date" class="form-control">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-4">
                                <label>
                                    @lang('warehouse.pname')
                                </label>
                                <input type="text" id="pname" name="pname" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    @lang('warehouse.reeiver')
                                </label>
                                <input type="text" id="receiver" name="receiver" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    @lang('layout.phn_no')
                                </label>
                                <input type="text" id="phn_no" name="phn_no" class="form-control">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('product.product')<span class="text-danger text-bold"> *</span>
                                </label>
                                <select class="form-control" id="prod_Select" name="product_id">
                                    <option selected>@lang('layout.select')</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->product_name}} - {{$product->product_id}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="product_id_field">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('layout.price')
                                </label>
                                <input type="text" readonly class="form-control" id="price">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('layout.qnt')<span class="text-danger text-bold"> *</span>
                                </label>
                                <input type="number" id="qt" onkeyup="quantity_calculate(this);" value="" class="form-control">
                            </div>

                            <div class="col-lg-2">
                                <label>
                                    @lang('product.prod_disc')
                                </label>
                                <input type="number" id="discount_cal"  onchange="discount_calculate(this);" value="0" class="form-control">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('layout.total')
                                </label>
                                <input type="text" readonly id="total" class="form-control">
                            </div>
                            <div class="col-lg-1">
                                <label>
                                    @lang('layout.add')
                                </label>
                                <button class="btn btn-sm btn-warning form-control" type="button" onclick="addRow()"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="table-responsive col-lg-12">
                                <table class="table table-bordered table-hover" id="normalinvoice">
                                    <thead>
                                        <tr>
                                            <th class="text-center product_field">Item Information <i class="text-danger">*</i></th>
                                            <th class="text-center">Qnty <i class="text-danger">*</i></th>
                                            <th class="text-center">Rate <i class="text-danger">*</i></th>
                                            <th class="text-center">Discount %</th>
                                            <th class="text-center invoice_fields">Total
                                            </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addinvoiceItem">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-left" colspan="3" rowspan="2"><b>Note:</b><br> <textarea name="remarks" id="remarks" cols="6" class="form-control" rows="2"></textarea></td>
                                            <td class="text-right" colspan="1"><b>Discount :</b></td>
                                            <td class="text-right">
                                                <input type="number" id="discount" onfocusout="discount_manual(this)" class="form-control text-right" name="discount" tabindex="16">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><b>Total:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="in_total" class="form-control text-right" readonly name="amount" placeholder="0.00" tabindex="16">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('warehouse.warehouse')
                                </label>
                                <select class="form-control" id="warehouse_Select" name="warehouse_id">
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{$warehouse->id}}" name="warehouse_id">{{$warehouse->name}}</option>
                                    @endforeach
                                </select>                                
                            </div>                            
                            <div class="col-lg-5">
                                <label>
                                    @lang('layout.trans_name')
                                </label>
                                <input type="text" name="transp_name" class="form-control">
                            </div>
                            <div class="col-lg-4">
                                <label>
                                    @lang('layout.deliv_pname')
                                </label>
                                <input type="text" name="deliv_pname" class="form-control">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-12 transaction_submit">
                                <button type="button" onclick="AddNewInvoice()" class="btn btn-success">@lang('layout.save')</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- ./col -->
    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@section('js')
<script>
document.getElementById('v_date').value = moment().format('YYYY-MM-DD');
var produ_id;
var disc;
var prod_disc=0;
var customer_id;
var produ_name;
var produ_price;
var count = 1;
var qnty = 0;
var total = 0;
var in_total = 0;
var price = 0;
var in_total_n = 0;
var prod_disc_n = 0;
$("#prod_Select").select2()
    .on("select2:select", function(e) {
        var selected_element = $(e.currentTarget);
        var select_val = selected_element.val();
        produ_id = select_val;
        $('#product_id_field').val(select_val);
        var url = baseUrl + "product/get_price/" + select_val;
        getAjaxdata(url, requestCallback, 'get')
    });
$("#customer_Select").select2()
    .on("select2:select", function(e) {
        var sel_element = $(e.currentTarget);
        var cus_val = sel_element.val();
        customer_id = cus_val;
        $('#customer_Select').val(customer_id);
        var selected_el = $("#customer_Select").select2('data')[0]['text'];
        var res = selected_el.split("-");
        $('#dco_code').val(res[0]);
    });
$("#warehouse_Select").select2()
    .on("select2:select", function(e) {
        var sel_element = $(e.currentTarget);
        var ware_val = sel_element.val();
        $('#warehouse_Select').val(ware_val);
    });
var requestCallback = function(response) {
    produ_name = response.name;
    produ_price = response.price;
    $("#price").val(response.price);
}

function quantity_calculate(e) {
    qnty = e.value;
    var pr = $("#price").val();
    disc = $('#discount_cal').val();
    if (disc == 0) {
        price = pr*qnty
    }else{
        var dis_price = (pr*qnty)*disc/100;
        prod_disc = prod_disc + dis_price;
        price = (pr*qnty)-dis_price;
    }
    total = price;
    $("#total").val(price);
    $('#discount').val(prod_disc);
}
function discount_calculate(e) {
    disc = e.value;
    var price_product = $("#price").val();
    var qntity = $("#qt").val();
    var price_n = price_product*qntity;
    var d_amount = price_n*disc/100;
    prod_disc = prod_disc+d_amount;
    price = price_n-d_amount;
    total = price;
    $("#total").val(price);
    $('#discount').val(prod_disc);
}

function addRow(e) {
    $("#addinvoiceItem").append('<tr><td class="product_field"><input type="text" id="prod_name_' + count + '" value="" readonly class="form-control"><input type="hidden" id="prod_id_' + count + '" name=product_id[] readonly class="form-control"></td><td><input type="text" name="qnty[]" readonly id="qnty_' + count + '" class="form-control"></td><td class="invoice_fields"><input type="text" id="produ_price_' + count + '" readonly class="form-control"></td><td class="invoice_fields"><input class="form-control text-right" type="text" id="prod_disc_' + count + '" name="prod_disc[]" readonly="readonly"></td><td class="invoice_fields"><input class="form-control text-right" type="text" id="total_price_' + count + '" readonly="readonly"></td><td><button class="btn btn-danger text-right" type="button" value="Delete" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button></td></tr>');
    $('#prod_name_' + count + '').val(produ_name);
    $('#prod_id_' + count + '').val(produ_id);
    var price_of_d = prod_disc + '-tk';
    $('#prod_disc_' + count + '').val(disc);
    $('#qnty_' + count + '').val(qnty);
    $('#produ_price_' + count + '').val(produ_price);
    $('#total_price_' + count + '').val(total);
    in_total = total + in_total;
    $('#in_total').val(in_total);
    count = count + 1;
    $('#qt').val('');
    $('#total').val('');
    $('#price').val('');
    $('#discount_cal').val('');
    $('#prod_Select').val(null).trigger('change');

}
function cal_total_amnt(e) {
    var sale_dis = e.value;
    var sale_d = (in_total * sale_dis)/100;
    prod_disc_n = prod_disc + sale_d;
    in_total_n = in_total - sale_d;
    $('#discount').val(prod_disc_n);
    $('#in_total').val(in_total_n);
}

function discount_manual(e) {
    var dis = $('#discount').val();
    var am = in_total - dis;
    $('#in_total').val(am);
}

function deleteRow(e) {
    var price_id =$(e).closest("tr").find("input[type=text].total_price ").attr('id');
    var in_p = '#'+price_id;
    var pr = $(in_p).val();
    in_total = in_total - pr;
    $('#in_total').val(in_total);
    $(e).closest("tr").remove();
}

function AddNewInvoice() {

}

function AddNewInvoice() {
    var content = 'Are you Sure ?';
    var confirmtext = 'Place Requsition';
    var confirmCallback=function(){
        var form = $('#sell_req-add-form');
        var successcallback = function (a) {
            swal({
                title: 'Do you want to Print the Challan'+a.data+' ?',
                text: '',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#28A745",
                confirmButtonText: "Print",
                cancelButtonText: "Cancel",
                closeOnConfirm: true,
                closeOnCancel: false
            },
            function (isConfirm) {
                if (isConfirm) {
                    var url = baseUrl+"warehouse/print_chalan/"+a.data;
                    swal("Challan Successfully Printed", "success");
                    location.href = url;
                } else {

                    location.reload();
                }
            }) 
        }
        ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
    }
    confirmAlert(confirmCallback,content,confirmtext)
}


</script>
@endsection
