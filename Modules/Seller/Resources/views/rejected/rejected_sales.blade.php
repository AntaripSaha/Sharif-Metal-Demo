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
                    <h1 class="m-0 text-dark">Rejected sales</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Rejected sales</li>
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
                        <h3 class="card-title">Rejected sales</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="salesTable"
                            class="table table-sm table-bordered table-striped display responsive nowrap" width="100%">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>RequestDate</th>
                                    <th>Party Name</th>
                                    <th>Seller Name</th>
                                    <th>Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
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
    $(function () {
        var table = $('#salesTable').DataTable({
          responsive: true,
          processing: true,
          serverSide: true,
          ajax: baseUrl+"seller/rejected_sales",
          order: [ [0, 'desc'] ],
          columns: [
              {data: 'req_id', name: 'req_id'},
              {data: 'v_date', name: 'v_date'},
              {data: 'customer.customer_name', name: 'customer.customer_name'},
              {data: 'seller', name: 'seller'},
              {data: 'amount', name: 'amount'},
              {data: 'action', name: 'action'}
              ],
              columnDefs: [
                  { "orderable": true, "searchable": true }
              ]
        });
    
      /*Add Lot data*/
      $('.card_buttons').on('click', '.add_wareproduct', function(){
          "use strict";
          var url= baseUrl+"warehouse/add_wareproduct";
          getAjaxModal(url);
      });
    
      /*Add Lot data*/
      $('.card_buttons').on('click', '.add_warehouse', function(){
          "use strict";
          var url= baseUrl+"warehouse/add";
          getAjaxModal(url);
      });
    
      /*View Seals Request Info*/
      $('.table').on('click', '.view-tr', function(){
          "use strict";
          var unitID  = this.id.replace('view-tr-', '');
          var url= baseUrl+"seller/sell_req_details/"+unitID;
          getAjaxView(url,data=null,'ajaxview',false,'get');
      });
    
      /*Edit Lot data */
      $('.table').on('click', '.edit-tr', function(){
          "use strict";
          var unitID  = this.id.replace('edit-tr-', '');
          var url= baseUrl+"warehouse/edit/"+unitID;
          getAjaxModal(url);
      });
    
    });
</script>
@endsection
