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
                    <h1 class="m-0 text-dark">@lang('account.credit_voucher')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.credit_voucher')</li>
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
                        <h3 class="card-title">@lang('account.credit_voucher')</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>'accounts.credit_voucher','id'=>'credit_voucher-add-form']) !!}
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('account.vno')</label>
                                <input type="text" class="form-control" name="VNo" id="vno" value="{{$v_no}}" readonly="readonly">
                            </div>
                            <div class="col-lg-3">
                                <label for="VDate">@lang('layout.date')<i class="text-danger">*</i></label>
                                <input type="date" name="VDate" class="form-control" id="VDate">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-4">
                                <label>@lang('account.rec_at')<i class="text-danger"> *</i></label>
                                <select class="custom-select" id="trans_select">
                                    <option selected>@lang('layout.select')</option>
                                    @foreach($cr_heads as $cr_head)
                                        <option value="{{$cr_head->HeadCode}}">{{$cr_head->HeadName}} - {{$cr_head->HeadCode}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('account.amount')<i class="text-danger">*</i></label>
                                <input type="number" readonly id="amount" value="{{$chk_amount}}" class="form-control">
                            </div>
                            <div class="col-lg-2 mt-4">
                                <button class="btn btn-warning" type="button" value="Delete" onclick="addRow(this)"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="table-responsive col-lg-12">
                                <table class="table table-bordered" id="debtAccVoucher">
                                    <thead>
                                        <tr>
                                            <th class="text-center">@lang('account.ac_name')<i class="text-danger">*</i></th>
                                            <th class="text-center">@lang('layout.amount')<i class="text-danger">*</i></th>
                                            <th class="text-center">@lang('layout.action')</th>
                                        </tr>
                                    </thead>
                                    <tbody id="debitvoucher">
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="1" class="text-right"><label for="reason" class="  col-form-label">Total</label>
                                            </td>
                                            <td class="text-right">
                                                <input type="number" id="grandTotal" class="form-control text-right " name="grand_total" value="" readonly="readonly">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pay_from" class="col-sm-2 col-form-label">@lang('account.rec_frm')<i class="text-danger">*</i></label>
                            <div class="col-sm-4">
                                <input type="text" id="payment_from" name="payment_from" class="form-control" readonly value="{{$check->COAID}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="remark" class="col-sm-2 col-form-label">@lang('account.remark')</label>
                            <div class="col-sm-4">
                                <textarea name="remark" class="form-control" id="remark" cols="30" rows="2"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-12 text-right receive">
                                <input type="button" id="add_receive" class="btn btn-success btn-large" name="save" value="Save" tabindex="9">
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
var count = 1;
var transaction_to;
var tran_name;
var amount = 0;
var total = 0;
function addRow(e) {
    $("#debitvoucher").append('<tr><td class="" width="400"><input type="text" readonly id="paid_to_name_' + count + '" class="form-control"><input type="hidden" readonly name="HeadCode[]" id="paid_to_' + count + '" class="form-control"></td><td><input type="number" name="txtAmount[]" value="" class="form-control total_price text-right" id="txtAmount_'+count+'" aria-required="true" readonly></td><td><button class="btn btn-danger text-right" type="button" value="Delete" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button></td></tr>');
    amount = $('#amount').val();
    total = parseInt(total)  + parseInt(amount);
    $('#grandTotal').val(total);
    $('#paid_to_' + count + '').val(transaction_to);
    $('#paid_to_name_' + count + '').val(tran_name);
    $('#txtAmount_' + count + '').val(amount);
    count = count + 1;
    $('#trans_select').val(null).trigger('change');
    $('#amount').val(null);

}

function deleteRow(e) {
    var price_id =$(e).closest("tr").find("input[type=number].total_price ").attr('id');
    var in_p = '#'+price_id;
    var pr = $(in_p).val();
    total = total - pr;
    $('#grandTotal').val(total);
    $(e).closest("tr").remove();
}

$("#trans_select").select2()
    .on("select2:select", function(e) {
        var transaction_element = $(e.currentTarget);
        tran_name = $('#trans_select').find(':selected').text();
        transaction_to = transaction_element.val();
        $('#trans_select').val(transaction_to);
});

$('.receive').on('click', '#add_receive', function(){
    var form=$('#credit_voucher-add-form');
    var successcallback=function(a){
        toastr.success('Credit Voucher Added Successfylly !!');
        location.href = baseUrl+"accounts/checks_in_hand";
    }
    ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
});

</script>
@endsection
