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
                    <h1 class="m-0 text-dark">@lang('account.inventory_ladge')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.inventory_ladge')</li>
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
                                <div class="col-md-2 mr-4">
                                    <label for="from" class="d-block text-left">@lang('layout.from') : </label>
                                    <input type="date" class="form-control mr-sm-2" id="from" name="from">
                                </div>
                                <div class="col-md-2 mr-4">
                                    <label for="to" class="d-block text-left">@lang('layout.to') : </label>
                                    <input type="date" class="form-control mr-sm-2" id="to" name="to">
                                </div>
                                <div class="col-md-4">
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
                                    <h5 class="text-center" id="gl_title">
                                        Inventory Ledger ( All )
                                    </h5>
                                </div>
                                <div class="col-md-12" id="balance_block">
                                    <p class="text-right " style="margin-bottom: 0;">Previous Balance : <span
                                            id="prev-balance">{{ $balance[0] }} BDT</span></p>
                                    <p class="text-right ">Current Balance : <span id="cur-balance">{{ $total }}
                                            BDT</span></p>
                                </div>
                            </div>

                            <table class="table table-sm table-bordered table-striped">
                                <thead>
                                    <th>Transaction Date</th>
                                    <th>Narration</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                    {{-- <th>Action</th> --}}
                                </thead>

                                <tbody id="cash_book">

                                    @foreach($transactions as $key => $tran)
                                    <tr>
                                        <td>{{ $tran->VDate }}</td>
                                        <td>{{ $tran->Narration }}</td>
                                        <td>{{ $tran->Debit }}</td>
                                        <td>{{ $tran->Credit }}</td>
                                        <td>
                                            {{ $balance[$key] }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot id="cash_book_total">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td><strong>{{ $total_debit }}</strong></td>
                                        <td><strong>{{ $total_credit }}</strong></td>
                                        <td><strong>{{ $total }}</strong></td>
                                    </tr>
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
    $("#IsGL").select2().on("select2:select", function (e) {
        var transaction_element = $(e.currentTarget);
        tran_name = $('#IsGL').find(':selected').text();
        transaction_to = transaction_element.val();
        $('#IsGL').val(transaction_to);
    });

    function printPdf() {
        const invoice = document.getElementById("print_pdf")
        var opt = {
            margin: 0,
            filename: 'InventoryLedger.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 1
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        }
        html2pdf().from(invoice).set(opt).save()
    }

    // Ajax Call
    function searchByDate() {
        var from = $('#from').val();
        var to = $('#to').val();

        if (from && to) {
            $.ajax({
                url: "{{ route('accounts.inventory_ledger_by_date') }}",
                method: 'GET',
                data: {
                    from: from,
                    to: to
                },
                success: function (data) {
                    if (data) {
                        $("#gl_title").html(`
                        Inventory Ledger ( on ${data.from} to ${data.to} )
                        `);

                        if (data.balance.length > 0) {
                            $("#prev-balance").html(`${data.balance[0]} BDT`);
                        } else {
                            $("#prev-balance").html(`0 BDT`);
                        }


                        $("#cur-balance").html(`${data.total} BDT`);
                        $("#cash_book tr").remove()
                        $("#cash_book_total tr").remove();

                        $.each(data.inventory_ledger, (key, value) => {
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
                        })
                        $("#cash_book_total").append(`
                            <tr>
                                <td></td>
                                <td></td>
                                <td><strong>${data.total_debit}</strong></td>
                                <td><strong>${data.total_credit}</strong></td>
                                <td><strong>${data.total}</strong></td>
                            </tr>
                        `);
                    }
                }
            });
        } else {
            swal("", "Please choose a date", "error")
        }
    }

</script>
@endsection
