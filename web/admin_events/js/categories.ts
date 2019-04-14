function update(event: Event, href: string) {
    let divElement: JQuery<HTMLDivElement>;
    //@ts-ignore
    divElement = $(event.target).parent().parent();
    //@ts-ignore
    let element = $(event.target).parent().siblings().first().children("span").first();
    let content = <string>element.text();
    let submitButton = $('<button class="btn btn-block btn-info">valider</button>');
    let inputElement = $('<input type="text" class="form-control" value="' + content + '">');
    //@ts-ignore
    submitButton.on('click', function () {
        $.post(href, { nom: inputElement.val() }, function (data) {
            window.location.href = window.location.href;
        });
    });
    let firstDiv = $('<div></div>');
    let secondDiv = $('<div class="col-md-6 pr-0"></div>');
    divElement.children().remove();
    secondDiv.append(submitButton);
    firstDiv.append(inputElement);
    divElement.append(firstDiv, secondDiv);
}

function addCat(href: string) {
    if ($("#add_cat").val()) {
        $.post(href, { nom: $("#add_cat").val() }, function (data) {
            if (data == "yes")
                window.location.reload();
        });
    }
}