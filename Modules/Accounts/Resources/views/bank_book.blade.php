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
                    <h1 class="m-0 text-dark">@lang('account.bank_book')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.bank_book')</li>
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
                    <div class="card-header card_buttons row">
                        <div class="row" style="width: 100%">
                                <div class="col-md-4 mb-2">
                                    <label class="d-block text-left">Select Bank</label>
                                    <select class="form-control" id="cash_type" onchange="cashType()">
                                        <option selected disabled>Please Select Bank</option>
                                        @foreach($bank_type as $type)
                                            <option value="{{ $type->HeadCode }}">{{ $type->HeadName }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="from" class="d-block text-left">@lang('layout.from') : </label>
                                    <input type="date" class="form-control mr-sm-2" id="from" name="from">
                                </div>
                                <div class="col-md-3">
                                    <label for="to" class="d-block text-left">@lang('layout.to') : </label>
                                    <input type="date" class="form-control mr-sm-2" id="to" name="to">
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-success mt-4"
                                        onclick="searchByDate()">@lang('layout.search')</button>
                                    <button type="submit" class="btn btn-primary mt-4" onclick="printPdf()"
                                        id="print">@lang('layout.print')</button>
                                </div>
                            </div>
                    </div>
                    <!-- /.card-header -->
                    <div id="print_pdf">
                        <div class="card-header card_buttons row">
                            <div class="col-md-3">
                                <img src="{{asset('img/zamanit.png')}}" class="img-fluid mt-4" alt="Company Logo">
                            </div>
                            <div class="col-md-6 text-center">
                                <h3>{{$company_info->name}}</h3>
                                <span>{{$company_info->address}}</span><br>
                                <span>{{$company_info->phone_code}}{{$company_info->phone_no}}</span>
                            </div>
                            <div class="col-md-3 text-center">
                                <p class="mt-4">@lang('layout.date'): {{ \Carbon\Carbon::now()->toDateString() }}
                                </p>
                            </div>
                        </div>

                        <div class="card-body" id="ledger_view">
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="text-center" id="gl_title"></h5>
                                </div>
                                <div class="col-md-12" id="balance_block">
                                    <p class="text-right " style="margin-bottom: 0;">Previous Balance : <span id="prev-balance"></span></p>
                                    <p class="text-right " >Current Balance : <span id="cur-balance"></span></p>
                                </div>
                            </div>


                            {{-- Select Cash Account Name --}}
                            <table class="table table-sm table-bordered table-striped" >
                                <thead>
                                    <th>Transaction Date</th>
                                    <th>Narration</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </thead>

                                <tbody id="cash_book">
                                    
                                </tbody>
                                <tfoot id="cash_book_total">
                                    
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content --> 
@endsection
@section('js')

<script type="text/javascript" src="{{ asset('js/html2pdf.min.js') }}"></script>
<script type="text/javascript">
    {{-- Global HeadCode Variable --}}
    var HeadCode = '';
    function refresh(){
        location.reload();
    }


    function printPdf() {
        const invoice = document.getElementById("print_pdf")
        var opt = {
                margin: 0,
                filename: 'BankBook.pdf',
                image: { type: 'jpeg' , quality : 0.98 },
                html2canvas : { scale : 1 },
                jsPDF : { unit : 'in' , format : 'letter' , orientation : 'portrait' }
        }
        html2pdf().from(invoice).set(opt).save()
    }

    $("#print").hide()

    $("#balance_block").hide()

    function cashType(){
        HeadCode = $('#cash_type').val();
       $.ajax({
            type: 'GET',
            url: "{{route('account.BankBookFilter')}}",
            data: {HeadCode : HeadCode},
            success: function(data){
                $('#cash_book tr').remove();
                $("#cash_book_total tr").remove();
                $("#balance_block").show()
                let total_balance = 0;

                $("#gl_title").html(`
                        Bank of ( ${data.head_name} - ${data.HeadCode} ) - ( All )
                        `);
                $("#print").show()
                $("#prev-balance").html(`${data.balance[0]} BDT`)

                $.each(data.cash_books, (key, value) =>{
                    
                    $('#cash_book').append(`
                        <tr>
                            <td>${value.VDate}</td>
                            <td>${value.Narration}</td>
                            <td>${value.Debit }</td>
                            <td>${value.Credit }</td>
                            <td>
                                ${data.balance[key]}
                            </td>
                        </tr>
                    `);
                    total_balance = data.balance[key]
                })
                $("#cash_book_total").append(`
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>${data.total_debit}</strong></td>
                        <td><strong>${data.total_credit}</strong></td>
                        <td><strong>${total_balance}</strong></td>
                    </tr>
                `);
                $("#cur-balance").html(`${total_balance} BDT`)
            }
       }); 
    }

    function searchByDate(){
        if($('#cash_type').val() == null){
            alert('Please Select Bank Name');
        }else{
            var from = $('#from').val();
        var to = $('#to').val();

        $.ajax({
            type: 'GET',
            url: "{{route('account.BankBookFilter')}}",
            data: {HeadCode : HeadCode, from : from, to : to},
            success: function(data){
                $('#cash_book tr').remove();
                $("#cash_book_total tr").remove();
                $("#balance_block").show()
                let total_balance = 0;

                if( data.balance.length > 0 ){
                    $("#prev-balance").html(`${data.balance[0]} BDT`)
                }else{
                    $("#prev-balance").html(0 + ` BDT`)
                }
                $("#gl_title").html(`
                        Bank Book of ( ${data.head_name} - ${data.HeadCode} ) - (On ${data.from} To ${data.to} )
                        `);

                $.each(data.cash_books, (key, value) =>{
                    
                    $('#cash_book').append(`
                        <tr>
                            <td>${value.VDate}</td>
                            <td>${value.Narration}</td>
                            <td>${value.Debit }</td>
                            <td>${value.Credit }</td>
                            <td>
                                ${data.balance[key]}
                            </td>
                        </tr>
                    `);
                    total_balance = data.balance[key]
                })
                $("#cash_book_total").append(`
                    <tr>
                        <td></td>
                        <td></td>
                        <td><strong>${data.total_debit}</strong></td>
                        <td><strong>${data.total_credit}</strong></td>
                        <td><strong>${total_balance}</strong></td>
                    </tr>
                `);
                $("#cur-balance").html(`${total_balance} BDT`)
            }
       }); 

        }        
    }
</script>

@endsection
