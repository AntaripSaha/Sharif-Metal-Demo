<!-- Main content -->

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Sale Requisition Details</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Sale Requisition Details</li>
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
                    @if(auth('web')->user()->role_id == 18 || auth('web')->user()->role_id == 9 || auth('web')->user()->id == 48)
                    <h3 class="card-title"> <a 
                    @if($cus_id->is_rejected == false)
                    href="{{ route('seller.index') }}"
                    @else
                    href="{{ route('seller.rejected.sales') }}"
                    @endif
                    > <button
                                class="btn btn-warning btn-sm"><i class="fas fa-arrow-left"></i></button></a> <span
                            class="ml-2"> Requisition Details </span></h3>

                    <!-- Print Button Start -->
                    <div class="float-right">
                        <a href="{{ route('seller.sale_request_print', $req_id) }}">
                            <button class="btn btn-info btn-sm" id="print_sale_req_details">
                                <i class="fas fa-print"></i> <span class="ml-1">Print</span>
                            </button>
                        </a>
                    </div>
                    <!-- Print Button End -->

                    <!-- Export Excel Button Start -->
                    <div class="float-right mr-2">
                        <a href="{{ route('sale_req_details_export_excel', $req_id) }}">
                            <button class="btn btn-success btn-sm" id="print_sale_req_details">
                                <i class="fas fa-print"></i> <span class="ml-1">Export Excel</span>
                            </button>
                        </a>
                    </div>
                    <!-- Export Excel Button End -->
                        @endif
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <!-- {!!Form::open(['route'=>'seller.add_sell','id'=>'sell_req-add-form','enctype'=>"multipart/form-data"]) !!} -->
                    <div class="form-group m-form__group row">
                        <div class="col-lg-3">
                            <label>
                                Customer :
                            </label>
                            <span class="text-bold">{{$cus_name}}</span>
                            {{-- Req id hidden value --}}
                            <input type="hidden" id="request_id" value="{{ $req_id }}">
                        </div>
                        <div class="col-lg-3">
                            <label>
                                Seller :
                            </label>
                            <span class="text-bold">{{$seller_name}}</span>
                        </div>
                        <div class="col-lg-3">
                            <label>
                                @lang('layout.date')
                            </label>
                            <span class="text-bold"> : {{$v_date}}</span>
                        </div>
                        <div class="col-lg-3">

                            <div class="row">
                                <div class="col-sm-3">
                                    <label>Due :</label>
                                </div>

                                <div class="col-sm-6">
                                    @if ($cus_id->due_amount)
                                    <input type="text" name="due_amount" id="due_amount"
                                        value="{{ $cus_id->due_amount }}" class="form-control form-control-sm">
                                    @else
                                    <input type="text" name="due_amount" id="due_amount"
                                        class="form-control form-control-sm">
                                    @endif
                                    <!-- Print Hidden Sale Requision ID -->
                                    <input type="hidden" id="sale_requisition_id" value="{{ $sale_requisition_id }}">
                                </div>
                                @if(auth('web')->user()->role_id == 9 || auth('web')->user()->id == 48)
                                <div class="col-sm-1">
                                    <button class="btn btn-dark btn-sm" id="due_amount_save">Save</button>
                                </div>
                                    @endif
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover" id="normalinvoice">
                                <thead>
                                    <tr>
                                        <th class="text-center product_field">Item Information <i
                                                class="text-danger">*</i></th>
                                        <th class="text-center">Head</th>
                                        <th class="text-center">Qnty <i class="text-danger">*</i></th>
                                        <th class="text-center">Rate <i class="text-danger">*</i></th>
                                        <th class="text-center">Total</th>
                                        <th class="text-center">Prod. Discount(%)</th>
                                        <th class="text-center">Grand Total</th>
                                    </tr>
                                </thead>
                                <tbody id="">
                                    <?php
                                        $prd_disc = 0;
                                        $in_total_amount = 0;
                                    ?>

                                    @foreach($req_products as $prod)
                                    <tr>
                                        <td>{{ $prod->products->product_name }}</td>
                                        <td class="text-center">
                                            @if ($prod->products->head_code)
                                                {{ $prod->products->head_code }}
                                            @else
                                                 -
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $prod->qnty }}</td>
                                        <td>{{ $prod->products->price }}</td>
                                        <td>{{ $prod->qnty * $prod->products->price }}</td>
                                        <td>{{$prod->prod_disc}}</td>
                                        <td>
                                            @if($prod->prod_disc == null || $prod->prod_disc == 0)
                                            {{-- {{$prod->qnty * $prod->products->price}} --}}
                                            @php
                                            $prod_new_price = $prod->qnty * $prod->products->price;
                                            @endphp
                                            <span>{{  $prod_new_price}}</span>
                                            @else
                                            {{-- <?php $prd_disc = $prod->qnty * $prod->products->price*($prod->prod_disc/100) ?> --}}
                                            {{-- {{($prod->qnty * $prod->products->price) - $prd_disc}} --}}
                                            @php
                                            $prod_new_price = ($prod->qnty * $prod->products->price) - ((($prod->qnty *
                                            $prod->products->price) * $prod->prod_disc) / 100);
                                            @endphp
                                            <span>{{ $prod_new_price }}</span>
                                            @endif
                                        </td>
                                        @php
                                        $in_total_amount += $prod_new_price;
                                        @endphp
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" rowspan="2"><span><b>Transport Name :</b></span><textarea
                                                readonly class="form-control" cols="5"
                                                rows="2">{{$cus_id->transp_name}}</textarea></td>
                                        <td class="text-right"><b>Total Amount:</b></td>
                                        <td class="text-left">
                                            <input type="text" class="form-control" id="in_total_amount" readonly
                                                value="{{ $in_total_amount }}">
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-right"><b>Sale Discount(%):</b></td>
                                        <td class="text-right">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    @php
                                                    $sale_disc = $cus_id->sale_disc;

                                                    if($cus_id->sale_discount_overwrite != null){
                                                    $sale_disc = $cus_id->sale_discount_overwrite;
                                                    }else{
                                                    if($sale_disc == null){
                                                    $sale_disc = 0;
                                                    }else{
                                                    $sale_disc = $cus_id->sale_disc;
                                                    }
                                                    }
                                                    @endphp
                                                    <input type="text" id="sale_discount" class="form-control text-left"
                                                        name="sale_discount_overwrite" readonly
                                                        value="{{ $sale_disc }}">
                                                </div>
                                                @if ($userType == 1)
                                                <div class="col-sm-6">
                                                    <button class="btn btn-warning btn-sm float-left"
                                                        id="btn_change_discount">Change Discount</button>
                                                    <button class="btn btn-success btn-sm float-left"
                                                        id="btn_change_save">Save Discount</button>

                                                </div>
                                                @else

                                                @endif
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="4" rowspan="2"><span><b>Remarks :</b></span><textarea readonly
                                                class="form-control" cols="5" rows="2">{{$remarks}}</textarea></td>
                                        <td class="text-right"><b>Total Discount:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="total_discount" class="form-control text-left"
                                                readonly value="{{ $cus_id->discount }}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-right"><b>Grand Total:</b></td>
                                        <td class="text-right">
                                            <input type="text" id="grand_total" class="form-control text-left" readonly
                                                value="{{ $total_amount }}">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        
                        
                        @if(auth('web')->user()->role_id == 18 || auth('web')->user()->role_id == 9 || auth('web')->user()->id == 48)
                        
                            @if($cus_id->is_rejected == false)
                                <div class="approve_req">
                                    @if($is_approved == 0)
                                    <button class="btn btn-success text-right request_submit"
                                        id="request_id-{{$req_id}}">Approve</button>
                                    @endif
                                    
                                </div>
        
                                <!-- Edit Button -->
                                <div class="edit_req">
                                    <a href="{{ route('edit_requisition_details', $req_id) }}">
                                    <button class="ml-3 btn btn-primary text-right request_edit" id="id-{{$req_id}}">Edit
                                        Details</button>
                                    </a>
                                    
                                </div>
                            @endif
                            
                            @if($cus_id->is_rejected == false)
                            <div class="reject_req ml-3">
                                <button class="btn btn-danger text-right request_submit"
                                    id="request_id-{{$req_id}}">Reject</button>
                            </div>
                            @else
                            <div class="cancel_reject_req ml-3">
                                <button class="btn btn-success text-right request_submit"
                                    id="request_id-{{$req_id}}">Cancel Rejection</button>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
                <!-- {!! Form::close() !!} -->
            </div>
        </div>
    </div>
</div>
</div>
<script>
    // Some Global Variable
    var dis_amount = 0;
    var new_grand_total = 0;

    $('#btn_change_save').hide();
    
    //approve request
    $('.approve_req').on('click', '.request_submit', function () {
        var due_amount = $('#due_amount').val();
        if (due_amount) {
            "use strict";
            var req_id = this.id.replace('request_id-', '');
            var content = 'Approve Sale Rquisition ?';
            var confirmtext = 'Approve';
            var confirmCallback = function () {
                var successcallback = function (a) {
                    toastr.success("@lang('warehouse.req_approved')", "@lang('layout.success')!");
                    location.reload();
                }
                var url = baseUrl + "seller/request_approve/" + req_id;
                ajaxGetRequest(url, successcallback);
            }
            confirmAlert(confirmCallback, content, confirmtext)
        } else {
            swal("", "Please Add Due Amount", "error");
            return false;
        }
    });
    
    //reject request
    $('.reject_req').on('click', '.request_submit', function () {
        
        "use strict";
        var req_id = this.id.replace('request_id-', '');
        var content = 'Reject Sale Rquisition ?';
        var confirmtext = 'Reject';
        var confirmCallback = function () {
            var successcallback = function (a) {
                toastr.success("Request Rejected", "@lang('layout.success')!");
                location.reload();
            }
            var url = baseUrl + "seller/request_reject/" + req_id;
            ajaxGetRequest(url, successcallback);
        }
        confirmAlert(confirmCallback, content, confirmtext)
        
    });
    
    
    //cancel reject request
    $('.cancel_reject_req').on('click', '.request_submit', function () {
        
        "use strict";
        var req_id = this.id.replace('request_id-', '');
        var content = 'Cancel Rejection Sale Rquisition ?';
        var confirmtext = 'Reject';
        var confirmCallback = function () {
            var successcallback = function (a) {
                toastr.success("Request Rejected Cancel", "@lang('layout.success')!");
                location.reload();
            }
            var url = baseUrl + "seller/cancel_request_reject/" + req_id;
            ajaxGetRequest(url, successcallback);
        }
        confirmAlert(confirmCallback, content, confirmtext)
        
    });

    // save discount btn show start
    $('#btn_change_discount').on('click', function () {
        $("#sale_discount").attr("readonly", false);
        $('#btn_change_discount').hide();
        $('#btn_change_save').show();
    })
    // save discount btn show ends
    
    
    // Calculate New Sale Discount
    $('#sale_discount').on('keyup', function () {
        var in_total_amount = $('#in_total_amount').val();
        var new_sale_discount = $('#sale_discount').val();
        dis_amount = (in_total_amount * new_sale_discount) / 100;
        new_grand_total = in_total_amount - dis_amount;

        $('#total_discount').val(dis_amount);
        $('#grand_total').val(new_grand_total);
    })


    $('#btn_change_save').on('click', function () {
        var new_sale_discount = $('#sale_discount').val();
        var req_id = $('#request_id').val();
        // Ajax Submit for Update Sale Discount
        $.ajax({
            url: "{{ route('updateSaleDiscount') }}",
            method: 'GET',
            data: {
                new_sale_discount: new_sale_discount,
                id: req_id,
                amount: new_grand_total,
                discount: dis_amount

            },
            success: function (data) {
                if (data.status == 'success') {
                    swal("", data.message, "success");
                    $("#sale_discount").attr("readonly", true);
                    $('#btn_change_discount').show();
                    $('#btn_change_save').hide();
                }
            }
        })
    });

    //Save Due Amount Start
    $('#due_amount_save').on('click', function () {
        var due_amount = $('#due_amount').val();
        var sale_requisition_id = $('#sale_requisition_id').val();

        $.ajax({
            url: "{{ route('updateDueAmount') }}",
            method: 'GET',
            data: {
                due_amount: due_amount,
                id: sale_requisition_id
            },
            success: function (data) {
                if (data.status == 'success') {
                    swal("", data.message, "success");
                }
            }
        })
    })
    //Save Due Amount End

    //Check Due amount before Print Sale Request Start
    $('#print_sale_req_details').on('click', function () {
        var due_amount = $('#due_amount').val();
        if (!due_amount) {
            swal("", "Please Add Due Amount & Save", "error");
            return false;
        }
    })
    //Check Due Amount before Print Sale Request End

</script>
