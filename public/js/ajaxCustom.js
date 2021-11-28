$(document).ajaxError(function (response) {

    if (response.status === 500) {

        toastMessage(i18n.layout.error + '!', 'error', i18n.layout.layout.please_contact_us_to_report_this_issue);
        location.reload();

    }
});


function getAjaxModal(url, dataid = {}, ajaxclass = '#modal-ajaxview', callback = false, data = {}, modalclass = '#ajax-modal', method = 'get') {
    $.ajax({
        url: url,
        type: method,
        data: data,
        beforeSend: function (xhr) {

        },
        success: function (result) {

            $(modalclass).modal('show');
            if (callback) {
                callback(result);
                return;
            }
            $(ajaxclass).html(result);

        },
        error: function (a, b) {

            toastr.error(Lang.get('layout.please_contact_us_to_report_this_issue'), Lang.get('layout.error') + '!');
            console.log(a);
            console.log(b);
        }
    });
}

function getAjaxView(url, data = {}, ajaxclass, callback = false, method = 'get') {
    $.ajax({
        url: url,
        type: method,
        data: data,
        beforeSend: function (xhr) {

        },
        success: function (result) {
            if (callback) {
                callback(result);
                return;
            }
            $('#' + ajaxclass).html(result);
        },
        error: function (a) {
            toastr.error(Lang.get('layout.please_contact_us_to_report_this_issue'), Lang.get('layout.error') + '!');
                      
        }
    });
    return false;
}

function getAjaxViewAppend(url, data = {}, ajaxclass, callback = false, method = 'get') {
    $.ajax({
        url: url,
        type: method,
        data: data,
        beforeSend: function (xhr) {

        },
        success: function (result) {
            if (callback) {
                callback(result);
                return;
            }
            $('#' + ajaxclass).append(result);
        },
        error: function (a) {
            toastr.error(Lang.get('layout.please_contact_us_to_report_this_issue'), Lang.get('layout.error') + '!');       
        }
    });
    return false;
}

function ajaxGetRequest(url, successCallback = '', errorCallback = '', datatypes = 'json') {

    $.ajax({
        url: url,
        type: 'Get',
        dataType: datatypes,
        success: function (result) {
            if (successCallback) {
                successCallback(result);
                return;
            }
            toastr.success("Updated Successfully!", "Success !!!");
        },
        error: function (a) {
            if (errorCallback) {
                errorCallback(a);
                return;
            }
            toastr.error(Lang.get('layout.please_contact_us_to_report_this_issue'), Lang.get('layout.error') + '!');
        }
    });
}

function ajaxValidationFormSubmit(url, data = {}, submitId = '', successCallback) {
    
    var erroCallback = function (json) {
        var error = '';
        if (json.status == 422) {
            var errors = json.responseJSON;
            $.each(json.responseJSON.errors, function (key, value) {
                error += value + '<br>';
            });
        }
        if (json.status == 500) {
            error = json.responseText;
            console.log(error);
        }
        if (json.status == 401) {
            error = 'permission denied';
        }
        toastr.error(error, Lang.get('layout.error') + '!');

    };
    if (!successCallback) {
        successCallback = function () {
            toastr.success("Updated Successfully!", "Success!!!");
        };
    }
    ajaxFormSubmit(url, data, '', successCallback, erroCallback)
}


function ajaxFormSubmit(url, data = {}, submitId = '', successCallback, errorCallback) {

    $.ajax({
        url: url,
        type: 'POST',
        data: data,
        dataType: 'json',
        beforeSend: function (xhr) {
            $('span.error').html('');
        },
        success: function (result) {
            if(result.status == 'success'){
                // location.reload();
                console.log(result.status);
            }
            if (successCallback) {
                successCallback(result);
                return;
            }
        },
        error: function (a) {
            
            if (errorCallback) {
                errorCallback(a);
                return;
            }
        }
    });
}

function getAjaxdata(url, successCallback, method = 'get') {
    $.ajax({
        url: url,
        type: method,
        beforeSend: function (xhr) {

        },
        success: function (result) {
            if (successCallback) {
                successCallback(result);
                return;
            }
        },
        error: function (a) {
            toastr.error(Lang.get('layout.please_contact_us_to_report_this_issue'), Lang.get('layout.error') + '!');         
        }
    });
    return false;
}


function confirmAlert(callback = '', content = '', title = '',cbtext='') {

    swal({
            title: title,
            text: content,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: cbtext,
            cancelButtonText: "No, Cancel please!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function (isConfirm) {
            if (isConfirm) {
                callback();
                swal("Deleted!", "Your imaginary file has been deleted.", "success");
            } else {

                swal("Cancelled", "Your imaginary file is safe :)", "error");

            }
        });
}
