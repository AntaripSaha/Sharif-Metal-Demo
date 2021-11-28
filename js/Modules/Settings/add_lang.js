  $('document').ready(function(){
                "use strict";
               
           $('.group-add').on('click', '#add_new_language', function(){
       
              var url= baseUrl+"settings/add_lang";
           getAjaxModal(url);
             });
            });