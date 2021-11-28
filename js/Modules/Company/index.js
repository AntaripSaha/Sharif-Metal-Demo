   $(function () {
      var table = $('#myTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: baseUrl+"admin/companies",
        order: [ [0, 'desc'] ],
        columns: [
            {data: 'input', name: 'input'},
            {data: 'name', name: 'name'},
            {data: 'phone_no', name: 'phone_no'},
            {data: 'address', name: 'address'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action'}
            ],
            columnDefs: [
                { "orderable": true, "searchable": true }
            ]
      });

    /*Add Company data*/
    $('.card_buttons').on('click', '.add_company', function(){
        "use strict";
        var url= baseUrl+"admin/companies/add";
        getAjaxModal(url);
    });

    /*View Company Data*/
    $('.table').on('click', '.view-tr', function(){
        "use strict";
        var customerID  = this.id.replace('view-tr-', '');
        var url= baseUrl+"admin/companies/view/"+customerID;
            
        getAjaxView(url,data=null,'ajaxview',false,'get');
    });

    /*Edit Company data */
    $('.table').on('click', '.edit-tr', function(){
        "use strict";
        var companyID  = this.id.replace('edit-tr-', '');
        var url= baseUrl+"admin/companies/edit/"+companyID;
        getAjaxModal(url,1);
    });


    /*Update status*/
    $('.table').on('click', '.update-tr', function(){
        "use strict";
        var customerID  = this.id.replace('update-tr-', '');
        
        var url= baseUrl+"customer/status_update/"+customerID;
            
        var currentRow=$(this).closest("tr");
        var name= currentRow.find("td:eq(1)").text();
        /*edit after js lang check*/
        var content = i18n.msg.change_status+' '+name +' !';
        var confirmtext = i18n.msg.update_status; 
        var confirmCallback=function(){
            var successcallback = function(a){
                if (a.status == 'success') {
                     toastr.success(i18n.customers.customer+" "+i18n.msg.update_successfully,  i18n.layout.success);
                    table.ajax.reload( null, false );
                    var url= baseUrl+"customer/statistics";
                    getAjaxView(url,data=null,'statistics',false,'get');
                }else{
                    /*edit after js lang check*/
                    toastr.error(i18n.msg.update_error, i18n.layout.warning);
                }  
            }
            ajaxGetRequest(url,successcallback);
        }
        confirmAlert(confirmCallback,content,confirmtext)
    });

    $('.table').on('click', '.delete-tr', function(){
        "use strict";
        var userID  = this.id.replace('delete-tr-', '');
        var currentRow=$(this).closest("tr");
        var name= currentRow.find("td:eq(1)").text();
        var content = 'Delete '+name+'?';
        var confirmtext = 'Delete Company';
        var confirmCallback=function(){
        var requestCallback=function(response){
            if(response.status == 'success') {
                toastr.success('Company Deleted Successfully  ', 'Success');
                table.ajax.reload( null, false ); 
                }else{
                toastr.error(i18n.msg.delete_failed, i18n.layout.error);
                }
            }

            var url= baseUrl+"admin/companies/delete/"+userID;
        
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