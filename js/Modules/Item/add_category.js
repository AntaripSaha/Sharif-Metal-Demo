$('.add_category').on('click', '#submit_addcategory', function(){
    var form=$('#itemcategory-add-form');
    var successcallback=function(a){
         toastr.success(i18n.menu.items+" "+i18n.msg.create_successfully, i18n.layout.success);
            $('#ajax-modal').modal('hide');
            location.reload();
    }
    ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
});