$(function () {
    var table = $('#ware_productsTable').DataTable({
      responsive: true,
      processing: true,
      serverSide: true,
      ajax: baseUrl+"reports/product_wise",
      order: [ [0, 'desc'] ],
      columns: [
          {data: 'input', name: 'input'},
          {data: 'p_name', name: 'p_name'},
          {data: 'w_name', name: 'w_name'},
          {data: 'sell_q', name: 'sell_q'},
          ],
          columnDefs: [
              { "orderable": true, "searchable": true }
          ]
    });
});