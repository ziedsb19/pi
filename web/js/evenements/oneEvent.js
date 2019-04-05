var pElt = document.getElementById('new_line');
//@ts-ignore
var re = new RegExp(/\./, 'g');
console.log(re);
//@ts-ignore
pElt.innerHTML = pElt.textContent.replace(re, ".<br>");
function bookmark(event, id, href) {
    var iElt = event.target;
    var divElt = $(".bookmark-info").first();
    $(iElt).toggleClass("fas");
    $(iElt).toggleClass("far");
    console.log($(".bookmark-info strong"));
    $.post(href, { id: id }, function (data) {
        if (data == "saved")
            $(".bookmark-info strong").first().html("evenement enregistre au favoris");
        if (data == "deleted")
            $(".bookmark-info strong").first().html("evenement supprimé du favoris");
        divElt.slideDown(400).delay(3000).slideUp(400);
    });
}
