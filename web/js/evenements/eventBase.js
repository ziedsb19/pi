var truncateList;
var jsonArray;
truncateList = document.getElementsByClassName("truncate");
for (var i = 0; i < truncateList.length; i++) {
    //@ts-ignore
    if (truncateList[i].textContent.length > 200) {
        //@ts-ignore
        truncateList[i].textContent = truncateList[i].textContent.substr(0, 200).concat("...");
    }
}
function search(href) {
    $.post(href, function (data) {
        jsonArray = JSON.parse(data);
        console.log(jsonArray);
    });
}
function fill(imageRef) {
    var filtredArray = [];
    var searchVal = $("#search").val();
    var ulItem = $('#searchList ul');
    ulItem.html("");
    filtredArray = jsonArray.filter(function (e) {
        return e.titre.substring(0, searchVal.length) == searchVal;
    }).slice(0, 5);
    console.log(filtredArray);
    filtredArray.forEach(function (i) {
        var date;
        //@ts-ignore
        date = new Date(i.date.timestamp * 1000);
        var liItem = $('<li class="d-flex p-2 align-items-center border-bottom"></li>');
        var divItem1 = $('<div class="col-md-2"> <img src="' + imageRef + i.urlImage + '" class="img-fluid"> </div>');
        var divItem2 = $('<div class="col-md-10"></div>');
        var divItem3 = $('<div class="d-flex justify-content-between"></div>');
        var spanItem = $('<span> ' + i.titre + '</span> ');
        var small1Item = $('<small class="text-muted">' + date.toLocaleDateString() + '</small>');
        var small2Item = $('<small class="text-muted"><i class="fas fa-map-marker-alt"></i> ' + i.adresse + '</small>');
        divItem3.append(spanItem, small1Item);
        divItem2.append(divItem3, small2Item);
        liItem.append(divItem1, divItem2);
        ulItem.append(liItem);
        liItem.hover(changeBack);
    });
}
;
function changeBack() {
    $("#searchList li").hover(function () {
        $(this).css('background-color', '#ECECEC');
    }, function () {
        $(this).css('background-color', 'white');
    });
}
$(document).ready(function () {
    $('.closeJs').click(function () {
        $(this).parent().slideUp(500);
    });
    $("#search").click(function (e) {
        e.stopPropagation();
        $("#searchList").show();
    });
    $(document).click(function () {
        $("#searchList").hide();
    });
});
