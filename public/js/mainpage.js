$(document).ready(function(){
    $('#slider').nivoSlider({pauseTime:6000});
   /* var owlSlider = $("#owl-slider");



   owlSlider.owlCarousel({
        items : 1,
        loop: true,
        singleItem : true,
        slideSpeed: 1000,
       autoplay: true,
       autoplayTimeout: 3500,
        navigation: true,
        navText:["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
        pagination:true,
        dots: false,
        //dotsClass:'slider__pager',
        responsive: true,
        responsiveRefreshRate : 200,
        responsiveBaseWidth: window,
    });*/
    $('.owl-carousel').owlCarousel({loop:true,nav:true,navText:["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],responsive:{0:{items:1,},520:{items:1,},560:{items:1,},768:{items:2,},992:{items:3,},1200:{items:4,}}});
    //$('.owl-carousel').on("mousewheel",function(e){if(e.deltaY>0){$(".owl-next").trigger("click");}else{$(".owl-prev").trigger("click");}e.preventDefault();});

    /*$(".slide-image").height('auto').equalHeights();
    $(".slide-title").height('auto').equalHeights();*/
    setTimeout(function () {
        $(".slide-image").height('auto').equalHeights();
        $(".slide-title").height('auto').equalHeights();
    },1000);

});