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
                    <h1 class="m-0 text-dark">@lang('account.journal_voucher')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.journal_voucher')</li>
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
                        <h3 class="card-title">@lang('account.journal_voucher')</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        {!!Form::open(['route'=>'accounts.journal_voucher','id'=>'journal_voucher-add-form']) !!}
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
                                <label>@lang('account.account name')<i class="text-danger"> *</i></label>
                                <select class="custom-select" id="trans_select">
                                    <option selected>@lang('layout.select')</option>
                                    @foreach($trans as $cr_head)
                                        <option value="{{$cr_head->HeadCode}}">{{$cr_head->HeadName}} - {{$cr_head->HeadCode}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('account.debit')<i class="text-danger">*</i></label>
                                <input type="number" id="debit" class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-lg-3">
                                <label>@lang('account.credit')<i class="text-danger">*</i></label>
                                <input type="number" id="credit" class="form-control" placeholder="0.00">
                            </div>
                            <div class="col-lg-2 mt-4">
                                <button class="btn btn-success" type="button" value="Delete" onclick="addRow(this)"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="table-responsive col-lg-12">
                                <table class="table table-bordered" id="debtAccVoucher">
                                    <thead>
                                        <tr>
                                            <th class="text-center">@lang('account.ac_name')<i class="text-danger">*</i></th>
                                            <th class="text-center">@lang('account.debit')<i class="text-danger">*</i></th>
                                            <th class="text-center">@lang('account.credit')<i class="text-danger">*</i></th>
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
                                                <input type="number" id="grand_debit" class="form-control text-right " name="grand_debit" value="" readonly="readonly">
                                            </td>
                                            <td class="text-right">
                                                <input type="number" id="grand_credit" class="form-control text-right " name="grand_credit" value="" readonly="readonly">
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
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

var debit_total = 0;
var credit_total = 0;
var debit_val;
var credit_val;
$("#debit").keyup(function(){
  debit_val = $('#debit').val();
  credit_val = 0.00;
  if (debit_val == '') {
    debit_val = parseInt(0);
  }
});
$("#credit").keyup(function(){
  credit_val = $('#credit').val();
  debit_val = 0;
  if (credit_val == '') {
    credit_val = parseInt(0);
  }
});


var count = 1;
var transaction_to;
var tran_name;
var amount = 0;
var total = 0;
function addRow(e) {
    credit_total = parseInt(credit_val)  + parseInt(credit_total);
    debit_total = parseInt(debit_val)  + parseInt(debit_total);
    $("#debitvoucher").append('<tr><td class="" width="400"><input type="text" readonly id="paid_to_name_' + count + '" class="form-control"><input type="hidden" readonly name="HeadCode[]" id="paid_to_' + count + '" class="form-control"></td><td><input type="number" name="debtAmount[]" value="" class="form-control debit_price text-right" id="debtAmount_'+count+'" aria-required="true" readonly></td><td><input type="number" name="creAmount[]" value="" class="form-control credit_price text-right" id="creAmount_'+count+'" aria-required="true" readonly></td><td><button class="btn btn-danger text-right" type="button" value="Delete" onclick="deleteRow(this)"><i class="fa fa-trash"></i></button></td></tr>');
    $('#grand_credit').val(credit_total);
    $('#grand_debit').val(debit_total);
    $('#paid_to_' + count + '').val(transaction_to);
    $('#paid_to_name_' + count + '').val(tran_name);
    $('#debtAmount_' + count + '').val(debit_val);
    $('#creAmount_' + count + '').val(credit_val);
    count = count + 1;
    $('#trans_select').val(null).trigger('change');
    $('#debit').val(null);
    $('#credit').val(null);

}

function deleteRow(e) {
    var debit_price =$(e).closest("tr").find("input[type=number].debit_price ").attr('id');
    var credit_price =$(e).closest("tr").find("input[type=number].credit_price ").attr('id');
    var deb_p = '#'+debit_price;
    var cre_p = '#'+credit_price;
    var pr_d = $(deb_p).val();
    var pr_c = $(cre_p).val();
    debit_total = debit_total - pr_d;
    credit_total = credit_total - pr_c;
    $('#grand_credit').val(credit_total);
    $('#grand_debit').val(debit_total);
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
    /*edit after js lang check*/
    var content = 'Are you Sure ?';
    var confirmtext = 'Insert Journal Voucher';
    var confirmCallback=function(){
        if (debit_total == credit_total) {
            var form=$('#journal_voucher-add-form');
            var successcallback=function(a){
                toastr.success('Journal Voucher Added Successfylly !!');
                location.reload();
            }
            ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
        }else{
            toastr.error('Debit and Credit must be equal !!');
        }
    }
    confirmAlert(confirmCallback,content,confirmtext)
});

</script>
@endsection
