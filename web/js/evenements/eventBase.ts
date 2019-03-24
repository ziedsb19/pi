interface evenement{
    id: number,
    titre: string,
    adresse: string,
    urlImage: string
}

var truncateList : HTMLCollectionOf<Element>;
var jsonArray : evenement[];    

truncateList = document.getElementsByClassName("truncate");
for (let i=0; i<truncateList.length; i++){
    //@ts-ignore
    if (truncateList[i].textContent.length>200){
        //@ts-ignore
        truncateList[i].textContent= truncateList[i].textContent.substr(0,200).concat("...");
    }
}

function search(href : string){
    $.post(href,function(data){
        jsonArray= JSON.parse(data);
        console.log(jsonArray);        
    });
}

$("#search").keyup(function(){
    let filtredArray=[];
    let searchVal =<string> $(this).val();
    filtredArray = jsonArray.filter(function(e){
        return e.titre.substring(0,searchVal.length)==searchVal;
    }).slice(0,5);
    console.log(filtredArray);
});

$(document).ready(function () {
    $('.closeJs').click(function(){
        $(this).parent().slideUp(500);
    });
    
});

