var truncateList = document.getElementsByClassName("truncate");
for (i=0; i<truncateList.length; i++){
    if (truncateList[i].textContent.length>200){
        truncateList[i].textContent= truncateList[i].textContent.substr(0,200).concat("...");
    }
}

$(document).ready(function () {
    $('.closeJs').click(function(){
        $(this).parent().slideUp(500);
    });
    
});

