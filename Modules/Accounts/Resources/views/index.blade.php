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
                    <h1 class="m-0 text-dark">@lang('menu.Chart Of Accounts')</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">@lang('menu.Chart Of Accounts')</li>
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
                        <h3 class="card-title">@lang('menu.Chart Of Accounts')</h3>
                        <button class="btn btn-info float-right btn-sm" onclick="printPdf()">Print COA</button>
                    </div>
                    <!-- /.card-header -->
                    <div id="print_pdf">
                        <center class="d-block">
                            <h4 class="mt-3">Chart Of Accounting</h4>
                        </center>
                        <div class="card-body row">
                            <div class="col-md-6">
                                <ul id="tree2">
                                    <?php
                                        $visit=array();
                                        for ($i = 0; $i < count($userList); $i++)
                                        {
                                            $visit[$i] = false;
                                        }
                                        Modules\Accounts\Entities\Accounts::dfs("Chart Of Accounts","0",$userList,$visit,0);
                                    ?>
                                </ul>
                            </div>
                            <div class="col-md-6" id="coa_view">
                                
                            </div>
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
<script src="{{asset('plugins/treeview/treeview-active.js')}}"></script>
<script src="{{asset('js/Modules/Accounts/index.js')}}"></script>
<script type="text/javascript" src="{{ asset('js/html2pdf.min.js') }}"></script>

<script>
    "use strict";
    
    function loadCoaData(id){
        var url= baseUrl+"accounts/view_code/"+id;
        getAjaxView(url,data=null,'coa_view',false,'get');
    }

    function printPdf() {
        const invoice = document.getElementById("print_pdf")
        var opt = {
                margin: 0,
                filename: 'ChartOfAccounting.pdf',
                image: { type: 'jpeg' , quality : 0.98 },
                html2canvas : { scale : 1 },
                jsPDF : { unit : 'in' , format : 'letter' , orientation : 'portrait' }
        }
        html2pdf().from(invoice).set(opt).save()
    }
</script>
@endsection
