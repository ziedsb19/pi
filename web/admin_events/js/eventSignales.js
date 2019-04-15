$(document).ready(function () {
    $("#my_table i").click(function (event) {
        var trElt = $(event.target).parent().parent();
        if ($("tr.temp").length == 0) {
            trElt.after("<tr class='temp'><td colspan='6'><strong>Description: </strong>" + trElt.attr("data-desc") + " </td></tr>");
        }
        else {
            $("tr.temp").remove();
        }
    });
});
