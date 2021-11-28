 $('document').ready(function(){
                "use strict";
     var table = $('#myTable').DataTable({       
        processing: true,
        serverSide: true,
        ajax: baseUrl+"settings/user_list",
        dom: 'Bfrtip',
        order: [ [0, 'desc'] ],
        columns: [
            
            {data:'name'},
            {data:'email'},
            {data:'role.name'},
            {data:'status'}, 
             
            {data:'action'},

        ],
         columnDefs: [
                { "orderable": false, "targets": 0 }
                    ]

    });

      /*View Customer Data*/
    $('.table').on('click', '.edit-tr', function(){
        var id  = this.id.replace('edit-tr-', '');
        var url= baseUrl+"settings/edit_user/"+id;
            
        getAjaxView(url,data=null,'tab_setting',false,'get');
    });

             });