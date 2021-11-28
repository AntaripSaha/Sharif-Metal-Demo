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
                    <h1 class="m-0 text-dark">@lang('account.proftandLoss')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('account.proftandLoss')</li>
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

                        <div class="card-body" id="profitLoss">
                            <div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h5 class="text-center" id="title">Profit and Loss Statement [All]</h5>
                                    </div>
                                </div>
                            </div>

                            <!-- profit start -->
                            <table class="table table-sm table-bordered" id="profit_table">
                                <thead>
                                    <tr>
                                        <td colspan="3">
                                            <strong>INCOME</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-left"><strong>Income</strong></td>
                                        <td class="text-right"><strong>Amount</strong></td>
                                    </tr>
                                    @php
                                    $totalIncome = 0;
                                    $totalExpense = 0;
                                    @endphp
                                </thead>
                                
                                <tbody>
                                    @foreach ($profit as $p)
                                    <tr>
                                        <td>{{ $p->HeadName }}</td>
                                        <td class="text-right">
                                            @if($p->transactions->count() > 0)
                                                {{ $p->transactions->sum('Credit') }}
                                            @php
                                                $totalIncome += $p->transactions->sum('Credit')
                                            @endphp
                                            @else
                                                0.00
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                
                                <tfoot>
                                    <tr>
                                        <td class="text-left"><strong>Total Income</strong></td>
                                        <td class="text-right"><strong> <span id="total_income">{{ $totalIncome }}</span> BDT</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- profit end -->
                            <br>

                            <!-- loss start -->
                            <table class="table table-sm table-bordered" id="loss_table">
                                <thead>
                                    <tr>
                                        <td colspan="2">
                                            <strong>COSTS</strong>
                                        </td>
                                    </tr>
    
                                    <tr>
                                        <td class="text-left"><strong>Expense Type</strong></td>
                                        <td class="text-right"><strong>Amount</strong></td>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($loss as $key => $l)
                                    <tr>
                                        <td>{{ $l->HeadName }}</td>
                                        <td class="text-right">
                                            @if($l->transactions->count() > 0)
                                            {{ $l->transactions->sum('Debit') }}
                                            @php
                                            $totalExpense += $l->transactions->sum('Debit')
                                            @endphp
                                            @else
                                            0.00
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td class="text-left"><strong>Total Costs</strong></td>
                                        <td class="text-right"><strong> <span id="total_expense">{{ $totalExpense }}</span> BDT</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- loss end -->
                            <br>

                            <!-- final statement start -->
                            <table class="table table-sm table-bordered" id="statement_table">
                                <tr>
                                    <td colspan="3">
                                        <strong>Final Statement</strong>
                                    </td>
                                </tr>
                                <tr class="text-center">
                                    <th>Total Income</th>
                                    <th>Total Cost</th>
                                    <th>Statement</th>
                                </tr>
                                <tr>
                                <tr class="text-center">
                                    <td><span id="statement_income">{{ $totalIncome }}</span> BDT</td>
                                    <td> <span id="statement_expense">{{ $totalExpense }}</span> BDT</td>
                                    <td id="result">
                                        @if($totalIncome > $totalExpense)
                                        <span> {{ $totalIncome - $totalExpense }} <span
                                                class="badge badge-success">Profit</span></span>
                                        @else
                                        <span> {{ $totalExpense - $totalIncome }}<span
                                                class="badge badge-danger">Loss</span></span>
                                        @endif
                                    </td>
                                </tr>
                                </tr>
                            </table>
                            <!-- final statement end -->

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
<script>
    function printPdf() {
        const invoice = document.getElementById("print_pdf")
        var opt = {
            margin: 0,
            filename: 'ProfitAndLossStatement.pdf',
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

    //search by date
    function searchByDate() {
        var from = $('#from').val();
        var to = $('#to').val();

        if (from && to) {
            $.ajax({
                url: "{{ route('accounts.profit_loss_by_date') }}",
                method: 'GET',
                data: {
                    from: from,
                    to: to
                },
                success: function (data) {
                    if( data ){
                        $("#profit_table tbody tr").remove()
                        $("#loss_table tbody tr").remove()
                        $("#result span").remove()
                        $.each(data.profit,(key,value) => {
                            $("#profit_table tbody").append(`
                                <tr>
                                    <td>${value.HeadName}</td>
                                    <td class="text-right">
                                        ${data.profit_amount[key]}
                                    </td>
                                </tr>
                            `);
                        })
                        $("#total_income").html(`${data.totalIncome}`)

                        $.each(data.loss,(key,value) => {
                            $("#loss_table tbody").append(`
                                <tr>
                                    <td>${value.HeadName}</td>
                                    <td class="text-right">
                                        ${data.loss_amount[key]}
                                    </td>
                                </tr>
                            `);
                        })
                        $("#total_expense").html(`${data.totalExpense}`)

                        $("#statement_income").html(data.totalIncome)
                        $("#statement_expense").html(data.totalExpense)

                        $("#result").append(`
                        <span>${data.diff}<span
                                                class="badge badge-danger" style="color:white;background-color: #dc3545;">${data.result}</span></span>
                        `);
                            
                        $("#title").html(`
                        Profit and Loss Statement On ( ${data.from} - ${data.to} )
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
