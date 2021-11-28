$(function () {
    var table = $('#accountTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"bank/index",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'bank_name', name: 'bank_name'},
          {data: 'account_name', name: 'account_name'},
          {data: 'account_no', name: 'account_no'},
          {data: 'branch', name: 'branch'},
          {data: 'balance', name: 'balance'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add New Account data*/
  $('.card_buttons').on('click', '.add_bank', function(){
      "use strict";
      var url= baseUrl+"bank/add";
      getAjaxModal(url);
  });
  $('.card_buttons').on('click','.bank_transaction',function(){

      var url= baseUrl+"bank/transactions";
      location.href = url;
  });
  $('.card_buttons').on('click','.bank_ledger',function(){

      var url= baseUrl+"bank/ledgers";
      location.href = url;
  });
  /*View Account Informations*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"bank/view/"+AccountID;
          
      getAjaxView(url,data=null,'ajaxview',false,'get');
  });

  /*Edit Bank data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"bank/edit/"+AccountID;
      getAjaxModal(url,1);
  });
  /* Delete Bank Account */
  $('.table').on('click', '.delete-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('delete-tr-', '');
      var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(1)").text();
      var content = 'Delete '+name+'?';
      var confirmtext = 'Delete Bank Account';
      var confirmCallback=function(){
      var requestCallback=function(response){
          if(response.status == 'success') {
              toastr.success('Bank Account Deleted Successfully', 'Success');
              table.ajax.reload( null, false ); 
              }else{
              toastr.error('You can not Delete this Bank.You have Transactions.','Error');
              }
          }

          var url= baseUrl+"bank/delete/"+AccountID;
          console.log(url);
          ajaxGetRequest(url,requestCallback);
      }
      confirmAlert(confirmCallback,content,confirmtext)
  });

  $('.import_data').on('click', '.import_file', function(){
      "use strict";
      var formate  = this.id.replace('file-id-', '');
      var url= baseUrl+"customer/import_file/"+formate;
      
      getAjaxModal(url);
  });


});