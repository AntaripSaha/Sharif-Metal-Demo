    $('.edit_item').on('click', '#submit_edititem', function(){
        var form=$('#item-edit-form');
        var successcallback=function(a){
             toastr.success(i18n.menu.items+" "+i18n.msg.update_successfully, i18n.layout.success);
            $('#ajax-modal').modal('hide');
            location.reload();
        }
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
  });