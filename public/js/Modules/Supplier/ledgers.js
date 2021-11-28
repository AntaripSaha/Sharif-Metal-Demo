$(function () {
  /*Add New Account data*/
  $('.card_buttons').on('click', '.add_supplier', function(){
      "use strict";
      var url= baseUrl+"supplier/add_supplier";
      getAjaxModal(url);
  });

  $("#supplier_id").select2()
    .on("select2:select", function(e) {
        var seller_element = $(e.currentTarget);
        var supplier_val = seller_element.val();
        $('#supplier_id').val(supplier_val);
  });
  
  $('.card_buttons').on('click', '#ledgers', function(){
        var supplier_id = $("#supplier_id").val();
        var company_id = $("#company_id").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var url= baseUrl+"supplier/view_ledgers";
        var data = {company_id:company_id,supplier_id:supplier_id, from:from, to:to};
        getAjaxView(url,data=data,'ledger_view',false,'get');
  });
  $('.card_buttons').on('click', '#ledger_all', function(){
    location.reload();
  });

});