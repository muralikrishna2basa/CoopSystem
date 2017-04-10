$(function(){
//     $(".ordering-plus").click(function(e){  e.preventDefault(); setPrice($(this), 1) });
//     $(".ordering-minus").click(function(e){ e.preventDefault(); setPrice($(this), -1) });
// })
// function separate(num)
// {
//     return String(num).replace( /(\d)(?=(\d\d\d)+(?!\d))/g, '$1,');
// }
// function getPrice(price)
// {
//     return price.replace(/,/g, '') * 1;
// }

// function setPrice(selector, sign)
// {
//     var trgPrice = selector.parent().attr('data-price');
//     var trgTotal = selector.parent().attr('data-total');
//     var trgNum   = selector.parent().attr('data-number');
//     var trgDisp  = selector.parent().attr('data-display');
//     var trgStock = selector.parent().attr('data-stock');
//     var num      = document.getElementById(trgNum).defaultValue * 1;
//     if(num + sign < 0) return;
//     var log =
//         'trgPrice:'+trgPrice+"\n"+
//         'trgTotal:'+trgTotal+"\n"+
//         'trgNum  :'+trgNum+"\n"+
//         'trgDisp :'+trgDisp
//     ;
// //    console.log(log);
//         // 在庫数の計算
//         if(trgStock !== void 0)
//         {
//             var stock        = $('#'+trgStock).html() * 1;
//             if(stock - sign < 0)
//             {
//                 alert('これ以上在庫がありません。');
//                 return false;
//             }
//             $('#'+trgStock).html(stock - sign);
//         }

//         // 現在の発注個数を取得してカウントアップ hidden要素に値をセット
//         num += sign;
//         document.getElementById(trgNum).defaultValue = num;

//         // 表示される個数を再計算
//         $('#'+trgDisp).html(num);

//         // 合計金額を再計算
//         if(trgPrice !== void 0)
//         {
//             // var price    = getPrice($('#'+trgPrice).html());
//             var unit     = getPrice($('#'+trgPrice).html());
//             var price    = getPrice($('#price_total').html());
//             price += sign * unit;
//             $('#'+trgTotal).html(separate(unit * num));
//             $('#price_total').html(separate(price));
//         }
// //        $('#price_total').html('0');
//         // $("form [id *= 'total_']").each(function(){
//         //     var total = getPrice($('#price_total').html());
//         //     var price = getPrice($(this).html());
//         //     $('#price_total').html(separate(total + price));
//         // });
    }