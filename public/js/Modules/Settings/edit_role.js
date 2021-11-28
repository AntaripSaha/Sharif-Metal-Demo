 $('document').ready(function(){
                "use strict";
           $('.table').on('click', '.edit-tr', function(){
        var rowid  = this.id.replace('edit-tr-', '');
        var url= baseUrl+"settings/role_edit/"+rowid;
           
        getAjaxView(url,data=null,'tab_setting',false,'get');
      
        
    });

             });