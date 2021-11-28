 $('.edit_customer_form').on('click', '#edit_customer', function(){
        var form=$('#customer-edit-form');
        var successcallback=function(a){
            toastr.success(i18n.customers.customer+" "+i18n.msg.update_successfully,  i18n.layout.success);
            $('#ajax-modal').modal('hide');
            var id = $('#form_submited').val();
            var cus_id = $('#form_submited').attr('class').replace('customer-id-', '');
            if (id == 1) {
                table.ajax.reload( null, false );
                var url= baseUrl+"customer/statistics/";
                
                getAjaxView(url,data=null,'statistics',false,'get');
            }else{
                var url= baseUrl+"customer/view/"+cus_id;
               
                getAjaxView(url,data=null,'ajaxview',false,'get');
            }
        }
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
    });