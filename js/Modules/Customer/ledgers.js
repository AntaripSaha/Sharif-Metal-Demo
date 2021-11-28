$(function () {
  $("#customer_id").select2()
    .on("select2:select", function(e) {
        var seller_element = $(e.currentTarget);
        var customer_val = seller_element.val();
        $('#customer_id').val(customer_val);
  });
  $('.card_buttons').on('click', '#ledgers', function(){
        var customer_id = $("#customer_id").val();
        var company_id = $("#company_id").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var url= baseUrl+"customer/view_ledgers";
        var data = {company_id:company_id,customer_id:customer_id, from:from, to:to};
        getAjaxView(url,data=data,'ledger_view',false,'get');
  });
  $('.card_buttons').on('click', '#ledger_all', function(){
    location.reload();
  });

  /*Add New Account data*/
  $('.card_buttons').on('click', '.add_customer', function(){
      "use strict";
      var url= baseUrl+"customer/add";
      getAjaxModal(url);
  });
  $('.card_buttons').on('click','.paid_customer',function(){

      var url= baseUrl+"customer/paid_customer";
      location.href = url;
  });
  $('.card_buttons').on('click','.credit_customer',function(){

      var url= baseUrl+"customer/credit_customer";
      location.href = url;
  });

});