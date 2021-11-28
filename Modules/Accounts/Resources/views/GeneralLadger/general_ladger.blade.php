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
                    <h1 class="m-0 text-dark">@lang('account.gl')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.gl')</li>
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
                        {{-- <h2 class="card-title col-4 mt-2">Search By Date</h2> --}}
                        <div class="form-inline card_buttons col-md-12">
                            {{-- Select Cash Account Name --}}
                            <div class="row" style="width: 100%">
                                <div class="col-md-4 mb-2">
                                    <label class="d-block text-left">Select Account Head</label>
                                    <select class="custom-select" id="IsGL">
                                        <option selected>@lang('layout.select')</option>
                                        @foreach($IsGL as $gl)
                                        <option value="{{$gl->HeadCode}}">{{$gl->HeadName}} - {{$gl->HeadCode}}</option>
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

                            <div class="row">
                                <div class="col-md-12 table-responsive">
                                    <table class="table table-sm table-bordered table-striped">
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
        </div>
    </div>
</section>
<!-- /.content -->
@endsection
@section('js')

<script type="text/javascript" src="{{ asset('js/html2pdf.min.js') }}"></script>

<script type="text/javascript">
    //print pdf
    function printPdf() {
        const invoice = document.getElementById("print_pdf")
        var opt = {
                margin: 0,
                filename: 'GeneralLedger.pdf',
                image: { type: 'jpeg' , quality : 0.98 },
                html2canvas : { scale : 1 },
                jsPDF : { unit : 'in' , format : 'letter' , orientation : 'portrait' }
        }
        html2pdf().from(invoice).set(opt).save()
    }

    $("#print").hide()

    $("#balance_block").hide()

    $("#IsGL").select2().on("select2:select", function (e) {
        var transaction_element = $(e.currentTarget);
        tran_name = $('#IsGL').find(':selected').text();
        transaction_to = transaction_element.val();
        $('#IsGL').val(transaction_to);
    });

    // Ajax Call
    function searchByDate() {

        var HeadCode = $('#IsGL').val();
        var from = $('#from').val();
        var to = $('#to').val();

        if (HeadCode && from && to) {
            $.ajax({
                url: "{{ route('accounts.is_gl_search') }}",
                method: 'GET',
                data: {
                    HeadCode: HeadCode,
                    from: from,
                    to: to
                },
                success: function (data) {

                    $('#cash_book tr').remove();
                    $("#cash_book_total tr").remove();
                    $("#balance_block").show()
                    // $("#gl_title").remove();
                    $("#gl_title").html(`
                        General ledger of ( ${data.head_name} - ${data.HeadCode} ) - (On ${data.from} To ${data.to} )
                        `);
                    let total_balance = 0;
                    if (data.gl_transaction.length > 0) {
                        $("#print").show()
                        
                        $("#prev-balance").html(`${data.balance[0]} BDT`)
                        $.each(data.gl_transaction, (key, value) => {

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
                    } else {
                        $('#cash_book tr').remove();
                        $("#cash_book_total tr").remove();
                        $("#balance_block").hide()
                        $("#cash_book_total").append(`
                            <tr>
                                <td colspan = "7"><strong class="badge badge-danger text-center d-block">No Recored Found</strong></td>
                            </tr>
                    `);
                    }
                }
            });
        } else {
            swal("","Please fill up the fields","error")
        }
    }

</script>
@endsection
