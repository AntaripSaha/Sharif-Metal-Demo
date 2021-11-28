$('.add_item').on('click', '#submit_additem', function(){
        var form=$('#item-add-form');
        var successcallback=function(a){
             toastr.success(i18n.menu.items+" "+i18n.msg.create_successfully, i18n.layout.success);
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
    });
