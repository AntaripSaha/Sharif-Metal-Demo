$(function () {
    var table = $('#customerTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"customer/index",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'customer_id', name:'customer_id'},
          {data: 'customer_name', name: 'customer_name'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add New Account data*/
  $('.card_buttons').on('click', '.import_file', function(){
      "use strict";
      var url= baseUrl+"customer/import_file";
      getAjaxModal(url);
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
  /*View Account Informations*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"customer/view_customer/"+AccountID;
      getAjaxModal(url);    
  });

  /*Edit Customer data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"customer/update_customer/"+AccountID;
      getAjaxModal(url);
  });
  /* Delete Customer Account */
  $('.table').on('click', '.delete-tr', function(){
      "use strict";
      var AccountID  = this.id.replace('delete-tr-', '');
      var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(1)").text();
      var content = 'Delete '+name+'?';
      var confirmtext = 'Delete Customer Account';
      var confirmCallback=function(){
      var requestCallback=function(response){
          if(response.status == 'success') {
              toastr.success('Customer Account Deleted Successfully', 'Success');
              table.ajax.reload( null, false ); 
              }else{
              toastr.error('You can not Delete this Customer.Customer have Transactions.','Error');
              }
          }

          var url= baseUrl+"customer/delete/"+AccountID;
          ajaxGetRequest(url,requestCallback);
      }
      confirmAlert(confirmCallback,content,confirmtext)
  });


  $('.card_buttons').on('click', '.import_file', function(){
      "use strict";
      var url= baseUrl+"customer/import_file";
      
      getAjaxModal(url);
  });


});