jQuery(function() {
	searchFocus();
	Slider.start();
	Sort.sort();
	Submenu.submenu();
});



var searchFocus = function(){
	jQuery('.head-srch-field').focus(function(){
		if(jQuery(this).val() == 'Поиск по каталогу') jQuery(this).val('');
		jQuery(this).addClass('srch-focus');
	});
};



//
Slider = function()
{
	var duration = 600;
	var interval = 9000;
	var src = [];
	
	function arr()
	{
		jQuery('.slider-img img').each(function(e){
			src[e] = jQuery(this).attr('src');
		});
	}
	
	function nextItem(n)
	{
		if( (Number(n)+1) < src.length ) n = Number(n)+1;
		else n = 0;
		return n;
	}
	
	function prevItem(n)
	{
		if( n > 0 ) n = Number(n)-1;
		else n = Number(src.length)-1;
		return n;
	}
	
	function nextSlide()
	{
		if(
		jQuery('.sl').eq(0).is(':animated') == false &&
		jQuery('.sl').eq(4).is(':animated') == false
		){
			jQuery('.sl').eq(0).animate({'width':'0'}, duration, function()
			{
				var i = nextItem( jQuery('.sl img').eq(4).attr('slider') );
				jQuery('.sl img').eq(0).attr('src', src[i]).attr('slider', i);
				jQuery('.sl').eq(0).appendTo(jQuery('.slider'));
				jQuery('.sl').eq(4).css('width','1000px');
			});
		}	
	}
	
	function prevSlide()
	{
		if(
		jQuery('.sl').eq(0).is(':animated') == false &&
		jQuery('.sl').eq(4).is(':animated') == false
		){
			jQuery('.sl').eq(4).css('width','0');
			var i = prevItem( jQuery('.sl img').eq(0).attr('slider') );
			jQuery('.sl img').eq(4).attr('src', src[i]).attr('slider', i);
			jQuery('.sl').eq(4).prependTo(jQuery('.slider'));
			jQuery('.sl').eq(0).animate({'width':'1000px'}, duration);
		}	
	}
	
	function arrange()
	{
			var i = 0;
		jQuery('.sl img').eq(2).attr('src', src[i]).attr('slider', i);
			i = nextItem(i);
		jQuery('.sl img').eq(3).attr('src', src[i]).attr('slider', i);
			i = nextItem(i);
		jQuery('.sl img').eq(4).attr('src', src[i]).attr('slider', i);
			i = Number(src.length)-1;
		jQuery('.sl img').eq(1).attr('src', src[i]).attr('slider', i);
			i = prevItem(i);
		jQuery('.sl img').eq(0).attr('src', src[i]).attr('slider', i);
	}
	
	function buttons()
	{
		// display buttons
		jQuery('.slider-prev, .slider-next').css('display', 'block');
		
		// next init
		jQuery('.slider-next').click(function(){ nextSlide(); return false; });
		
		// prev init
		jQuery('.slider-prev').click(function(){ prevSlide(); return false; });
	}
	
	function autoscroll()
	{
		intervalID = window.setInterval(nextSlide, interval);
		jQuery('.sliderbox').hover(
			function(){ clearInterval(intervalID) },
			function(){ intervalID = window.setInterval(nextSlide, interval) }
		);
	}
	
	function start()
	{
		if( jQuery('.slider-img img').length == 0 )
		{
			jQuery('.sliderbox').hide();
			return false;
		}
		if( jQuery('.slider-img img').length == 1 )
		{
			jQuery('.sl img').eq(2).attr('src', jQuery('.slider-img img').attr('src'));
			return false;
		}
		arr();
		arrange();
		buttons();
		autoscroll();
	}
	
	return {
		start: start
	}
}();



// drop sort-list for caralog
Sort = function()
{
	function sortDrop(){
		jQuery('.sort-list').each(function(){
			jQuery(this).hover(
				function(){
					jQuery(this).addClass('sort-list-hov');
					jQuery(this).find('.sort-drop').show();
				},
				function(){
					jQuery(this).removeClass('sort-list-hov');
					jQuery(this).find('.sort-drop').hide();
				}
			);
		});
	}

	return {
		sort: sortDrop
	}
}();



// Drop submenu in main menu
Submenu = function()
{
	function submenu(){
		jQuery('.cat-nav li').each(function(){
			if(jQuery(this).find('.cat-subnav').length)
			{
				jQuery(this).hover(
					function(){
						jQuery(this).addClass('cat-nav-selected');
					},
					function(){
						jQuery(this).removeClass('cat-nav-selected');
					}
				);
			}
		});
	}

	return {
		submenu: submenu
	}
}();