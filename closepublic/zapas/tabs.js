  $(function(){
    //first tabs
    $('div#tabs_1 ul').delegate('li:not(.selected)', 'click', function() {
      $(this).addClass('selected')
        .siblings().each(function(){
          $(this).removeClass('selected');
      });
      $('div#tabs_1_content > div').hide().eq($(this).index()).fadeIn(150);
    });
        
    //second tabs
    $('div#tabs_2_content > div').hide();
    $('div#tabs_2_content').find('div:first').show();
    
    $('div#tabs_2 ul').delegate('li:not(.selected)', 'click', function() {
      $(this).addClass('selected')
        .siblings().each(function(){
          $(this).removeClass('selected');
      });
      $('div#tabs_2_content > div').hide().eq($(this).index()).fadeIn(150);
    });
  
  });
  
