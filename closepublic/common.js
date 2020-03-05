$(document).ready(function() {
   $(".auth_buttons").click(function() {
		$(this).next().slideToggle();
	});
	$("#topmnu").click(function() {
		$("#topnav").slideToggle();
	});
    $("#menucat").click(function() {
		$(".main_mnu ul").slideToggle();
	});
     $('#scrollup img').mouseover( function(){
		$( this ).animate({opacity: 0.65},100);
	}).mouseout( function(){
		$( this ).animate({opacity: 1},100);
	}).click( function(){
		$('body, html').animate({
        scrollTop: 0
      }, 600);
	});
	$(window).scroll(function(){
		if ( $(document).scrollTop() > 0 ) {
			$('#scrollup').fadeIn('fast');
		} else {
			$('#scrollup').fadeOut('fast');
		}
	});

$(function(){
    $('.icon').click(function(e) {
        e.preventDefault();
        var maina = $('#main_icon a').attr('href');
        var maindata = $('#main_icon a').attr('data-img');
        var src = $(this).attr('src');
        var name = $(this).data('img');
        var srcfull = src.replace(/_small/g, '');
        srcfull = srcfull.replace(/small_/g, '');
        $('#main_icon img').attr('src', src);
        $('#main_icon a').attr('href', srcfull);
        $('.addimg [data-img="'+name+'"]').attr('href',maina);
        $('.addimg [data-img="'+name+'"]').attr('data-img',maindata);
    });
});

    var prodprice;
    
    prodprice = parseFloat($('.prod-price-c').html());
        if(prodprice<1)
        {
            $(".prod-price-c").parent().html('');
            $(".prod_price").css('margin-left','10px');
            
        }
    $(".catprice").each(function(){
            var pr;
            pr = parseFloat($(this).html());
            if(pr<1) {
                $(this).parent().html('Цену уточняйте!').css('font-size','16px');
            }
        });

    $(".showfilter").click(function ( e ) {
        if($(this).html() == 'Показать фильтр')
        {
             $(this).html('Скрыть фильтр');

            $(this).next().removeClass('hidden-xs');
            $(this).next().next().removeClass('hidden-xs');
        }
        else {
            $(this).html('Показать фильтр');
            $(this).next().addClass('hidden-xs');
            $(this).next().next().addClass('hidden-xs');
        }
    });
});

