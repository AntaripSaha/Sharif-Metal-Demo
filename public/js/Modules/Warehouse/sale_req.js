$(function () {
    var table = $('#sale_reqTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"warehouse/prod_requests",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'voucher_no', name: 'voucher_no'},
          {data: 'customer.customer_name', name: 'customer.customer_name'},
          {data: 'seller.name', name: 'seller.name'},
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
      var url= baseUrl+"warehouse/sell_req_details/"+unitID;
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