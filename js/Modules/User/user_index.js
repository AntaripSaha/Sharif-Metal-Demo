$(function () {
    var table = $('#user_list').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"users/user_list",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'name', name: 'name'},
          {data: 'user_id', name:'user_id'},
          {data: 'email', name: 'email'},
          {data: 'role.name', name: 'role.name'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add New User*/
  $('.card_buttons').on('click', '.add_user', function(){
      "use strict";
      var url= baseUrl+"users/add_user";
      getAjaxModal(url);
  });

  // Import Seller Users Data Start
  $('.card_buttons').on('click', '.ImportSellerUsers', function(){
      "use strict";
      var url = baseUrl + "users/import_sellers";
      getAjaxModal(url);
  })
  // Import Seller Users Data End

  /*View User Data*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var userID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"users/view/"+userID;
      getAjaxModal(url);
  });

  /*Edit Lot data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var userID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"users/edit/"+userID;
      getAjaxModal(url);
  });
  
  $('.table').on('click', '.changePassword-tr', function (){
        "use strict"
        var userID = this.id.replace('changePassword-tr-','');
        var url = baseUrl + "users/change_user_password/"+userID;
        getAjaxModal(url);
    });

  /* User Soft-Delete */
  $('.table').on('click', '.delete-tr', function(){
    "use strict";
    var userID = this.id.replace('delete-tr-', '');
    var currentRow=$(this).closest("tr");
    var name= currentRow.find("td:eq(1)").text();
    var content = 'Delete '+name+'?';
    var confirmtext = 'Delete This User';
    var confirmCallback=function(){
    var requestCallback=function(response){
        if(response.status == 'success') {
            toastr.success('User Deleted Successfully', 'Success');
            table.ajax.reload( null, false ); 
            }else{
            toastr.error('You can not Delete this Bank.You have Transactions.','Error');
            }
        }

        var url= baseUrl+"users/delete/"+userID;
        console.log(url);
        ajaxGetRequest(url,requestCallback);
    }
    confirmAlert(confirmCallback,content,confirmtext)
});

});