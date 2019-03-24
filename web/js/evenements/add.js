$(document).ready(function () {
    //@ts-ignore
    $('#evenementsbundle_evenement_date').flatpickr({
        "enableTime": true,
        //@ts-ignore
        "minDate": new Date().fp_incr(1)
    });
    $('.fadeOut').delay(5000).slideUp('300');
    //@ts-ignore
    var placesAutocomplete = places({
        appId: 'plJOKP8XE6QS',
        apiKey: '6a82a5c26661368dcc17e5f84ff981e3',
        container: document.querySelector('#evenementsbundle_evenement_adresse')
    });
    var prixE;
    prixE = $('#evenementsbundle_evenement_prix');
    var items;
    items = [$("#evenementsbundle_evenement_titre"), $("#evenementsbundle_evenement_date"), $("#evenementsbundle_evenement_adresse")];
    $('form').submit(function (e) {
        items.forEach(function (el) {
            if (!el.val()) {
                e.preventDefault();
                //@ts-ignore
                var elId = el.attr("id").substring(27);
                console.log(elId);
                el.addClass("is-invalid");
                $("#evenement_" + elId + "_help").removeClass("text-muted");
                $("#evenement_" + elId + "_help").css("color", "#dc3545");
            }
        });
        if (!$('#termsAgree').is(':checked')) {
            $('#termsAgree').addClass("is-invalid");
            e.preventDefault();
        }
        if (prixE.hasClass("is-invalid"))
            e.preventDefault();
    });
    items.forEach(function (el) {
        el.blur(function (e) {
            if (el.val()) {
                //@ts-ignore
                var elId = el.attr("id").substring(27);
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
    $('#evenementsbundle_evenement_prix').keyup(function (e) {
        var prix = prixE.val();
        if (!/(^\d*$)|(^\d+\.\d*$)/.test(prix)) {
            if (!prixE.hasClass("is-invalid"))
                prixE.addClass("is-invalid");
        }
        else {
            prixE.removeClass("is-invalid");
        }
    });
});
