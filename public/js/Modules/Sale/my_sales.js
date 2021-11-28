$(function () {
    var table = $('#salesTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"seller/my_sales",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'voucher_no', name: 'voucher_no'},
          {data: 'customer.customer_name', name: 'customer.customer_name'},
          {data: 'v_date', name: 'v_date'},
          {data: 'del_date', name: 'del_date'},
          {data: 'amount', name: 'amount'}
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
      var url= baseUrl+"seller/sell_req_details/"+unitID;
      getAjaxView(url,data=null,'ajaxview',false,'get');
  });

});
