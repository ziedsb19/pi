function signaler(event, href) {
    event.preventDefault();
    var formElement = event.target;
    var dataId = formElement.dataset.id;
    var textAreaElement = $(formElement).children().children("textarea");
    var buttElt = $("button.sig_butt[data-id=" + dataId + "]");
    //@ts-ignore
    if (textAreaElement.val().length < 25) {
        if (!$(textAreaElement).hasClass("is-invalid"))
            $(textAreaElement).addClass("is-invalid");
        $(textAreaElement).siblings('small').first().removeClass("text-muted");
        $(textAreaElement).siblings('small').first().css('color', '#dc3545');
    }
    else {
        buttElt.attr('disabled', 'true');
        if ($(textAreaElement).hasClass("is-invalid"))
            $(textAreaElement).removeClass("is-invalid");
        if (!$(textAreaElement).siblings('small').first().hasClass("text-muted"))
            $(textAreaElement).siblings('small').first().addClass("text-muted");
        $.post(href, $(formElement).serialize(), function (data) {
            console.log(data);
            //@ts-ignore
            $(formElement).parent().parent().parent().parent().modal('hide');
            $(".bookmark-info strong").first().html("evenement signale avec succés");
            $(".bookmark-info").first().slideDown(400).delay(3000).slideUp(400);
        });
    }
}
function bookmark(event, id, href) {
    var iElt = event.target;
    var divElt = $(".bookmark-info").first();
    $(iElt).toggleClass("fas");
    $(iElt).toggleClass("far");
    $.post(href, { id: id }, function (data) {
        if (data == "saved")
            $(".bookmark-info strong").first().html("evenement enregistre au favoris");
        if (data == "deleted")
            $(".bookmark-info strong").first().html("evenement supprimé du favoris");
        divElt.slideDown(400).delay(3000).slideUp(400);
    });
}
function showShare(element, event) {
    var shareButton = element;
    var divId = shareButton.dataset.id;
    $(".hiddenItems").hide();
    event.stopPropagation();
    console.log(divId);
    $('.share_link[data-id="' + divId + '"]').toggle();
}
function copy(event) {
    event.stopPropagation();
    //@ts-ignore
    var inputElement = $(event.target).parent().siblings().first();
    inputElement.select();
    document.execCommand('copy');
}
$(document).ready(function () {
    //@ts-ignore
    $(".adresse").hover(function (e) {
        var target = e.target;
        var targetId = target.dataset.id;
        var mapDiv = $(".maps[data-id='" + targetId + "']");
        mapDiv.val("");
        $(target).css("text-decoration", "underline");
        $(mapDiv).show();
        var latLng = mapDiv.attr("data-latlng");
        if (latLng) {
            var array = latLng.split('/').map(function (i) { return Number(i); });
            //@ts-ignore
            var map = L.map(mapDiv.attr('id'), {
                center: array,
                zoom: 13
            });
            //@ts-ignore
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            //@ts-ignore    
            L.marker(array).addTo(map);
        }
    }, function (e) {
        var target = e.target;
        var targetId = target.dataset.id;
        $(target).css("text-decoration", "none");
        $(".maps[data-id='" + targetId + "']").hide();
    });
});
