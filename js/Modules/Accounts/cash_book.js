$(function () {

  "use strict";

  /*View Voucher Informations*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('view-tr-', '');
      console.log(AccountID);
      //var url= baseUrl+"bank/view/"+AccountID;
      //getAjaxView(url,data=null,'ajaxview',false,'get');
  });

  /*Print Voucher */
  $('.table').on('click', '.print-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('print-tr-', '');
      var purl = baseUrl+"accounts/print_cashbook/"+AccountID;
        location.href = purl;
  });
  
});