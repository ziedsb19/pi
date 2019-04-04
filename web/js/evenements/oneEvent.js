var pElt = document.getElementById('new_line');
//@ts-ignore
var re = new RegExp(/\./, 'g');
console.log(re);
//@ts-ignore
pElt.innerHTML = pElt.textContent.replace(re, ".<br>");
