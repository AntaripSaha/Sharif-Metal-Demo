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
                    <h1 class="m-0 text-dark">New Sale Requsition</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">New Sale Requsition</li>
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
                        <h3 class="card-title">New Sale Requsition</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>'seller.add_sell','id'=>'sell_req-add-form','enctype'=>"multipart/form-data"])
                        !!}
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('customer.customer_name')
                                </label>
                                <select class="form-control form-control-sm " id="customer_Select" name="customer_id">
                                    <option disabled selected>{{ 'Select' }}</option>
                                    @foreach($customers as $customer)
                                    <option value="{{$customer->id}}" name="customer_id">{{$customer->customer_id}} -
                                        {{$customer->customer_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label>
                                    @lang('warehouse.dco_code')
                                </label>
                                <input type="text" id="dco_code" name="dco_code" class="form-control form-control-sm"
                                    readonly>
                            </div>

                            <!-- P/O Code Start -->
                            <div class="col-lg-1">
                                <label>
                                    P/O Code
                                </label>
                                <input type="text" id="po_code" name="po_code" class="form-control form-control-sm">
                            </div>
                            <!-- P/O Code End -->
                            <div class="col-lg-3">
                                <label>
                                    @lang('company.company_name')
                                </label>
                                <select class="form-control form-control-sm js-example-basic-single" name="company_id">
                                    @foreach($companies as $company)
                                    <option value="{{$company->id}}" name="company_id">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.date')
                                </label>
                                <input type="date" id="v_date" name="v_date" class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-3">
                                <label>
                                    @lang('warehouse.pname')
                                </label>
                                <input type="text" id="pname" name="pname" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('warehouse.reeiver')
                                </label>
                                <input type="text" id="receiver" name="receiver" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.phn_no')
                                </label>
                                <input type="text" id="phn_no" name="phn_no" class="form-control form-control-sm">
                            </div>
                            {{-- Due Amount Start --}}
                            <div class="col-lg-3">
                                <label>
                                    Due Amount
                                </label>
                                <input type="number" id="due_amount" name="due_amount"
                                    class="form-control form-control-sm">
                            </div>
                            {{-- Due Amount End --}}
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="col-lg-2">
                                <label>
                                    @lang('product.product')<span class="text-danger text-bold"> *</span>
                                </label>
                                <select class="form-control form-control-sm" id="prod_Select" name="product_id">
                                    <option selected>@lang('layout.select')</option>
                                    @foreach($products as $product)
                                    <option value="{{$product->id}}">{{$product->product_name}} -
                                        {{$product->product_id}} @if ($product->head_code)
                                        - {{ $product->head_code }}@endif</option>
                                    @endforeach
                                </select>
                                <input type="hidden" id="product_id_field">
                            </div>
                            {{-- Head Code Start --}}
                            <div class="col-lg-1">
                                <label>HeadCode</label>
                                <input type="text" readonly id="head_code" class="form-control form-control-sm">
                            </div>
                            {{-- Head Code End --}}
                            <div class="col-lg-2">
                                <label>
                                    @lang('layout.price')
                                </label>
                                <input type="text" readonly class="form-control form-control-sm" id="price">
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.qnt')<span class="text-danger text-bold"> *</span>
                                </label>
                                <input type="number" id="qt" onkeyup="quantity_calculate(this);" value=""
                                    class="form-control form-control-sm">
                            </div>

                            <div class="col-lg-2" style="display : none">
                                <label>
                                    @lang('product.prod_disc')
                                </label>
                                <input type="number" id="discount_cal" onchange="discount_calculate(this);" value="0"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-3">
                                <label>
                                    @lang('layout.total')
                                </label>
                                <input type="text" readonly id="total" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-1">
                                <label>
                                    @lang('layout.add')
                                </label>
                                <button class="btn btn-sm btn-warning form-control form-control-sm" type="button"
                                    onclick="addRow()"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group m-form__group row">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="normalinvoice">
                                    <thead>
                                        <tr>
                                            <th class="text-center product_field">Item Information <i
                                                    class="text-danger">*</i></th>
                                            <th>Head Code</th>
                                            <th class="text-center">Qnty <i class="text-danger">*</i></th>
                                            <th class="text-center">Rate <i class="text-danger">*</i></th>
                                            <th class="text-center" style="display: none">Discount %</th>
                                            <th class="text-center invoice_fields">Total
                                            </th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="addinvoiceItem">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td class="text-left" colspan="3" rowspan="2">
                                                <b>Remarks:</b>
                                                <br> 
                                                <textarea name="remarks" id="remarks" cols="6" class="form-control form-control-sm" rows="2"></textarea>
                                            </td>
                                            
                                            <td class="text-right" colspan="1">
                                                <b>Sale Discount :</b>
                                            </td>
                                            <td class="text-right">
                                                <input type="number" id="sale_disc" onfocusout="cal_total_amnt(this)"
                                                    class="form-control form-control-sm text-right" name="sale_disc"
                                                    placeholder="0" tabindex="16">
                                                <!-- Hidden Input type for check seller or admin -->
                                                <input type="hidden" value="{{ $role_id }}" id="check_role_id">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-right"><b>Total Discount:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="discount"
                                                    class="form-control form-control-sm text-right" readonly
                                                    name="discount" placeholder="0.00" tabindex="16">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-left" colspan="3" rowspan="1"><b>Transport Name:</b><br>
                                                <textarea name="transp_name" id="transp_name" cols="6"
                                                    class="form-control form-control-sm" rows="2"></textarea></td>
                                            <td class="text-right"><b>Total:</b></td>
                                            <td class="text-right">
                                                <input type="text" id="in_total"
                                                    class="form-control form-control-sm text-right" readonly
                                                    name="amount" placeholder="0.00" tabindex="16">
                                            </td>
                                        </tr>
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
    document.getElementById('v_date').value = moment().format('YYYY-MM-DD');
    var produ_id;
    var disc;
    var prod_disc = 0;
    var customer_id;
    var seller_id;
    var produ_name;
    var produ_price;
    var produ_head_code;
    var head_code;
    var count = 1;
    var qnty = 0;
    var total = 0;
    var in_total = 0;
    var price = 0;
    var in_total_n = 0;
    var prod_disc_n = 0;
    $("#prod_Select").select2()
        .on("select2:select", function (e) {
            var selected_element = $(e.currentTarget);
            var select_val = selected_element.val();
            produ_id = select_val;
            $('#product_id_field').val(select_val);
            var url = baseUrl + "product/get_price/" + select_val;
            getAjaxdata(url, requestCallback, 'get');
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
        produ_head_code = response.head_code;

        $("#price").val(response.price);
        $('#head_code').val(response.head_code);
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
        $('#discount').val(prod_disc);
    }

    // function head_code_p(e){
    //     head_code = e.value;
    //     console.log(head_code);
    // }

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

    var key = 0;

    function addRow(e) {
        if (qnty == null || qnty == 0) {
            swal("", "Please add Product quantity", "error");
            return false;

        } else {

            
                $("#addinvoiceItem").append('<tr>' +
                    '<td class="product_field">' +
                    '<input type="text" id="prod_name_' + count +
                    '" value="" readonly class="form-control form-control-sm frm-control">' +
                    '<input type="hidden" id="prod_id_' + count +
                    '" name=product_id[] readonly class="form-control form-control-sm frm-control">' +
                    '</td>' +
                    '<td>' +
                    // '<input type="text" name="head_code[]" readonly id="head_code_' + count + '" class="form-control form-control-sm frm-control">'+
                    '<input type="text" id="produ_head_code_' + count +
                    '" readonly class="form-control form-control-sm">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" name="qnty[]" readonly id="qnty_' + count +
                    '" class="form-control form-control-sm frm-control">' +
                    '</td>' +
                    '<td class="invoice_fields">' +
                    '<input type="text" id="produ_price_' + count +
                    '" readonly class="form-control form-control-sm frm-control">' +
                    '</td>' +
                    '<td class="invoice_fields">' +
                    '<input class="form-control form-control-sm frm-control text-right" type="text" id="prod_disc_' +
                    count + '" name="prod_disc[]" readonly="readonly">' +
                    '</td>' +
                    '<td class="invoice_fields">' +
                    '<input class="form-control form-control-sm frm-control text-right total_price" type="text" id="total_price_' +
                    count + '" readonly="readonly">' +
                    '</td>' +
                    '<td>' +
                    '<button class="btn btn-danger text-right delete-row" type="button" value="Delete"><i class="fa fa-trash"></i></button>' +
                    '</td>' +
                    '</tr>'
                );


                $('#prod_name_' + count + '').val(produ_name);
                $('#prod_id_' + count + '').val(produ_id);
                var price_of_d = prod_disc + '-tk';
                $('#prod_disc_' + count + '').val(disc);
                $('#qnty_' + count + '').val(qnty);
                $('#head_code_' + count + '').val(head_code);
                $('#produ_price_' + count + '').val(produ_price);
                $('#produ_head_code_' + count + '').val(produ_head_code);
                $('#total_price_' + count + '').val(total);
                in_total = total + in_total;
                $('#in_total').val(in_total);
                count = count + 1;

                qnty = 0;

                $('#qt').val('');
                $('#head_code').val('');
                $('#total').val('');
                $('#price').val('');
                $('#discount_cal').val('0');
                $('#prod_Select').val(null).trigger('change');

                key++;
           


        }
    }

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

    // Remove Single Row
    $('#addinvoiceItem').on('click', '.delete-row', function () {
        const $this = $(this)
        const t_price = $this.closest("tr").find(".invoice_fields .total_price").val();
        in_total = $('#in_total').val();
        in_total = (in_total - t_price);
        $('#in_total').val(in_total);
        $this.closest('tr').remove();
    })

    function AddNewInvoice() {
        var due_amount = $('#due_amount').val();
        if (!due_amount) {
            swal("", "Please Add Your Due Amount", "error");
            return false;
        } else {
            var content = 'Are you Sure ?';
            var confirmtext = 'Place Requsition';
            var confirmCallback = function () {
                var form = $('#sell_req-add-form');
                var successcallback = function (a) {
                    toastr.success("@lang('warehouse.requ_has_been_added')", "@lang('layout.success')!");
                    location.reload();
                }
                ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
            }
            confirmAlert(confirmCallback, content, confirmtext)
        }
    }

</script>
@endsection
