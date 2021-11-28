$(function () {
    var table = $('#categoryTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"product/category",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'category_name', name: 'category_name'},
          {data: 'category_slug', name: 'category_slug'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add Category data*/
  $('.card_buttons').on('click', '.add_category', function(){
      "use strict";
      var url= baseUrl+"product/category/add";
      getAjaxModal(url);
  });

  /*View Category Data*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var unitID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"product/category/view/"+unitID;
      getAjaxModal(url);
  });

  /*Edit Category data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var unitID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"product/category/edit/"+unitID;
      getAjaxModal(url);
  });
  /* Delete Category */
  $('.table').on('click', '.delete-tr', function(){
      "use strict";
      var LotID  = this.id.replace('delete-tr-', '');
      var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(1)").text();
      var content = 'Delete '+name+'?';
      var confirmtext = 'Delete Unit';
      var confirmCallback=function(){
      var requestCallback=function(response){
          if(response.status == 'success') {
              toastr.success('Category Deleted Successfully  ', 'Success');
              table.ajax.reload( null, false ); 
              }else{
              toastr.error(i18n.msg.delete_failed, i18n.layout.error);
              }
          }

          var url= baseUrl+"product/category/delete/"+LotID;
      
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