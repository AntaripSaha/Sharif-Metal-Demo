$(function () {
    var table = $('#productTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"product/index",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'product_name', name: 'product_name'},
          {data: 'category.category_name', name: 'category.category_name'},
          {data: 'product_id', name: 'product_id'},
          {data: 'head_code', name: 'head_code'},
          {data: 'price', name: 'price'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add Category data*/
  $('.card_buttons').on('click', '.add_product', function(){
      "use strict";
      var url= baseUrl+"product/add";
      getAjaxModal(url);
  });

  /*View Category Data*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var productID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"product/view/"+productID;
      getAjaxView(url,data=null,'ajaxview',false,'get');
  });

  /*Edit Category data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var productID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"product/edit/"+productID;
      getAjaxModal(url);
  });

  $('.import_data').on('click', '.import_file', function(){
      "use strict";
      var formate  = this.id.replace('file-id-', '');
      var url= baseUrl+"customer/import_file/"+formate;
      
      getAjaxModal(url);
  });


});