$(function(){
    //Draggable
    $(".draggable").draggable({ containment: $(this).attr("data-area") });

    //Toggle
    $(".toggle-menu dt").each(function(){
        $(this).next().hide();
        $(this).prepend('<span class="toggle-icon"></span>');
    });
    $(".toggle-menu dt").click(function(){
        $(this).next().slideToggle();
        var icon = $(this).children(".toggle-icon");
        if(icon.hasClass("toggle-open")){ icon.removeClass("toggle-open") }else{ icon.addClass("toggle-open") }
    });

    //Modal
    $(".modal-btn").click(function(){
        console.log($(this).attr("modal-target"));
        $("body").append('<div class="modal-bg"></div>');
    });
})