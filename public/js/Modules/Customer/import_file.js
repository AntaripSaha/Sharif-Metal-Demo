    $('#file-upload'). change(function(e){
        var name = $(this).val().split('\\').pop();
        $('#filename').text(name);
        
    });

    $('.import_submit').on('click', '#import_file', function(){
        
        var thisBtn = $('#import_file');
        var thisForm = thisBtn.closest("form");
        var formData = new FormData(thisForm[0]);
        var url = baseUrl+"customer/import_file";
        var successcallback=function(a){
            
            toastr.success('Party Imported Successfully !!');
            $('#ajax-modal').modal('hide');
            location.reload(); 
        }; 
    $.ajax({
        type: "POST",
        url: url,
        data: formData,
        processData: false,
        contentType: false,
        success:function(data){

            if(data.status=='success')
            {
                successcallback(); 
            }
        },
        error:function(json){
            var error='';
             var errors = json.responseJSON;                
            $.each(json.responseJSON.errors, function (key, value) {
                error+=value+'<br>';
               
            });
             toastr.error('Error !! Try again');
             
        }
    });
    });