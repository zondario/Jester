$(function(){
    $('[data-toggle="tooltip"]').tooltip();
    $(".side-nav .collapse").on("hide.bs.collapse", function() {
        $(this).prev().find(".fa").eq(1).removeClass("fa-angle-right").addClass("fa-angle-down");
    });
    $('.side-nav .collapse').on("show.bs.collapse", function() {
        $(this).prev().find(".fa").eq(1).removeClass("fa-angle-down").addClass("fa-angle-right");
    });
    var string = ""
    document.querySelectorAll("td>a").forEach( function(el){if(el.textContent != "Mix" && el.textContent != "Shades" && el.textContent.indexOf("#"))
    {
       string+=el.textContent + ",";
    }});
    window.clipboardData = string;
}) 