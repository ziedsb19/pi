function signaler(event: Event, href: string){
    event.preventDefault();
    let formElement = <HTMLFormElement>event.target;
    let textAreaElement = $(formElement).children().children("textarea");
    //@ts-ignore
    if (textAreaElement.val().length <25){
        if (!$(textAreaElement).hasClass("is-invalid"))
            $(textAreaElement).addClass("is-invalid");
        $(textAreaElement).siblings('small').first().removeClass("text-muted");
        $(textAreaElement).siblings('small').first().css('color','#dc3545');    
    }
    else {
        if ($(textAreaElement).hasClass("is-invalid"))
            $(textAreaElement).removeClass("is-invalid");
         if (!$(textAreaElement).siblings('small').first().hasClass("text-muted")) 
            $(textAreaElement).siblings('small').first().addClass("text-muted");  
        $.post(href, $(formElement).serialize(), function(data){
            console.log(data);
            //@ts-ignore
            $(formElement).parent().parent().parent().parent().modal('hide');
            $("#alert-report").delay(1500).slideDown(400).delay(3000).slideUp(400);
        }); 
    }
}

function showShare(element : HTMLElement, event: Event){
    let shareButton= <HTMLButtonElement> element;
    let divId= shareButton.dataset.id;
    $(".hiddenItems").hide();
    event.stopPropagation();
    console.log(divId);
    $('.share_link[data-id="'+divId+'"]').toggle();
}

function copy(event: Event){
    event.stopPropagation();
    //@ts-ignore
    let inputElement = $(event.target).parent().siblings().first();
    inputElement.select();
    document.execCommand('copy');
}


$(document).ready(function () {
});