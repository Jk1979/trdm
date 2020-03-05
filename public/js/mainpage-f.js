$(document).ready(function() {
//Каруселька
	//Документация: http://www.jqueryscript.net/slider/nivo-slider.html
    
    $('#slider').nivoSlider(
	{ pauseTime: 6000 }
    );
    //Каруселька
	//Документация: http://owlgraphic.com/owlcarousel/
	
	$('.owl-carousel').owlCarousel({
		loop:true,
		nav: true,
		navText: ["<i class='fa fa-angle-left'></i>","<i class='fa fa-angle-right'></i>"],
		responsive:{
				0:{
						items:1,
				},
				520:{
						items:1,
				},
				560:{
						items:1,
				},
				768:{
						items:2,
				},
				992:{
						items:3,
				},
				1200:{
						items:4,
				}
		}
});
$('.owl-carousel').on("mousewheel",  function (e) {
		if (e.deltaY > 0) {
			$(".owl-next").trigger("click");
		} else {
			$(".owl-prev").trigger("click");
		}
		e.preventDefault();
	});
	
});
