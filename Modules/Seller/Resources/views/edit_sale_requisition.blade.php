@extends('layouts.app')
@section('css')
@endsection
@section('content')

{{-- Custom CSS For Seller Mobile View  --}}
<style>
    .frm-control {
        width: max-content;
    }

</style>
<!-- Main content -->
<section class="content" id="ajaxview">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Edit Sale Requsition</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Sale Requsition</li>
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
                        <h3 class="card-title">Edit Sale Requsition</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>array('edit_requisition_details',$id),'id'=>'sell_req-edit-form','enctype'=>"multipart/form-data"])
                        !!}
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('customer.customer_name')
                                </label>
                                <input type="text" class="form-control form-control-sm" name="customer_id"
                                    id="customer_id" readonly value="{{ $customer_name['customer_name'] }}">

                            </div>
                            <div class="col-lg-3">
                                <label>
                                    Seller Name
                                </label>
                                <input type="text" class="form-control form-control-sm" value="{{ $seller_info['user_id'] }} - {{ $seller_info['name'] }}" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    Requisition No
                                </label>
                                <input type="text" class="form-control form-control-sm" value="{{ $sale_request_master->req_id }}" readonly>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    Requisition Date
                                </label>
                                <input type="date" class="form-control form-control-sm" value="{{ $sale_request_master->v_date }}" readonly>
                            </div>
                        </div>

                        {{-- Add New Product Section Start --}}
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('product.product')<span class="text-danger text-bold"> *</span>
                                </label>
                                <select class="form-control form-control-sm product_id" id="prod_Select"
                                    name="product_id">
                                    <option selected>@lang('layout.select')</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}">
                                        {{$product->product_name.' - '. $product->product_id }} 
                                        @if ($product->head_code)
                                            - {{ $product->head_code }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="product_id_field">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('layout.price')
                                </label>
                                <input type="text" readonly class="form-control form-control-sm product_rate"
                                    id="price">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('layout.qnt')<span class="text-danger text-bold"> *</span>
                                </label>
                                <input type="number" id="qt" onkeyup="quantity_calculate(this);" value=""
                                    class="form-control form-control-sm product_quantity">
                            </div>

                            <div class="col-lg-2">
                                <label>
                                    @lang('product.prod_disc')
                                </label>
                                <input type="number" id="discount_cal" onchange="discount_calculate(this);" value="0"
                                    class="form-control form-control-sm product_discount">
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('layout.total')
                                </label>
                                <input type="text" readonly id="total"
                                    class="form-control form-control-sm product_total_price">
                            </div>
                            <div class="col-lg-1">
                                <label>
                                    @lang('layout.add')
                                </label>
                                <button class="btn btn-sm btn-warning form-control form-control-sm" type="button"
                                    onclick="addRow()"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        {{-- Add New Product Section End --}}


                        <div class="form-group m-form__group row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm" id="normalinvoice">
                                    <thead>
                                        <tr>
                                            <th class="text-center product_field">Item Information <i
                                                    class="text-danger">*</i></th>
                                            <th class="text-center">Qnty <i class="text-danger">*</i></th>
                                            <th class="text-center">Rate <i class="text-danger">*</i></th>
                                            <th class="text-center">Discount %</th>
                                            <th class="text-center invoice_fields">Total
                                            </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addinvoiceItem">
                                        {{-- Show Existing Product Informations Start --}}
                                        @php
                                        $in_total_amount = 0;
                                        $prd_disc = 0;
                                        @endphp
                                        @foreach ($product_details as $product)
                                        <tr>
                                            <td>
                                                <input class="form-control form-control-sm prod_id" type="text"
                                                    name="product_name[]" value="{{ $product->products->product_name }}" readonly>
                                                <!-- Hidden Input for get Product ID -->
                                                <input type="hidden" name="product_id[]" value="{{ $product->products->id }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm qnty"
                                                    value="{{ $product->qnty }}" name="qnty[]">
                                            </td>
                                            <td>
                                                <input readonly class="form-control form-control-sm rate" type="text"
                                                    name="" id="" value="{{ $product->products->price }}">
                                            </td>
                                            <td>
                                                <input readonly class="form-control form-control-sm prod_disc"
                                                    type="text" name="prod_disc[]" value="{{ $product->prod_disc }}">
                                            </td>
                                            <td>
                                                @if($product->prod_disc == null || $product->prod_disc == 0)
                                                @php
                                                $prod_new_price = $product->qnty * $product->products->price;
                                                @endphp

                                                @else
                                                {{-- <?php $prd_disc = $product->qnty * $product->products->price*($product->prod_disc/100) ?> --}}
                                                @php
                                                $prod_new_price = ($product->qnty * $product->products->price) -
                                                ((($product->qnty *
                                                $product->products->price) * $product->prod_disc) / 100);
                                                @endphp

                                                @endif

                                                <input readonly class="form-control form-control-sm product_price"
                                                    type="text" name=""
                                                    value="{{ $product->qnty *  $product->products->price }}">
                                            </td>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm delete-signle-row"><i
                                                        class="fa fa-trash"></i></button>
                                            </td>
                                            @php
                                            $in_total_amount += $prod_new_price;
                                            @endphp
                                        </tr>
                                        @endforeach
                                        {{-- Show Existing Product Informations End --}}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            {{-- Transport Name Start --}}
                                            <td class="text-left" colspan="3" rowspan="2"><b>TransportName:</b><br>
                                                <textarea name="transp_name" id="transp_name" cols="6"
                                                    class="form-control form-control-sm" rows="2">{{ $sale_request_master->transp_name }}
                                                </textarea>
                                            </td>
                                            {{-- Transport Name End --}}

                                            {{-- Total Amount Start --}}
                                            <td class="text-right" colspan="1"><b>Total Amount :</b></td>
                                            <td class="text-right">
                                                <input type="number"
                                                    class="form-control form-control-sm text-right total_price"
                                                    name="in_total_amount" placeholder="0" tabindex="16"
                                                    value="{{ $in_total_amount }}" readonly id="total_amount_without_discount">
                                            </td>
                                            {{-- Total Amount End --}}
                                        </tr>

                                        {{-- Sale Discount Start --}}
                                        <tr>
                                            <td class="text-right" colspan="1"><b>Sale Discount(%) :</b></td>
                                            <td class="text-right">
                                                @php
                                                    $sale_discount = $sale_request_master->sale_disc;
                                                    if($sale_request_master->sale_discount_overwrite != null){
                                                        $sale_discount = $sale_request_master->sale_discount_overwrite;
                                                    }else{
                                                        if($sale_discount == null){
                                                            $sale_discount = 0;
                                                        }else{
                                                            $sale_discount = $sale_request_master->sale_disc;
                                                        }
                                                    }
                                                @endphp
                                                <input type="number" id="sale_disc"
                                                    class="form-control form-control-sm text-right" name="sale_disc"
                                                    placeholder="0" tabindex="16"
                                                    value="{{ $sale_discount }}">
                                            </td>
                                        </tr>
                                        {{-- Sale Discount End --}}
                                        <tr>
                                            {{-- Remarks Start --}}
                                            <td class="text-left" colspan="3" rowspan="2"><b>Remarks:</b><br>
                                                <textarea name="remarks" id="remarks" cols="6"
                                                    class="form-control form-control-sm"
                                                    rows="2">{{ $sale_request_master->remarks }}</textarea>
                                            </td>
                                            {{-- Remarks End --}}

                                            {{-- Total Discount Start --}}
                                            <td class="text-right"><b>Total Discount:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="discount"
                                                    class="form-control form-control-sm text-right" readonly
                                                    name="discount" placeholder="0.00" tabindex="16"
                                                    value="{{ $sale_request_master->discount }}">
                                            </td>
                                            {{-- Total Discount End --}}
                                        </tr>

                                        {{-- Grand Total Start --}}
                                        <tr>
                                            <td class="text-right"><b>Grand Total:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="in_total"
                                                    class="form-control form-control-sm text-right" readonly
                                                    name="amount" placeholder="0.00" tabindex="16"
                                                    value="{{ $sale_request_master->amount }}">
                                            </td>
                                        </tr>
                                        {{-- Grand Total End --}}

                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-12 transaction_submit">
                            <button type="button" onclick="AddNewInvoice()" class="btn btn-success ml-2"
                                id="btn_save">@lang('layout.save')</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
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
<script src="{{asset('js/Modules/Bank/transaction.js')}}"></script>
<script>
    // document.getElementById('v_date').value = moment().format('YYYY-MM-DD');
    var produ_id;
    var disc;
    var prod_disc = 0;
    var customer_id;
    var seller_id;
    var produ_name;
    var produ_price;
    var count = 1;
    var qnty = 0;
    var total = 0;
    var in_total = 0;
    var price = 0;
    var in_total_n = 0;
    var prod_disc_n = 0;
    
    var total_amount_without_discount = $('#total_amount_without_discount').val();

    $("#prod_Select").select2()
        .on("select2:select", function (e) {
            var selected_element = $(e.currentTarget);
            var select_val = selected_element.val();
            produ_id = select_val;
            $('#product_id_field').val(select_val);
            var url = baseUrl + "product/get_price/" + select_val;
            getAjaxdata(url, requestCallback, 'get')
        });
    $("#customer_Select").select2()
        .on("select2:select", function (e) {
            var sel_element = $(e.currentTarget);
            var cus_val = sel_element.val();
            customer_id = cus_val;
            $('#customer_Select').val(customer_id);
            var selected_el = $("#customer_Select").select2('data')[0]['text'];
            var res = selected_el.split("-");
            $('#dco_code').val(res[0]);
        });

    $("#seller_Select").select2()
        .on("select2:select", function (e) {
            var seller_element = $(e.currentTarget);
            var seller_val = seller_element.val();
            seller_id = seller_val;
            $('#seller_Select').val(seller_id);
        });
    var requestCallback = function (response) {
        produ_name = response.name;
        produ_price = response.price;
        $("#price").val(response.price);
    }

    function quantity_calculate(e) {
        qnty = e.value;
        var pr = $("#price").val();
        disc = $('#discount_cal').val();
        if (disc == 0) {
            price = pr * qnty
        } else {
            var dis_price = (pr * qnty) * disc / 100;
            prod_disc = prod_disc + dis_price;
            price = (pr * qnty) - dis_price;
        }
        total = price;
        $("#total").val(price);
        // $('#discount').val(prod_disc);
    }

    function discount_calculate(e) {
        disc = e.value;
        var price_product = $("#price").val();
        var qntity = $("#qt").val();
        var price_n = price_product * qntity;
        var d_amount = price_n * disc / 100;
        prod_disc = prod_disc + d_amount;
        price = price_n - d_amount;
        total = price;
        $("#total").val(price);
        $('#discount').val(prod_disc);

    }

    // Add New Product Start
    function addRow(e) {
        if (qnty == null || qnty == 0) {
            swal("", "Please Add Product Quantity", "error");
            return false;
        } else {
            var prod_name = $('#prod_Select option:selected').text();
            var prod_id = $('.product_id').val();
            var rate = $('.product_rate').val();
            var prod_qnty = $('.product_quantity').val();
            var discount = $('.product_discount').val();
            var product_total_price = $('.product_total_price').val();


            $('#addinvoiceItem').append(`
                <tr>
                    <td>
                        <input class="form-control form-control-sm" type="text" value="${prod_name}">
                        <input class="form-control form-control-sm" type="hidden" name="product_id[]" value="${prod_id}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm qnty" name="qnty[]" value="${prod_qnty}">
                    </td>
                    <td>
                        <input readonly class="form-control form-control-sm rate" type="text" name="" value="${rate}">
                    </td>
                    <td>
                        <input readonly class="form-control form-control-sm" type="text" name="prod_disc[]" value="${discount}">
                    </td>
                    <td>
                        <input readonly class="form-control form-control-sm product_price" type="text" name="" value="${product_total_price}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm delete-signle-row">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `);

            // Update In Total Aamount Start
            total_amount_without_discount = $('#total_amount_without_discount').val();
            total_amount_without_discount = parseInt(total_amount_without_discount) + parseInt(product_total_price);
            in_total = $('#in_total').val();
            in_total = parseInt(in_total) + parseInt(product_total_price);
            $('#in_total').val(in_total);
            $('#total_amount_without_discount').val(total_amount_without_discount);
            // Update In Total Amount End
        }
    }

    // Change Product Quantity Start
    $(document).on('keyup', '.qnty', function () {
        let $this = $(this);
        var product_qnty = $this.closest('tr').find('.qnty').val();
        var product_rate = $this.closest('tr').find('.rate').val();
        //get old Price
        var old_total_price = $this.closest('tr').find('.product_price').val();
        in_total = $('#in_total').val();
        in_total = in_total - old_total_price;

        //Update total_amount_without_discount value
        total_amount_without_discount = total_amount_without_discount - old_total_price;

        var new_total_price = product_qnty * product_rate;
        $this.closest('tr').find('.product_price').val(new_total_price);

        // update in_total variable value
        in_total = in_total + new_total_price
        $('#in_total').val(in_total.toFixed(2));

        total_amount_without_discount = total_amount_without_discount + new_total_price;
        $('#total_amount_without_discount').val(total_amount_without_discount.toFixed(2))
    })
    // Change Product Quantity End

    function cal_total_amnt(e) {
        var role_id = $('#check_role_id').val();
        var sale_dis = e.value;
        if (role_id == 4) {
            if (sale_dis > 30) {
                swal("", "Sale Discount Must be under 30%", "error");
                $('#btn_save').hide();
                return false;
            } else {
                $('#btn_save').show();
            }
        }

        var sale_d = (in_total * sale_dis) / 100;
        prod_disc_n = prod_disc + sale_d;
        in_total_n = in_total - sale_d;
        $('#discount').val(prod_disc_n);
        $('#in_total').val(in_total_n);
    }

    // Calculate Discount Start
    $(document).on('keyup', '#sale_disc', function(){
        var sale_discount = $('#sale_disc').val();
        var total_amount_without_discount = $('#total_amount_without_discount').val();

        var product_discount_amount = (total_amount_without_discount * sale_discount) / 100;
        $('#discount').val(product_discount_amount.toFixed(2));
        in_total = total_amount_without_discount - product_discount_amount;
        $('#in_total').val(in_total.toFixed(2));
    })
    // Calculate Discount End


    // Delete Signle Row Start
    $(document).on('click', '.delete-signle-row', function () {
        let $this = $(this);
        var product_price = $this.closest('tr').find('.product_price').val();
        in_total = $('#in_total').val();
        //remove the current row
        $this.closest('tr').remove();
        in_total = in_total - product_price;
        total_amount_without_discount = total_amount_without_discount - product_price;
        $('#in_total').val(in_total.toFixed(2));
        $('#total_amount_without_discount').val(total_amount_without_discount.toFixed(2));
    })
    // Delete Single Row End


    function AddNewInvoice() {
        
        var content = 'Are you Sure ?';
        var confirmtext = 'Update This Requisition Data';
        var confirmCallback = function () {
            var form = $('#sell_req-edit-form');
            var successcallback = function (a) {
                toastr.success("@lang('warehouse.requ_has_been_added')", "@lang('layout.success')!");
                location.reload();
            }
            ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
        }
        confirmAlert(confirmCallback, content, confirmtext)
    }

</script>
@endsection
