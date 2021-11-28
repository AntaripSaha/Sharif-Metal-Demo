$('.transaction_submit').on('click', '#transaction_create', function(){
        var form=$('#transaction-add-form');
        var successcallback=function(a){
            toastr.success('Transaction Added Successfylly !!');
            var url= baseUrl+"bank/index";
            location.href = url;  
        }
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
});
  $('.card_buttons').on('click','.manage_bank',function(){
      var url= baseUrl+"bank/index";
      location.href = url;
  });
  $('.card_buttons').on('click', '.add_bank', function(){
      "use strict";
      var url= baseUrl+"bank/add";
      getAjaxModal(url);
  });