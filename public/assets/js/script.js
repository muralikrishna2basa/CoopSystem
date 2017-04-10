CNT = 0;
function windowSizing()
{
    var top = $("header").outerHeight();
    var btm = $("footer").outerHeight();
    var win = $(window).height();
    var siz = win - top - btm;

    var trg  = $(".col-btn").attr("col-target");
    var btm  = $(window).height();
    var side = $(trg).innerWidth();
    $(".col-btn").css({left: (side-35)+"px"});

}

function checkEdit()
{
    if(CNT > 0) return confirm("データは更新されませんが別のページに移動してよろしいですか？");
    return true;
}
$(function(){
    //sizing
    windowSizing();
    $(window).resize(function(){ windowSizing() });

    //Draggable
    $(".draggable").draggable({ containment: 'body' });

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
    $(".modal-btn").click(function(e){
        e.preventDefault();
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
    $(document).on('click', '.modal-close, .modal-close-btn', function(){ $(".modal-bg").fadeOut(function(){ $(this).remove() }) });

    //Tips
    $(".tips-trigger").hover(
        function(){
            // var html = $(this).nextAll('.tips-target').html();
            // if(html === void 0)
            // {
            var html = $(this).children('.tips-target').html();
            if(html === void 0) html = $(this).next('.tips-target').html();

            // }
            $('.tips-content').html(html);
        }, function(){
            $('.tips-content').html('<h3 class="text-center">ここにヒントが表示されます。このメッセージはドラッグできます。</h3>')
        });
    $('.tips-btn').click(function(){
        if($('.tips-content').is(":hidden"))
        {
//            console.log('hide');
            $('.tips-content').fadeIn();
        }else{
//            console.log('show');
            $('.tips-content').fadeOut();
        }
    })

    //col-btn
    $(".col-btn").click(function(){
        var trg = $(this).attr("col-target");
        if($(trg).is(":hidden")){
            $(trg).fadeIn(200);
            var side = $(trg).innerWidth();
            $(this).removeClass('col-close').css({left: (side-35)+'px'});
        }else{
            $(trg).fadeOut(200);
            $(this).addClass('col-close').css({left: '0px'});
        }
    });

    // logout btn
    $('body,html').click(function(){ if(!$('.logout-content').is(':hidden')) $('.logout-content').hide() });
    $('.logout-menu').click(function(){
        $('.logout-content').show();
        return false;
    });

    // forms count
    $("form").change(function(){ CNT++; console.log(CNT) })

})

