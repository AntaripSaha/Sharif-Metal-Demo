$(function() {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })

})
    $('.company_list').on('change', '#company_id', function(){    
        var company_id = $(this).children("option:selected").val();
        var url= baseUrl+"admin/modules/company/"+company_id;
        getAjaxView(url,data=null,'module_permissions',false,'get');
    });