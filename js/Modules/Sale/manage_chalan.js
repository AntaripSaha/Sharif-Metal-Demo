$(function () {
    var table = $('#salesTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"seller/manage_chalan",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'voucher_no', name: 'voucher_no'},
          {data: 'customer', name: 'customer'},
          {data: 'seller', name: 'seller'},
          {data: 'v_date', name: 'v_date'},
          {data: 'del_date', name: 'del_date'},
          {data: 'del_amount', name: 'del_amount'},
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
      var url= baseUrl+"seller/sold_details/"+unitID;
      getAjaxView(url,data=null,'ajaxview',false,'get');
  });
  /*Print Invoice*/
  $('.table').on('click', '.print-tr', function(){
      "use strict";
      var unitID  = this.id.replace('print-tr-', '');
      var url= baseUrl+"warehouse/print_chalan/"+unitID;
      location.href = url;
  });

});