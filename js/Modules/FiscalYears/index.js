$(function () {
    var table = $('#myTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"fiscalyears/index",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'starting_date', name: 'starting_date'},
          {data: 'ending_date', name: 'ending_date'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add Role data*/
  $('.card_buttons').on('click', '.add_fiscal_year', function(){
      "use strict";
      var url= baseUrl+"fiscalyears/add";
      getAjaxModal(url);
  });

  /*View Role Data*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var roleID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"roles/view/"+roleID;
      getAjaxView(url,data=null,'ajaxview',false,'get');
  });

  /*Edit Role data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var roleID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"roles/view/"+roleID;
      getAjaxView(url,data=null,'ajaxview',false,'get');
  });

  $('.table').on('click', '.delete-tr', function(){
      "use strict";
      var roleID  = this.id.replace('delete-tr-', '');
      var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(1)").text();
      /*edit after js lang check*/
      var content = 'Delete the Role '+name+'?';
      var confirmtext = 'Delete';
      var confirmCallback=function(){
      var requestCallback=function(response){
          if(response.status == 'success') {
              /*edit after js lang check*/
              toastr.success('Delete Role', 'Succeess');
              table.ajax.reload( null, false );  
              }else{
                  /*edit after js lang check*/
              toastr.error('Could not Delete ', 'Failed !!');
              }
          }

          var url= baseUrl+"roles/delete/"+roleID;
      
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
