<!-- Main content -->
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Product Request Details</h1>
            </div>
            <!-- /.col -->
            
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Product Request Details</li>
                </ol>
            </div>
            <!-- /.col -->
            
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card_buttons">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h3 class="card-title">Product Request Details</h3>
                        </div>
                        @if( auth('web')->user()->role->id == 9 )
                        <div class="col-md-6 text-right">
                            <a href="{{route('warehouse.upapprove.sell.request',$req->id)  }}" class="btn btn-danger">
                                Unapprove
                            </a>
                        </div>
                        @endif
                    </div>
                    
                </div>
                
                
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6">
                            <label>
                                @lang('customer.customer_name') :
                            </label>
                            <span class="text-bold">{{$cus_name}}</span>
                        </div>
                        <div class="col-lg-3">
                            <label>
                                @lang('layout.date')
                            </label>
                            <span class="text-bold"> : {{$v_date}}</span>
                        </div>
                        <div class="col-lg-3">
                            <label>
                                @lang('warehouse.chalan_no')
                            </label>
                            <span class="text-bold"> : {{$req->voucher_no}}</span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-sm" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field">Item Information</th>
                                        <th class="text-center">@lang('product.bar_code')</th>
                                        <th class="text-center">Head</th>
                                        <th class="text-center">Qnty <i class="text-danger">*</i></th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    @foreach($req_products as $prod)
                                    <tr>
                                        <td>{{ $prod->products->product_name }}</td>
                                        <td class="text-center">{{ $prod->products->product_id }}</td>
                                        <td class="text-center">
                                            @if ($prod->products->head_code)
                                                {{ $prod->products->head_code }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $prod->qnty }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    {!!Form::open(['route'=>'warehouse.deliver','id'=>'sell_req-deliver-form','enctype'=>"multipart/form-data"]) !!}
                    <div class="form-group m-form__group row">
                        
                        <div class="col-lg-3">
                            <label>
                                @lang('product.product')<i class="text-danger">*</i>
                            </label>
                            <select class="form-control" id="product_id">
                                <option selected>@lang('layout.select')</option>
                                @foreach($req_products as $prod)
                                    <option value="{{$prod->products->id }}" @if ($prod->products->head_code)
                                        id = "{{$prod->products->head_code}}"
                                    @endif>{{$prod->products->product_name}}
                                    @if ($prod->products->head_code)
                                        -{{ $prod->products->head_code }}
                                    @endif</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <label>
                                @lang('warehouse.warehouse')<i class="text-danger">*</i>
                            </label>
                            <select class="form-control" id="warehouse_id">
                                <option selected>@lang('layout.select')</option>
                            </select>
                            <input type="hidden" id="req_id" name="request_id" value="{{$req->id}}">
                        </div>
                        <div class="col-lg-2" id="av_qnt_row">
                            <label>
                                @lang('product.av_qnt')
                            </label>
                            <input type="text" class="form-control" readonly id="av_qnt">
                        </div>
                        <div class="col-lg-2">
                            <label>
                                @lang('product.req_qnt')
                            </label>
                            <input type="text" class="form-control" readonly id="req_qnt">
                        </div>
                        <div class="col-lg-2" id="q_sell">
                            <label>
                                @lang('product.qnt_tosell')
                            </label>
                            <input type="number" class="form-control" id="qnt_tosell">
                            <input type="hidden" class="form-control" name="v_date" value="{{$v_date}}">
                            <input type="hidden" class="form-control" name="chalan_no" value="{{$req->voucher_no}}">
                        </div>
                        <div class="col-lg-2 d-none" id="rem_qnt_row">
                            <label>
                                @lang('product.rem_qnt')
                            </label>
                            <input type="text" class="form-control" readonly id="rem_qnt">
                        </div>
                        <div class="col-lg-3 d-none" id="s_combo">
                            <button type="button" class="btn btn-info mt-4">@lang('product.search_combo')</button>
                        </div>

                        <div class="col-lg-1" id="add_c">
                            <label>
                                @lang('layout.add_cart')
                            </label>
                            <button class="btn btn-sm btn-success form-control" type="button" onclick="addRow()"><i class="fa fa-plus"></i></button>
                        </div>
                    </div>
                    <div class="form-group m-form__group row d-none" id="set_products">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field">Set Name</th>
                                        <th class="text-center">Warehouse</th>
                                        <th class="text-center">Avl.Qnty</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="ware_combo">
                                </tbody>
                            </table>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field">Item Information <i class="text-danger">*</i></th>
                                        <th class="text-center">@lang('product.bar_code')<i class="text-danger">*</i></th>
                                        <th class="text-center">Qnty</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="addinvoiceItem">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6">
                            <label>
                                @lang('product.transp_name')
                            </label>
                            <input type="text" class="form-control" name="transp_name">
                        </div>
                        <div class="col-lg-6">
                            <label>
                                @lang('product.deliv_pname')
                            </label>
                            <input type="text" class="form-control" name="deliv_pname">
                        </div>
                        <div class="col-lg-12">
                            <br>
                            <label>
                                Gift Note ( Optional )
                            </label>
                            <input type="text" class="form-control" name="gift">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-12 transaction_submit">
                            <button type="button" id="btn_delivered" class="btn btn-success">@lang('layout.deliver')</button>
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
<script>
    var w_id = '';
    var product_id ='';
    var produ_name ='';
    var bar_code = '';
function approve(id) {
    var req_id = id;
    var successcallback = function(a) {
        toastr.success("@lang('warehouse.product_has_been_added')", "@lang('layout.success')!");
        location.reload();
    }
    var url = baseUrl + "seller/request_approve/" + req_id;
    ajaxGetRequest(url, successcallback);
}
var req_id='';
var av_qnty = '';
var req_qnt = '';

$('#product_id').on('change', function() {
    var head_code;
    $('#rem_qnt_row').hide();
    $('#av_qnt_row').show();
    $('#rem_qnt').val('');
    $('#qnt_tosell').val('');
    var prod_id = this.value;
    
    var h = $('#product_id option:selected').attr('id');
    if(h){
        head_code = h;
    }else{
        head_code = null;
    }

    w_id = $('#warehouse_id').val();
    
    req_id = $('#req_id').val();
    var url = baseUrl+'warehouse/prod_q/'+prod_id+'/'+req_id+'/'+head_code;
    
    $.ajax({
        "type" : "GET",
        "url" : url,
        success: function(data){
            $("#warehouse_id option").remove();
        
            $("#warehouse_id").append(`
                <option selected disabled>Select</option>
            `);
            $.each(data.warehouses, function(key,value){
                $("#warehouse_id").append(`
                <option value="${value.id}">${value.name}</option>
                `);
            })
            $('#av_qnt').val(0);
            $('#req_qnt').val(0);
    
        }
    });
        
    
    if (prod_id.length > 5) {
        getAjaxdata(url,successCallback,'get');
    }
});


$(document).on("change","#warehouse_id",function(){
    
    var head_code;
    $('#rem_qnt_row').hide();
    $('#av_qnt_row').show();
    $('#rem_qnt').val('');
    $('#qnt_tosell').val('');
    
    var prod_id = $('#product_id').val();
    var h = $('#product_id option:selected').attr('id');
    if(h){
        head_code = h;
    }else{
        head_code = null;
    }

    w_id = $('#warehouse_id').val();
    var url = baseUrl+'warehouse/prod_q/'+prod_id+'/'+w_id+'/'+req_id+'/'+head_code;
    
    if(w_id != 'Select' ){
        $.ajax({
            "type" : "GET",
            "url" : url,
            success: function(data){
                $('#av_qnt').val(data.stck_q);
                $('#req_qnt').val(data.req_qnt);
                if (data.stck_q == 0) {
                    $('#add_c').hide();
                    $('#q_sell').hide();
                    $('#s_combo').removeClass('d-none');
                    $('#set_products').show();
                }else{
                    $('#add_c').show();
                    $('#q_sell').show();
                    $('#set_products').hide();
                    $('#s_combo').addClass('d-none');
                }
                produ_name = data.prod_name;
                product_id = data.prod_id;
                bar_code = data.bar_code;
                av_qnty = data.stck_q;
                req_qnt = data.req_qnt;
        
            }
        });
    }
    else{
        alert("Please Select Warehouse")
    }
    
    
    if (prod_id.length > 5) {
        getAjaxdata(url,successCallback,'get');
    }
})


var count = 1;
var out_q;
var com_out;
var ware_combo_id = '';
var combo_prod_id = '';
var rem_q;
var productIdArray = [];

function addRow(e) {

    var req_qnt = $("#req_qnt").val()
    var qnt_tosell = $("#qnt_tosell").val()


    var pid = $('#product_id option:selected').text();
    if(productIdArray.includes(pid)){
        swal("", "Product Already Exists", "warning");
        return false;
    }else{
        productIdArray.push(pid);
    }

    $("#addinvoiceItem").append('<tr>'+
        '<td class="product_field">'+
            '<input type="text" id="prod_name_' + count + '" value="" readonly class="form-control produ_name">'+
            '<input type="hidden" id="prod_id_' + count + '" name=product_id[] readonly class="form-control prod_id">'+
        '</td>'+
        '<td>'+
            '<input type="text" readonly id="bar_code_' + count + '" class="form-control bar_code">'+
        '</td>'+
        '<td>'+
            '<input type="text" name="out_qnt[]" readonly id="out_qnty_' + count + '" class="form-control">'+
            '<input type="hidden" name="warehouse_id[]" id="warehouse_id_' + count + '" class="form-control">'+
        '</td>'+
        '<td>'+
            '<button class="btn btn-danger text-right" type="button" value="Delete" onclick="deleteRow(this)">'+
                '<i class="fa fa-trash"></i>'+
            '</button>'+
        '</td>'+
        '</tr>');
    
    $('#prod_name_' + count + '').val(produ_name);
    $('#prod_id_' + count + '').val(product_id);
    $('#bar_code_' + count + '').val(bar_code);
    $('#warehouse_id_' + count + '').val(w_id);
    out_q = $('#qnt_tosell').val();
    $('#out_qnty_' + count + '').val(out_q);
    count = count + 1;
    if (out_q<req_qnt) {
        $('#rem_qnt_row').show();
        var rem = req_qnt-out_q;
        $('#add_c').hide();
        $('#q_sell').hide();
        $('#av_qnt_row').hide();
        $('#rem_qnt_row').removeClass('d-none');
        $('#rem_qnt').val(rem);
        $('#s_combo').removeClass('d-none');
        $('#set_products').show();
    }else{
        $('#add_c').show();
        $('#q_sell').show();
        $('#set_products').hide();
        $('#s_combo').addClass('d-none');
    }
    
    
    
}

// function AddNewInvoice() {
//     var form = $('#sell_req-deliver-form');
//     var successcallback = function (a) {
//         console.log(a.data)
//         swal({
//             title: 'Do you want to Print the Chalan Testtt'+a.data+' ?',
//             text: '',
//             type: "warning",
//             showCancelButton: true,
//             confirmButtonColor: "#28A745",
//             confirmButtonText: "Print",
//             cancelButtonText: "Cancel",
//             closeOnConfirm: true,
//             closeOnCancel: false
//         },
//         function (isConfirm) {
//             console.log('isConfirm Function Called')
//             if (isConfirm) {
//                 console.log('Enter If Block');
//                 var url = baseUrl+"warehouse/print_chalan/"+a.data;
//                 swal("Chalan Successfully Printed", "success");
//                 location.href = url;
//             } else {
//                 console.log('Enter Else Block');
//                 location.reload();

//             }
//         }) 
//     }
//     ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
// }

$('#btn_delivered').on('click', function(){
    console.log('clicked');
    var form = $('#sell_req-deliver-form');
    var successcallback = function (a) {
        console.log(a.data)
        swal({
            title: 'Do you want to Print the Chalan '+a.data+' ?',
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
                swal("Chalan Successfully Printed", "success");
                location.href = url;
            } else {
                location.reload();

            }
        }) 
    }
    ajaxValidationFormSubmit(form.attr('action'), form.serialize(), '', successcallback);
})

function deleteRow(e) {
    let $this = $(this);
    pid = $(e).closest('tr').find('.produ_name').val();
    var pidIndex = productIdArray.indexOf(pid);
    productIdArray.splice(pidIndex,1);
    $(e).closest("tr").remove();
}

$('#s_combo').on('click', function(){
    var successcallback = function (a) {
        if (a.length < 1) {
            alert('This Product Is not in Any Combo !!')
        }
        $('#set_products').removeClass('d-none');
        $.each( a, function( i, val ) {
            $("#ware_combo").append('<tr><td class="product_field"><span>'+val.products[0].product_name+'</span></td><td><span>'+val.warehouse[0].name+'</span></td><td><span>'+(val.stck_q-val.sell_q)+'</span></td><td><button class="btn btn-success text-right" type="button" value="Select" id="selected_combo_'+i+'" onclick="selRow(this)"><i class="fa fa-check"></i></button><input type="hidden" class="warehouse_id" value='+val.warehouse[0].id+'><input type="hidden" class="combo_id" value='+val.products[0].id+'></td></tr>');
        });
    }
    url = baseUrl+'warehouse/combo_prod/'+bar_code+'/'+w_id;
    getAjaxdata(url,successcallback,'get');
});

$('#qnt_tosell').on('change keyup paste',function() {
    var qs = this.value;
    if (qs > av_qnty) {
        alert('You Can Not Sell Above Available Quantity');
        $('#qnt_tosell').val('');
    }
});

function selRow(e) {
    ware_combo_id = $(e).closest("td").find(".warehouse_id").val();
    combo_prod_id = $(e).closest("td").find(".combo_id").val();
    var id = $(e).attr("id");
    $('#'+id+'').hide();
    $(e).closest('td').append('<input placeholder="Quantity to Sell" onchange="com_qtosell(this)" type="text" style="width:50%;display:inline;" class="form-control" id="qnt_to_s"><button style="width:10%;margin-left:2%;" class="btn btn-sm btn-success form-control" type="button" onclick="addComRow(this)"><i class="fa fa-plus"></i></button>');
}

function addComRow(e) {
    $("#addinvoiceItem").append('<tr><td class="product_field"><input type="text" id="pro_name_' + count + '" value="" readonly class="form-control"><input type="hidden" id="outprod_id_' + count + '" name=com_outproduct_id[] readonly class="form-control"></td><td><input type="text" readonly id="bar_code_' + count + '" class="form-control"></td><td><input type="text" name="com_out_qnt[]" readonly id="out_qnty_' + count + '" class="form-control"><input type="hidden" name="com_warehouse_id[]" id="combowarehouse_id_' + count + '" class="form-control"><input type="hidden" name="com_id[]" id="com_id_' + count + '" class="form-control"></td><td><button class="btn btn-danger text-right" type="button" value="Delete" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button></td></tr>');
    $('#rem_qnt_row').addClass('d-none');
    rem_q = 0;
    $('#pro_name_' + count + '').val(produ_name);
    $('#outprod_id_' + count + '').val(product_id);
    $('#bar_code_' + count + '').val(bar_code);
    $('#combowarehouse_id_' + count + '').val(ware_combo_id);
    $('#com_id_' + count + '').val(combo_prod_id);
    out_q = com_out;
    $('#out_qnty_' + count + '').val(out_q);
    count = count + 1;
}

function com_qtosell(e) {
    com_out = e.value;
}

</script>
