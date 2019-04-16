interface evenement {
    id: number,
    titre: string,
    adresse: string,
    urlImage: string
}
var truncateList: HTMLCollectionOf<Element>;
var truncateList2: HTMLCollectionOf<Element>;
var jsonArray: evenement[];

var eventsEnregistreReg = window.location.href.search(/(evenement\/events_enregistre)/);
var eventsOrganiseReg = window.location.href.search(/(evenement\/events_organise)/);
var eventsInscrisReg = window.location.href.search(/(evenement\/events_inscri)/);
var eventsReg = window.location.href.search(/(evenement\/(\?|$))/);

if (eventsEnregistreReg != -1) {
    if (!$("a[data-no=4]").parent().hasClass("custom-li"))
        $("a[data-no=4]").parent().addClass("custom-li");
}

if (eventsOrganiseReg != -1) {
    if (!$("a[data-no=3]").parent().hasClass("custom-li"))
        $("a[data-no=3]").parent().addClass("custom-li");
}

if (eventsInscrisReg != -1) {
    console.log($("a[data-no=2]"));
    if (!$("a[data-no=2]").parent().hasClass("custom-li"))
        $("a[data-no=2]").parent().addClass("custom-li");
}

if (eventsReg != -1) {
    if (!$("a[data-no=1]").parent().hasClass("custom-li"))
        $("a[data-no=1]").parent().addClass("custom-li");
}

truncateList = document.getElementsByClassName("truncate");
for (let i = 0; i < truncateList.length; i++) {
    truncateList[i].textContent = trunc(truncateList[i], 200);
}

truncateList2 = document.getElementsByClassName("truncate2");
for (let i = 0; i < truncateList2.length; i++) {
    truncateList2[i].textContent = trunc(truncateList2[i], 24);
}

function trunc(obj: Element, pos: number) {
    //@ts-ignore
    if (obj.textContent.length > pos)
        //@ts-ignore
        return obj.textContent.substr(0, pos).concat("....");
    return obj.textContent;
}

function search(href: string) {
    $.post(href, function (data) {
        jsonArray = JSON.parse(data);
        console.log(jsonArray);
    });
}

function fill(imageRef: string, path: string) {
    let filtredArray = [];
    let searchVal = <string>$("#search").val();
    let ulItem = $('#searchList ul');
    ulItem.html("");

    filtredArray = jsonArray.filter(function (e) {
        return e.titre.substring(0, searchVal.length).toUpperCase() == searchVal.toUpperCase();
    }).slice(0, 5);
    console.log(filtredArray);
    filtredArray.forEach((i) => {
        let date: Date;
        //@ts-ignore
        date = new Date(i.date.timestamp * 1000);
        let urlImage: string;
        if (i.urlImage)
            urlImage = imageRef + i.urlImage;
        else
            urlImage = imageRef + "default.png";
        let liItem = $('<li class="d-flex p-2 align-items-center border-bottom"></li>');
        let divItem1 = $('<div class="col-md-2"> <img src="' + urlImage + '" class="img-fluid"> </div>');
        let divItem2 = $('<div class="col-md-10"></div>');
        let divItem3 = $('<div class="d-flex justify-content-between"></div>');
        let spanItem = $('<span> ' + i.titre + '</span> ');
        let small1Item = $('<small class="text-muted">' + date.toLocaleDateString() + '</small>');
        let small2Item = $('<small class="text-muted"><i class="fas fa-map-marker-alt"></i> ' + i.adresse + '</small>');

        divItem3.append(spanItem, small1Item);
        divItem2.append(divItem3, small2Item);
        liItem.append(divItem1, divItem2);
        ulItem.append(liItem);
        liItem.hover(changeBack);
        liItem.click(function () {
            window.location.href = path.replace("999", String(i.id));
        })
    });
};

function changeBack() {
    $("#searchList li").hover(function () {
        $(this).css('background-color', '#ECECEC');
    },
        function () {
            $(this).css('background-color', 'white');
        })
}

$(document).ready(function () {

    $('.closeJs').click(function () {
        $(this).parent().slideUp(500);
    });
    $(document).click(function () {
        $(".hiddenItems").hide();
    });

    $("#search").click(function (e) {
        e.stopPropagation();
        $("#searchList").show();
    });

    //@ts-ignore
    $('#date_filtre').flatpickr({
        "enableTime": false,
    });
    //@ts-ignore
    var placesAutocomplete = places({
        appId: 'plJOKP8XE6QS',
        apiKey: '6a82a5c26661368dcc17e5f84ff981e3',
        container: document.querySelector('#region_filtre')
    });

});

