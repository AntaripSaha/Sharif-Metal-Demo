$(function () {
    var table = $('#undeliveredTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"seller/undelivered_sales",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'req_id', name: 'req_id'},
          {data: 'customer.customer_name', name: 'customer.customer_name'},
          {data: 'seller.user_id', name: 'seller.user_id'},
          {data: 'v_date', name: 'v_date'},
          {data: 'price', name: 'price'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*View Seals Request Info*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var unitID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"seller/undelivered_details/"+unitID;
      getAjaxView(url,data=null,'ajaxview',false,'get');
  });


  /*Print Invoice*/
  $('.table').on('click', '.print-tr', function(){
      "use strict";
      var unitID  = this.id.replace('print-tr-', '');
      var url= baseUrl+"seller/print_invoice/"+unitID;
      location.href = url;
  });
});