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
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
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
                        <h3 class="card-title">Change Your Password Here</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="{{ route('seller.change.password', $id) }}" method="post">
                            @csrf
                            
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            
                            @if( session()->has('error') )
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ session()->get('error') }}</li>
                                    </ul>
                                </div>
                            @endif
                            
                            @if( session()->has('success') )
                                <div class="alert alert-success">
                                    <ul>
                                        <li>{{ session()->get('success') }}</li>
                                    </ul>
                                </div>
                            @endif
                            
                            <div class="form-group">
                                <label>Old Password</label>
                                <input type="password" name="old_password"class="form-control"  required>
                            </div>
                            
                            <div class="form-group">
                                <label>New Password</label>
                                <input type="password" name="password"class="form-control"  required>
                            </div>
                            
                            <div class="form-group">
                                <label>Re-Type New Password</label>
                                <input type="password" name="password_confirmation"class="form-control"  required>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Change Password</button>
                            </div>
                            
                        </form>
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

    function addRow(e) {
        if (qnty == null || qnty == 0) {
            swal("", "Please add Product quantity", "error");
            return false;

        } else {
            $("#addinvoiceItem").append('<tr>'+
                '<td class="product_field">'+
                    '<input type="text" id="prod_name_' + count +'" value="" readonly class="form-control form-control-sm frm-control">'+
                    '<input type="hidden" id="prod_id_' + count +'" name=product_id[] readonly class="form-control form-control-sm frm-control">'+
                '</td>'+
                '<td>'+
                    // '<input type="text" name="head_code[]" readonly id="head_code_' + count + '" class="form-control form-control-sm frm-control">'+
                    '<input type="text" id="produ_head_code_'+ count +'" readonly class="form-control form-control-sm">'+
                    '</td>'+
                '<td>'+
                    '<input type="text" name="qnty[]" readonly id="qnty_' + count + '" class="form-control form-control-sm frm-control">'+
                '</td>'+
                '<td class="invoice_fields">'+
                    '<input type="text" id="produ_price_' + count + '" readonly class="form-control form-control-sm frm-control">'+
                '</td>'+
                '<td class="invoice_fields">'+
                    '<input class="form-control form-control-sm frm-control text-right" type="text" id="prod_disc_' + count +'" name="prod_disc[]" readonly="readonly">'+
                '</td>'+
                '<td class="invoice_fields">'+
                    '<input class="form-control form-control-sm frm-control text-right total_price" type="text" id="total_price_' + count +'" readonly="readonly">'+
                '</td>'+
                '<td>'+
                    '<button class="btn btn-danger text-right delete-row" type="button" value="Delete"><i class="fa fa-trash"></i></button>'+
                '</td>'+
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
    $('#addinvoiceItem').on('click', '.delete-row', function(){
        const $this = $(this)
        const t_price = $this.closest("tr").find(".invoice_fields .total_price").val();
        in_total = $('#in_total').val();
        in_total = (in_total - t_price);
        $('#in_total').val(in_total);
        $this.closest('tr').remove();
    })

    function AddNewInvoice() {
        var due_amount = $('#due_amount').val();
        if(!due_amount){
            swal("", "Please Add Your Due Amount", "error");
            return false;
        }else{
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
