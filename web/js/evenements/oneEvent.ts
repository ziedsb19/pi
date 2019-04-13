var pElt = <HTMLElement>document.getElementById('new_line');
//@ts-ignore
var re = new RegExp(/\./, 'g');
var fileInput = <HTMLInputElement>document.getElementById('file');
var fileList: File[] = [];
//@ts-ignore
pElt.innerHTML = pElt.textContent.replace(re, ".<br>");

function bookmark(event: Event, id: number, href: string) {
    let iElt = <HTMLElement>event.target;
    let divElt = $(".bookmark-info").first();
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
    let fileL = fileInput.files;
    if (fileL)
        for (let i = 0; i < fileL.length; i++) {
            let reader = new FileReader();
            //@ts-ignore
            fileList.push(fileL.item(i));
            //@ts-ignore
            reader.readAsDataURL(fileL.item(i));
            reader.onload = function (e) {
                let xtimes = $('<div class="d-flex justify-content-center align-items-center" ><i class="fas fa-times" style="display: none;"></i></div>');
                //@ts-ignore
                let image = $('<img src="' + e.target.result + '" width="100%" height="100" />');
                let div = $('<div class="p-0 m-1" style="position: relative; width: 22%;"></div>')
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
        }
    showButt();
})

function showButt() {
    if (fileList.length != 0)
        $("#submit_images").show();
    else
        $("#submit_images").hide();
}

function submitImages(href: string) {
    let data = new FormData();
    fileList.forEach((f) => {
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
function deleteImage(e, href: string) {
    $(e.target).parent().parent().remove();
    $.get(href, function (data) {
        console.log(data);
    });
}

function signalerEvent(event: Event, href: string) {
    event.preventDefault();
    let formElement = <HTMLFormElement>event.target;
    let textAreaElement = $(formElement).children().children("textarea");
    //@ts-ignore
    if (textAreaElement.val().length < 25) {
        if (!$(textAreaElement).hasClass("is-invalid"))
            $(textAreaElement).addClass("is-invalid");
        $(textAreaElement).siblings('small').first().removeClass("text-muted");
        $(textAreaElement).siblings('small').first().css('color', '#dc3545');
    }
    else {
        if ($(textAreaElement).hasClass("is-invalid"))
            $(textAreaElement).removeClass("is-invalid");
        if (!$(textAreaElement).siblings('small').first().hasClass("text-muted"))
            $(textAreaElement).siblings('small').first().addClass("text-muted");
        $.post(href, $(formElement).serialize(), function (data) {
            $(".bookmark-info strong").first().html("evenement signalé avec succes");
            $(".bookmark-info").first().slideDown(400).delay(3000).slideUp(400);
        });
        //@ts-ignore
        $("#signalModal").modal('hide');
        $("#signal_butt").attr("disabled", "true");
    }
}

function copy(event: Event) {
    event.stopPropagation();
    //@ts-ignore
    let inputElement = document.getElementById('copy_link');
    console.log(inputElement);
    //@ts-ignore
    inputElement.select();
    document.execCommand('copy');
}

function toggleInscri(event: Event, href: string) {
    let buttElt = <HTMLButtonElement>event.target;
    let spanElt = <HTMLSpanElement>document.getElementById('inscri_nbr');
    let dataId = buttElt.dataset.id;
    let nbrInscri = Number(spanElt.textContent);
    if (dataId == "0") {
        $(buttElt).html();
        $(buttElt).html('<i class="fas fa-times-circle"></i> annuler');
        buttElt.dataset.id = "1";
        spanElt.textContent = String(nbrInscri + 1);
    }
    else {
        $(buttElt).html();
        $(buttElt).html('<i class="fas fa-check"></i> inscrire');
        buttElt.dataset.id = "0";
        spanElt.textContent = String(nbrInscri - 1);
    }
    $.get(href, function (data) {
    });
}

$('document').ready(function () {
    $("#share").click(function (e) {
        console.log($("#share_div"));
        $("#sh_div").toggle();
        e.stopPropagation();
    });
})