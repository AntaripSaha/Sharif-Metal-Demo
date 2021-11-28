$('.fiscal_year_submit').on('click', '#add_fiscal_year', function(){
    var form=$('#fiscal-add-form');
    var successcallback=function(a){
        toastr.success('Fiscal Year Successfully Added', 'Success !!');
        $('#ajax-modal').modal('hide');
        
        if (a.is_admin == 1) {
            var url= baseUrl+"fiscalyears/index";
            getAjaxView(url,data=null,'ajaxview',false,'get'); 
        }else{
            var url= baseUrl+"fiscalyears/index";
        }
    }
    ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
});
