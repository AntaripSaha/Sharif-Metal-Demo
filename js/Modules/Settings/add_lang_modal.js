  $('document').ready(function(){
                "use strict";
               $('.country_submit').on('click', '#add_country', function(){
        var form=$('#country-update-form');
        var successcallback=function(a){
            toastr.success(a.msg, 'success!');
            $('#ajax-modal').modal('hide');
            
              $( ".active" ).trigger( "click" );
        }
        ajaxValidationFormSubmit(form.attr('action'),form.serialize(),'',successcallback);
      
            });
            });