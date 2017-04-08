const server = "http://localhost:8000";
let div = $("<div><p></p></div>");

$.ajax(
    {
        url:server+"/"
    }
).done(function ($response) {
div.text($response);
div.appendTo($("#view"));
});
