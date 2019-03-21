$(document).ready(function () {
    $('#evenementsbundle_evenement_date').flatpickr({
        "enableTime": true,
        "minDate": new Date().fp_incr(1)
    });
    $("#evenementsbundle_evenement_billetsRestants").attr({
        'type': 'number',
        'min': 1
    });
    $('#evenementsbundle_evenement_captcha').addClass("ml-2");
    $('form').submit(function (e) {
        if (!$('#termsAgree').is(':checked'))
            e.preventDefault();
    });

    $('.fadeOut').delay('5000').slideUp('300');

    var placesAutocomplete = places({
        appId: 'plJOKP8XE6QS',
        apiKey: '6a82a5c26661368dcc17e5f84ff981e3',
        container: document.querySelector('#evenementsbundle_evenement_adresse')
      });    
});

