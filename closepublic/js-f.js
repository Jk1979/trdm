$( document ).ready(function() {
    
    
    /*   Меню каталога  */
    $(".smallmenu_icon").click(function(e){
      $(this).parent().find($(".menu__dropsmall")).toggle();
      e.preventDefault();
    });
    // if(location.href.substr(7)=='trdm/')
    if((location.href.substr(7)=='trademag.com.ua/') || (location.href=='trademag.com.ua/') || (location.href=='trademag.com.ua'))
    {
        $('.side').css('display','block');
        $('.side').css('position','static');
        $('.side').css('opacity','0.99');
      
   }
    else{
       $('.catmainbut').on('mouseenter click',function(e){
            $('.side').toggle();
            e.preventDefault();
                    });
        $('.catmainbutmob').on('click',function(e){
            $('.side').toggle();
            e.preventDefault();
                    });
       $('.side').on('mouseleave',function(e){

                        $('.side').hide();

                    }
          );
       $(document).on('mousemove click',function(e)
        {    

            var $this = $(e.target);
                if($('.side').css('display')==='block')
                {
                    if(!$this.closest('.parent__menublock').length)
                    {
                        $('.side').hide();
                    }
                }
                
        });

    }
     $(document).on('mousemove click',function(e)
        {    

            var $this = $(e.target);
              
                if($('.cart_sub').css('display')==='block')
                {
                    if(!$this.closest('.parent__cartblock').length)
                    {
                        $('.cart_sub').hide();
                    }
                }
        });
    /* Меню каталога  */
    
$("#searchprod").keyup(function () {
        var value = $(this).val();
         var loc = document.location.href;
        var locarr = loc.split('/');
        var locbase = locarr[0] + "/product/";
    
        $("#listProds").empty();
        if(value.length==0 || value.length==1 ) $('.toggled_block').hide();
        if(value.length>1)
        { 
                $.ajax({
              url: '/ajax/getindexprods/' + value,
              dataType: 'json',
              type: 'GET',
              error: function (jqXHR, textStatus, errorThrown) {alert('get prod error');},
              success: function(result,textStatus,jqXHR)
                {
                    if(result)
                    {
                        $('.toggled_block').show();
                        for(var i in result) 
                        {

                            $("#listProds").append('<li class="li_link"><a href="'+ locbase  + result[i].path +'">'
                            + result[i].title +'</a></li>');
                        }
                    }
                    else { $('.toggled_block').hide(); }
                }
            
                });
            }
});
    
    
 $(document).click( function(e) {
    if (!$(e.target).closest(".parent_block").length) {
    $('.toggled_block').hide();
    }
  e.stopPropagation();
});    

/*checkfilters();*/


// equal heights

/*function heightses() {
  $(".newmenu_title").height('auto').equalHeights();
 }
 $(window).resize(function() {
  heightses();
 });

 heightses();*/
 /*   console.log($(".fl-set>ul").outerHeight(false));
if($(".fl-set>ul").outerHeight()>499){
    $(".fl-set>ul").css("overflow-y","scroll");
}*/
    
});



jQuery(function() {
	Sort.sort();
});


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

function SortProducts(value){
	href = window.location.toString().split('?');
	new_href = '?sort=' + value;
	if(href.length > 1){
		tmp = href[1].split('&');
		if(tmp.length > 1){
			new_href = '?' + tmp[0] + '&' + tmp[1] + '&sort=' + value;
		}
	}
	window.location = new_href;
}
function checkfilters()
{
    var checked = []; 
    $(".optid").each(function(i,elem){
        var id = $(this).data('opid');
        checked.push(id);
    });
    var cat = $("#catid").html();
    var str = checked.join(';');
    
         if(str && catid)
            { 
                    $.ajax({
                  url: '/ajax/getfilterprods/' + cat + '/' + str,
                  dataType: 'json',
                  type: 'GET',
                  error: function (jqXHR, textStatus, errorThrown) {console.log('check filter error');},
                  success: function(result,textStatus,jqXHR)
                    {
                        if(result)
                        {

                            for(var i in result) 
                            {
                                var elem = '.optid';
                                console.log(result[i]);
                                
                              $(elem).data('opid', result[i]).parent('li').hide(); 
                                
                            }
                        }

                    }

                    });
                }

    
}

