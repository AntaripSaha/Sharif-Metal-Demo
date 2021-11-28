$(function () {
    var table = $('#supplierTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"supplier/index",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'supplier_name', name: 'supplier_name'},
          {data: 'mobile', name: 'mobile'},
          {data: 'email', name: 'email'},
          {data: 'balance', name: 'balance'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add New Account data*/
  $('.card_buttons').on('click', '.supplier_ledger', function(){
      "use strict";
      var url= baseUrl+"supplier/supplier_ledger";
      location.href = url;
  });
  /*Add New Account data*/
  $('.card_buttons').on('click', '.add_supplier', function(){
      "use strict";
      var url= baseUrl+"supplier/add_supplier";
      getAjaxModal(url);
  });
  /*View Account Informations*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"supplier/view_supplier/"+AccountID;
      getAjaxModal(url);    
  });

  /*Edit Customer data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"supplier/update_supplier/"+AccountID;
      getAjaxModal(url);
  });
  /* Delete Customer Account */
  $('.table').on('click', '.delete-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('delete-tr-', '');
      var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(1)").text();
      var content = 'Delete '+name+'?';
      var confirmtext = 'Delete supplier Account';
      var confirmCallback=function(){
      var requestCallback=function(response){
          if(response.status == 'success') {
              toastr.success('Supplier Account Deleted Successfully', 'Success');
              table.ajax.reload( null, false ); 
              }else{
              toastr.error('You can not Delete this Supplier.Supplier have Transactions.','Error');
              }
          }

          var url= baseUrl+"supplier/delete/"+AccountID;
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