$(function () {
    var table = $('#creditcustomerTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"customer/credit_customer",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'customer_name', name: 'customer_name'},
          {data: 'customer_address', name: 'customer_address'},
          {data: 'customer_mobile', name: 'customer_mobile'},
          {data: 'customer_email', name: 'customer_email'},
          {data: 'balance', name: 'balance'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });
  $('.card_buttons').on('click', '.add_customer', function(){
      "use strict";
      var url= baseUrl+"customer/add";
      getAjaxModal(url);
  });
  $('.card_buttons').on('click','.paid_customer',function(){

      var url= baseUrl+"customer/paid_customer";
      location.href = url;
  });
  $('.card_buttons').on('click','.customer_ledger',function(){

      var url= baseUrl+"customer/customer_ledger";
      location.href = url;
  });

});