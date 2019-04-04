var coords;
var items;
items = [$("#evenement_edit_titre"), $("#evenement_edit_date"), $("#evenement_edit_adresse")];
function submitForm(event) {
    var test = true;
    items.forEach(function (el) {
        if (!el.val()) {
            test = false;
            //@ts-ignore
            var elId = el.attr("id").substring(15);
            el.addClass("is-invalid");
            $("#evenement_" + elId + "_help").removeClass("text-muted");
            $("#evenement_" + elId + "_help").css("color", "#dc3545");
        }
    });
    if (!$('#termsAgree').is(':checked')) {
        $('#termsAgree').addClass("is-invalid");
        test = false;
    }
    if (!test) {
        event.preventDefault();
    }
    else {
        var href = window.location.href;
        //@ts-ignore
        if (coords) {
            var form = event.target;
            //@ts-ignore
            $(form).attr('action', href + "?lat=" + coords.lat + "&lng=" + coords.lng);
        }
    }
}
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
    placesAutocomplete.on('change', function (e) {
        coords = e.suggestion.latlng;
        console.log(coords);
    });
    placesAutocomplete.on('clear', function (e) {
        coords = { lat: 9999, lng: 9999 };
        console.log(coords);
    });
    items.forEach(function (el) {
        el.blur(function (e) {
            if (el.val()) {
                //@ts-ignore
                var elId = el.attr("id").substring(15);
                console.log(elId);
                el.removeClass("is-invalid");
                el.addClass("is-valid");
                $("#evenement_" + elId + "_help").css("color", "#28a745");
            }
        });
    });
    $('#termsAgree').change(function (e) {
        if ($(this).is(":checked")) {
            $(this).removeClass("is-invalid");
        }
    });
});
