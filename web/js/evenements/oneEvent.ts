var pElt = <HTMLElement> document.getElementById('new_line');
//@ts-ignore
var re= new RegExp(/\./, 'g');
var fileInput = <HTMLInputElement>document.getElementById('file');
var fileList : File[]= [];
//@ts-ignore
pElt.innerHTML=  pElt.textContent.replace(re,".<br>");

function bookmark(event:Event ,id : number, href:string){
    let iElt = <HTMLElement>event.target;
    let divElt = $(".bookmark-info").first();
    $(iElt).toggleClass("fas");
    $(iElt).toggleClass("far");
    console.log($(".bookmark-info strong"));
    $.post(href, {id: id}, function(data){
        if (data=="saved")
            $(".bookmark-info strong").first().html("evenement enregistre au favoris");
        if (data=="deleted")    
            $(".bookmark-info strong").first().html("evenement supprimé du favoris");
        divElt.slideDown(400).delay(3000).slideUp(400);    
    });
}

function addImage(){
    $("input[type=file]").click();
}

$(fileInput).change(function(e){
    let fileL = fileInput.files;
    if (fileL)
        for (let i=0; i<fileL.length; i++){
            let reader = new FileReader();
            //@ts-ignore
            fileList.push(fileL.item(i));
            //@ts-ignore
            reader.readAsDataURL(fileL.item(i));
            reader.onload = function(e){
                let xtimes = $('<div class="d-flex justify-content-center align-items-center" ><i class="fas fa-times" style="display: none;"></i></div>');
                //@ts-ignore
                let image = $('<img src="'+e.target.result+'" width="100%" height="100" />');
                let div = $('<div class="p-0 m-1" style="position: relative; width: 22%;"></div>' )
                div.append(image, xtimes);
                $("#images-container").append(div);
                xtimes.click(function(){
                    div.remove();
                    //@ts-ignore
                    fileList.splice(fileList.indexOf(fileL.item(i)),1);
                    showButt();
                });
                div.hover(function(){
                    xtimes.addClass('times');
                    xtimes.children().show();
                },function(){
                    xtimes.children().hide();
                    xtimes.removeClass('times');
                });
            };
        }
        showButt();   
})

function showButt(){
    if (fileList.length!=0)
        $("#submit_images").show();
    else 
        $("#submit_images").hide();
}

function submitImages(href: string){
    let data = new FormData();
    fileList.forEach((f)=>{
        data.append('file[]',f);
    });
    jQuery.ajax({
        url: href,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        type: 'POST',
        success: function(data){
            console.log(data);
            if (data=="yes")
                location.reload();
        }
    });

}

$('#images > div').hover(function(e){
    $(e.target).siblings().first().addClass("times");
    $(e.target).siblings().first().children().first().show();
},function(e){
    console.log(e.target);
    $(e.target).removeClass("times");
    $(e.target).children().first().hide();
}); 

//@ts-ignore
function deleteImage (e,href: string){
    $(e.target).parent().parent().remove();
    $.get(href,function(data){
        console.log(data);
    });
}