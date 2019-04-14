function update(event, href) {
    var divElement;
    //@ts-ignore
    divElement = $(event.target).parent().parent();
    //@ts-ignore
    var element = $(event.target).parent().siblings().first().children("span").first();
    var content = element.text();
    var submitButton = $('<button class="btn btn-block btn-info">valider</button>');
    var inputElement = $('<input type="text" class="form-control" value="' + content + '">');
    //@ts-ignore
    submitButton.on('click', function () {
        $.post(href, { nom: inputElement.val() }, function (data) {
            window.location.href = window.location.href;
        });
    });
    var firstDiv = $('<div></div>');
    var secondDiv = $('<div class="col-md-6 pr-0"></div>');
    divElement.children().remove();
    secondDiv.append(submitButton);
    firstDiv.append(inputElement);
    divElement.append(firstDiv, secondDiv);
}
function addCat(href) {
    if ($("#add_cat").val()) {
        $.post(href, { nom: $("#add_cat").val() }, function (data) {
            if (data == "yes")
                window.location.reload();
        });
    }
}
