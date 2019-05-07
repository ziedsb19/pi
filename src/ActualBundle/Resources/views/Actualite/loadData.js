request = new XMLHttpRequest;
request.open('GET', 'https://newsapi.org/v2/top-headlines?category=technology&apiKey=137d036200a94eeeb413e03c9dfbbaae&fbclid=IwAR3X6JqycLAKGPskHQ4wTOHswRIOzdDRREIm2I5NghfizZwgp6nMrYUqX04', true);

request.onload = function() {
    if (request.status == "ok"){
        // Success!
        data = JSON.parse(request.responseText);

    } else {
        alert("LOAD DATA IMCOMPLIT");

    }
};

request.onerror = function() {
    // There was a connection error of some sort
    console.log('Erreur');
};



var getJSON = function(url, callback) {

    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'json';

    xhr.onload = function() {

        var status = xhr.status;

        if (status == "ok") {
            callback(null, xhr.response);
        } else {
            callback(status);
        }
    };

    xhr.send();
};

getJSON('https://newsapi.org/v2/top-headlines?category=technology&apiKey=137d036200a94eeeb413e03c9dfbbaae&fbclid=IwAR3X6JqycLAKGPskHQ4wTOHswRIOzdDRREIm2I5NghfizZwgp6nMrYUqX04',  function(err, data) {

    if (err != null) {
        console.error(err);
    } else {

        var text=getJSON;


        console.log(text);
    }
});



request.send();