var coords;

var items;
items = [$("#Userbundle_Demandes_titre"), $("#Userbundle_Demandes_date"), $("#Userbundle_Demandes_lieu")];
function submitForm(e, href) {
    var test = true;
    items.forEach(function (el) {
        if (!el.val()) {
            test = false;
            //@ts-ignore
            var elId = el.attr("id").substring(27);
            console.log(elId);
            el.addClass("is-invalid");
            $("#demandes_" + elId + "_help").removeClass("text-muted");
            $("#demandes_" + elId + "_help").css("color", "#dc3545");
        }
    });
    if (!$('#termsAgree').is(':checked')) {
        $('#termsAgree').addClass("is-invalid");
        test = false;
    }
    console.log(test);
    if (test) {
        //@ts-ignore
        if (coords)
            $("form").attr('action', href + "?lat=" + coords.lat + "&lng=" + coords.lng);
        console.log($("form").attr("action"));
    }
    else {
        e.preventDefault();
    }
}
;
$(document).ready(function () {
    //@ts-ignore
    $('#Userbundle_Demandes_date').flatpickr({
        "enableTime": true,
        //@ts-ignore
        "minDate": new Date().fp_incr(1)
    });
    $('.fadeOut').delay(5000).slideUp('300');
    //@ts-ignore
    var placesAutocomplete = places({
        appId: 'plWZT7SVWEWC',
        apiKey: '71baa5bff421b2cabec711500fb2ca53',
        container: document.querySelector('#Userbundle_Demandes_lieu')
    });
    //@ts-ignore
    placesAutocomplete.on('change', function (e) {
        coords = e.suggestion.latlng;
        console.log(coords);
    });
    items.forEach(function (el) {
        el.blur(function (e) {
            if (el.val()) {
                //@ts-ignore
                var elId = el.attr("id").substring(27);
                console.log(elId);
                el.removeClass("is-invalid");
                el.addClass("is-valid");
                $("#Demandes_" + elId + "_help").css("color", "#28a745");
            }
        });
    });
    $('#termsAgree').change(function (e) {
        if ($(this).is(":checked")) {
            $(this).removeClass("is-invalid");
        }
    });

});
