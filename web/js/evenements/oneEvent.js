var pElt = document.getElementById('new_line');
//@ts-ignore
var re = new RegExp(/\./, 'g');
var fileInput = document.getElementById('file');
var fileList = [];
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
function addImage() {
    $("input[type=file]").click();
}
$(fileInput).change(function (e) {
    var fileL = fileInput.files;
    if (fileL) {
        var _loop_1 = function (i) {
            var reader = new FileReader();
            //@ts-ignore
            fileList.push(fileL.item(i));
            //@ts-ignore
            reader.readAsDataURL(fileL.item(i));
            reader.onload = function (e) {
                var xtimes = $('<div class="d-flex justify-content-center align-items-center" ><i class="fas fa-times" style="display: none;"></i></div>');
                //@ts-ignore
                var image = $('<img src="' + e.target.result + '" width="100%" height="100" />');
                var div = $('<div class="p-0 m-1" style="position: relative; width: 22%;"></div>');
                div.append(image, xtimes);
                $("#images-container").append(div);
                xtimes.click(function () {
                    div.remove();
                    //@ts-ignore
                    fileList.splice(fileList.indexOf(fileL.item(i)), 1);
                    showButt();
                });
                div.hover(function () {
                    xtimes.addClass('times');
                    xtimes.children().show();
                }, function () {
                    xtimes.children().hide();
                    xtimes.removeClass('times');
                });
            };
        };
        for (var i = 0; i < fileL.length; i++) {
            _loop_1(i);
        }
    }
    showButt();
});
function showButt() {
    if (fileList.length != 0)
        $("#submit_images").show();
    else
        $("#submit_images").hide();
}
function submitImages(href) {
    var data = new FormData();
    fileList.forEach(function (f) {
        data.append('file[]', f);
    });
    jQuery.ajax({
        url: href,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function (data) {
            console.log(data);
            if (data == "yes")
                location.reload();
        }
    });
}
$('#images > div').hover(function (e) {
    $(e.target).siblings().first().addClass("times");
    $(e.target).siblings().first().children().first().show();
}, function (e) {
    console.log(e.target);
    $(e.target).removeClass("times");
    $(e.target).children().first().hide();
});
//@ts-ignore
function deleteImage(e, href) {
    $(e.target).parent().parent().remove();
    $.get(href, function (data) {
        console.log(data);
    });
}
