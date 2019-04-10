function signaler(event: Event, href: string) {
    event.preventDefault();
    let formElement = <HTMLFormElement>event.target;
    let dataId = formElement.dataset.id;
    let textAreaElement = $(formElement).children().children("textarea");
    let buttElt = $("button.sig_butt[data-id=" + dataId + "]");
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

function bookmark(event: Event, id: number, href: string) {
    let iElt = <HTMLElement>event.target;
    let divElt = $(".bookmark-info").first();
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

function showShare(element: HTMLElement, event: Event) {
    let shareButton = <HTMLButtonElement>element;
    let divId = shareButton.dataset.id;
    $(".hiddenItems").hide();
    event.stopPropagation();
    console.log(divId);
    $('.share_link[data-id="' + divId + '"]').toggle();
}

function copy(event: Event) {
    event.stopPropagation();
    //@ts-ignore
    let inputElement = $(event.target).parent().siblings().first();
    inputElement.select();
    document.execCommand('copy');
}


$(document).ready(function () {
    //@ts-ignore
    $(".adresse").hover((e: Event) => {
        let target = <HTMLElement>e.target;
        let targetId = target.dataset.id;
        let mapDiv = $(".maps[data-id='" + targetId + "']");
        mapDiv.val("");
        $(target).css("text-decoration", "underline");
        $(mapDiv).show();
        let latLng = <string>mapDiv.attr("data-latlng");
        if (latLng) {
            let array = latLng.split('/').map((i) => { return Number(i) });
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
    },
        (e: Event) => {
            let target = <HTMLElement>e.target;
            let targetId = target.dataset.id;
            $(target).css("text-decoration", "none");
            $(".maps[data-id='" + targetId + "']").hide();
        });
});