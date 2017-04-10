const server = "http://localhost:8000/";
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    let activeCategory = $("title").text().trim();
    for(let a of $(".side-nav li a")){
        let text = $(a).text();
        if(text===activeCategory) {
            $(a).parent().attr('class', 'active');
            $(a).css('color','black')
            $(a).css('background-color',"darkorange")
        }

    }
});