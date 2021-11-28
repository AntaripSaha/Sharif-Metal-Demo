$(function () {
    var table = $('#warehouseTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"warehouse",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'name', name: 'name'},
          {data: 'location', name: 'location'},
          {data: 'status', name: 'status'},
          {data: 'action', name: 'action'}
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });

  /*Add Lot data*/
  $('.card_buttons').on('click', '.add_wareproduct', function(){
      "use strict";
      var url= baseUrl+"warehouse/add_wareproduct";
      getAjaxModal(url);
  });

  /*Add Lot data*/
  $('.card_buttons').on('click', '.add_warehouse', function(){
      "use strict";
      var url= baseUrl+"warehouse/add";
      getAjaxModal(url);
  });

  /*View Lot Data*/
  $('.table').on('click', '.view-tr', function(){
      "use strict";
      var unitID  = this.id.replace('view-tr-', '');
      var url= baseUrl+"warehouse/view/"+unitID;
      getAjaxModal(url);
  });

  /*Edit Lot data */
  $('.table').on('click', '.edit-tr', function(){
      "use strict";
      var unitID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"warehouse/edit/"+unitID;
      getAjaxModal(url);
  });

});