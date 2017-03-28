$(function(){
    //header-footer
    var top = $("header").outerHeight();
    var btm = $("footer").outerHeight();
//    console.log(top+' ; '+btm);
    $("body").css({paddingTop: top+'px', paddingBottom: btm+'px'});

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
        var src = $(this).attr("modal-target");
        $("body").append('<div class="modal-bg"></div>');
        $(".modal-bg").hide().fadeIn(function(){
            var html =
                '<div class="modal-content" style="top: '+($(window).scrollTop()+150)+'px">'+
                '   <span class="modal-close">&times;</span>'+
                '   '+$(src).html()+
                '</div>';
            $(this).append(html);
        });
    });
    $(document).on('click', '.modal-close', function(){ $(".modal-bg").fadeOut(function(){ $(this).remove() }) });

    //Tips
    $(".tips-trigger").hover(
        function(){
            var html = $(this).nextAll('.tips-target').html();
            $(this).parent().append('<div class="tips-content">'+html+'</div>');
            $(".tips-content").css({left: $(this).scrollLeft()+20+"px"}).hide().fadeIn();
        }, function(){
            $(".tips-content").fadeOut(100, function(){ $(this).remove() });
        }
    );

    //col-btn
    var trg  = $(".col-btn").attr("col-target");
    var btm  = $(window).height();
    var side = $(trg).innerWidth();
//    console.log(trg+btm+' ; '+side);
    $(".col-btn").css({top: (btm-60)+"px", left: "0px"});
    $(".col-btn").click(function(){
        var trg = $(this).attr("col-target");
        if($(trg).is(":hidden")){
            $(trg).fadeIn();
            $(this).removeClass('col-close');
        }else{
            $(trg).fadeOut();
            $(this).addClass('col-close');
        }
    });
})