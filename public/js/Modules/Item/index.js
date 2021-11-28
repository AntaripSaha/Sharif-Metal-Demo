    var table = $('#myTable').DataTable({                
        processing: true,
        serverSide: true,
        ajax: baseUrl+"sales/items",
        dom: 'Bfrtip',
        order: [ [0, 'desc'] ],
        columns: [
            {data: 'input',
                searchable: false,
                orderable: false,},
            {data:'name'},
            {data:'description'},
            {data:'rate'}, 
            {data:'unit'}, 
            {data:'item_category.name'},
            {data:'action'},
        ],
         columnDefs: [
                { "orderable": false, "targets": 0 }
                    ]
    });
    $('.card_buttons').on('click', '.additem', function(){
      var url= baseUrl+"sales/createitem";
      getAjaxModal(url);
    });
    $('.item_import_data').on('click', '.item_import_file', function(){
      var formate  = this.id.replace('file-id-', '');
      var url= baseUrl+"sales/import_file/"+formate;
      getAjaxModal(url,1);
    });
    $('.table').on('click', '.edit-tr', function(){
      var itemID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"sales/edititem/"+itemID;
      getAjaxModal(url);
    });
    $('.table').on('click', '.delete-tr', function(){
        var itemID  = this.id.replace('delete-tr-', '');
        var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(1)").text();
       var content = i18n.layout.delete+" "+i18n.menu.items+name+'?';
        var confirmtext = i18n.msg.are_you_delete_it+'?';
        var confirmCallback=function(){
        var requestCallback=function(response){
            if(response.status == 'success') {
                toastr.success(i18n.msg.delete_success, i18n.layout.success);
                table.ajax.reload( null, false ); 
                }else{
               toastr.error(i18n.msg.delete_failed, i18n.layout.error);
                }
            }
            var url= baseUrl+"sales/deleteitem"+itemID;
            ajaxGetRequest(url,requestCallback);
        }
        confirmAlert(confirmCallback,content,confirmtext)
    });
