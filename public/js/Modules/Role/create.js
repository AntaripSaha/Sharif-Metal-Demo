$('.role_submit').on('click', '#add_role', function(){
        var form=$('#roles-add-form');
        var successcallback=function(a){
            toastr.success('Role Created Successfully', 'Success !!');
            $('#ajax-modal').modal('hide');
            if (a.is_admin == 1) {
            	var url= baseUrl+"roles/view/"+a.role;
        		getAjaxView(url,data=null,'ajaxview',false,'get'); 
            }else{
            	location.reload();
            }
        }
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
    });
