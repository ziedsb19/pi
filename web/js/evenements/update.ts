var coords;

let items : JQuery<HTMLInputElement>[];
items = [$("#evenement_edit_titre"), $("#evenement_edit_date"), $("#evenement_edit_adresse")] ;

$(document).ready(function () {
    
    //@ts-ignore
    $('#evenement_edit_date').flatpickr({
        "enableTime": true,
        //@ts-ignore
        "minDate": new Date().fp_incr(1)
    });

    //@ts-ignore
    var placesAutocomplete = places({
        appId: 'plJOKP8XE6QS',
        apiKey: '6a82a5c26661368dcc17e5f84ff981e3',
        container: document.querySelector('#evenement_edit_adresse')
      });    
     
    //@ts-ignore
    placesAutocomplete.on('change',function(e){
        coords= e.suggestion.latlng;
        console.log("test"+coords);
    });
          
    items.forEach((el)=>{
        el.blur((e)=>{
            if (el.val()){
                //@ts-ignore
                let elId =<string> el.attr("id").substring(15);
                console.log(elId)
                el.removeClass("is-invalid");
                el.addClass("is-valid");
                $("#evenement_"+elId+"_help").css("color","#28a745");
            }
        });
    });
    
    $('#termsAgree').change(function(e){
        if ($(this).is(":checked")){
            $(this).removeClass("is-invalid");
        }
    });

});