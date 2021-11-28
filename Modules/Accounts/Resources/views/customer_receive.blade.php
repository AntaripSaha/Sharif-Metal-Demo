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
                    <h1 class="m-0 text-dark">Customer Receive</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Customer Receive</li>
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
                        <h3 class="card-title">Customer Receive</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>'accounts.customer_receive','id'=>'customer_receive-add-form']) !!}
                        <div class="form-group row">
                            <div class="col-sm-4">
                                <label for="VDate">@lang('layout.date')<i class="text-danger">*</i></label>
                                <input type="date" name="VDate" class="form-control" id="VDate">
                            </div>
                            <div class="col-sm-4">
                                <label for="company">@lang('company.company')<i class="text-danger">*</i></label>
                                <select class="custom-select" name="company_id">
                                    @foreach($companies as $company)
                                        <option value="{{$company->id}}">{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive col-sm-8">
                            <table class="table table-bordered" id="debtAccVoucher">
                                <thead>
                                    <tr>
                                        <th class="text-center">@lang('customer.customer_name')<i class="text-danger">*</i></th>
                                        <th class="text-center">@lang('layout.amount')<i class="text-danger">*</i></th>
                                    </tr>
                                </thead>
                                <tbody id="debitvoucher">
                                    <tr>
                                        <td width="330px;" >
                                            <select class="custom-select customer_select" name="customer_id" id="customer_Select">
                                                <option selected>@lang('layout.select')</option>
                                                @foreach($customers as $customer)
                                                <option value="{{$customer->id}}">{{$customer->customer_id}} - {{$customer->customer_name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="hidden" name="txtCode" value="" class="form-control " id="txtCode_1">
                                            <input type="number" name="txtAmount" value="" class="form-control total_price text-right" id="txtAmount_1" onkeyup="CustomerRcvcalculation(1)" aria-required="true">
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class="text-right"><label for="reason" class="  col-form-label">Total</label>
                                        </td>
                                        <td>
                                            <input type="text" id="grandTotal" class="form-control" name="grand_total" value="" readonly="readonly">
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-2">
                                <label for="paytype">@lang('layout.paytype')<i class="text-danger">*</i></label>
                                <select class="custom-select payment_select" name="paytype">
                                    <option selected value="1">@lang('account.cash_payment')</option>
                                    <option value="2">@lang('account.bank_payment')</option>
                                    <option value="3">@lang('account.check_payment')</option>
                                </select>
                            </div>
                            <div class="col-sm-4 d-none" id="bank_payment">
                                <label for="bank">@lang('layout.bank')<i class="text-danger">*</i></label>
                                <select class="custom-select bank_select" name="bank_id" id="bank_select">
                                </select>
                            </div>
                            <div class="col-sm-8 row d-none" id="check_payment">
                                <div class="col-sm-3">
                                    <label for="check">@lang('account.check_no')<i class="text-danger">*</i></label>
                                    <input type="text" name="check_no" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label for="check">@lang('account.bank_name')<i class="text-danger">*</i></label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>
                                <div class="col-sm-3">
                                    <label for="maturity">@lang('account.mat_date')<i class="text-danger">*</i></label>
                                    <input type="date" name="mat_date" class="form-control">
                                </div>                                
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="remark" class="col-sm-2 col-form-label">@lang('account.remark')</label>
                            <div class="col-sm-6">
                                <textarea name="remark" class="form-control" id="remark" cols="30" rows="2"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12 text-center receive">
                                <input type="button" id="add_receive" class="btn btn-success btn-large" name="save" value="Save" tabindex="10">
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
<script>
document.getElementById('VDate').value = moment().format('YYYY-MM-DD');
$(".payment_select").change(function() {
    var value = $(this).children("option:selected").val();
    if (value == 2) {
        $("#bank_payment").removeClass("d-none");
        var successcallback = function(a) {
            console.log(a)
            $('#bank_select').empty();
            $.each(a, function(i, val) {
                $('#bank_select').append('<option value=' + val.bank_id + '>' + val.bank_name + ' - '+ val.account_name +'</option>');
            });
        };
        var url = baseUrl + 'accounts/get_bank';
        getAjaxdata(url, successcallback);
    } else {
        $("#bank_payment").addClass("d-none");
    }
    if (value == 3) {
        $("#check_payment").removeClass("d-none");
    }else{
        $("#check_payment").addClass("d-none");
    }
});

$("#customer_Select").select2()
    .on("select2:select", function(e) {
        var sel_element = $(e.currentTarget);
        var cus_val = sel_element.val();
        $('#customer_Select').val(cus_val);
        var successcallback = function(a) {
            $('#txtCode_1').val(a);
        };
        var url = baseUrl + 'accounts/get_accode/'+cus_val;
        getAjaxdata(url, successcallback);  
    });

function CustomerRcvcalculation(sl) {
       
        var gr_tot = 0;
        $(".total_price").each(function() {
            isNaN(this.value) || 0 == this.value.length || (gr_tot += parseFloat(this.value))
        });

        $("#grandTotal").val(gr_tot.toFixed(2,2));
}

$('.receive').on('click', '#add_receive', function(){

    /*edit after js lang check*/
    var content = 'Are you Sure ?';
    var confirmtext = ' Insert Reeceive Voucher';
    var confirmCallback=function(){
        var form=$('#customer_receive-add-form');
        var successcallback=function(a){
            toastr.success('Payment Received  Successfylly !!');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
        }
    confirmAlert(confirmCallback,content,confirmtext)
});

</script>
@endsection
