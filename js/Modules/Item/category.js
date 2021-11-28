    var table = $('#myTable').DataTable({                
        processing: true,
        serverSide: true,
        searching: false,
        ajax: baseUrl+"sales/categories",
        dom: 'Bfrtip',
        order: [ [0, 'desc'] ],
        columns: [
            {data:'name'},
            {data:'parent_category.name'},
            {data:'action'},
        ],
        columnDefs: [
          { "orderable": false, "targets": 0 }
        ]
    });

    $('.table').on('click', '.edit-tr', function(){
      var itemID  = this.id.replace('edit-tr-', '');
      var url= baseUrl+"sales/editcategory/"+itemID;
      getAjaxModal(url);
    });
    $('.table').on('click', '.delete-tr', function(){
        var itemID  = this.id.replace('delete-tr-', '');
        var currentRow=$(this).closest("tr");
      var name= currentRow.find("td:eq(0)").text();
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
            var url= baseUrl+"sales/deletecategory/"+itemID;
            ajaxGetRequest(url,requestCallback);
        }
        confirmAlert(confirmCallback,content,confirmtext)
    });
