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
      var purl = baseUrl+"accounts/print_voucher/"+AccountID;
        location.href = purl;
  });
  /* Delete Bank Account */
  $('.table').on('click', '.approve-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('approve-tr-', '');
      var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(0)").text();
      var content = 'Approve '+name+'?';
      var confirmtext = 'Approve the voucher ?';
      var confirmbtntext = 'Approve';
      var printCallback = function() {
        var purl = baseUrl+"accounts/print_voucher/"+AccountID;
        location.href = purl;
      }
      var confirmCallback=function(){
      var requestCallback=function(response){
          if(response.status == 'success') {
              toastr.success('Voucher Approved Successfully', 'Success');
              var cont = 'Print '+name+ '?';
              var cnftxt = 'Print Voucher ?';
              var cbtntext = 'Print';
              confirmAlert(printCallback,cont,cnftxt,cbtntext)
              }else{
              toastr.error(i18n.msg.delete_failed, i18n.layout.error);
              }
          }
          var url= baseUrl+"accounts/approve_voucher/"+AccountID;
      
          ajaxGetRequest(url,requestCallback);
      }
      confirmAlert(confirmCallback,content,confirmtext,confirmbtntext)
  });

  $('.import_data').on('click', '.import_file', function(){
      "use strict";
      var formate  = this.id.replace('file-id-', '');
      var url= baseUrl+"customer/import_file/"+formate;
      
      getAjaxModal(url);
  });


});