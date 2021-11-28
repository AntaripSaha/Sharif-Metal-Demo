$(function () {
    var table = $('#salesTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"seller/index",
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