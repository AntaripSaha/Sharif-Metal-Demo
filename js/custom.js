Array.prototype.sample = function(){
  return this[Math.floor(Math.random()*this.length)];
}
var data =  document. getElementById("baseurl").textContent;
var baseUrl = data.trim();
var XCSRFTOKEN = $('meta[name=csrf-token]').attr('content');  
//Phone Number Code Change
       
    function changeNumberCode(country_code){
        var callback=function(data){
            //console.log(data);
            $('#phone_code').val(data.country_code);
        }
        if(country_code){
            let url = baseUrl+"/country-code/"+country_code;
            console.log(url);
            ajaxGetRequest(url,callback);
        }else{
            $('#phone_code').val('---');
        }
    }


 /*random color*/   
function random_rgba() {
    var o = Math.round, r = Math.random, s = 255;
    return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' + r().toFixed(1) + ')';
}

function read_URL(input,i) {
    if (input.files && input.files[0]) {
        id = input.id;
          
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#'+id+'-img').attr('src', e.target.result);
            $('#'+id+'-value').val(e.target.result);
        }  
        reader.readAsDataURL(input.files[0]);    
    }
}
