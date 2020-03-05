
$( document ).ready(function() {



    $("input[class='prodindex_price']").on('change', function ()
		{

		 var inp = $(this), newprice = $(this).val();
		 var id = inp.data('id');
		 newprice = $.trim(newprice); 
		 
		 $.ajax({
		  url: '/admin/products/changeprice/'+ id +'?newprice=' + newprice,
		  dataType: 'text',
		  type: 'GET',
		  error: function (jqXHR, textStatus, errorThrown) {alert('change price error');},
		  success:
		   function (data)
		   {
		    inp.val(data);
		   }
		 });
		}
		);
        
  /*  Pop up для загрузки изображений в списке товаров*/  
  $('.popup-content, .popup-filter').magnificPopup({
        type: 'inline'
    });
  $('.popup-content').click(function(e){
     $('#product_id').val($(this).data('id'));
     $('#uploaded-files').hide();
     $('#saveimgok').html('');
  });  
  
  $('#saveprodimg').click(function(e){
      var id = $('#product_id').val();
      var imagelong = $('#selectedFile').val();
      var imagear = imagelong.split('/');
      var image = imagear[imagear.length-1];
      
      if(id && image)
      {
        $.ajax({
           url: '/admin/products/saveprodimg/'+ id +'?image=' + image,
           dataType: 'html',
           type: 'GET',
           error: function (jqXHR, textStatus, errorThrown) {alert('Error ajax saving img function');},
           success:
            function (data)
            {
             if (data != 0)
             {
                $('#saveimgok').html('ok'); 
             }
             else
              alert('Error saving image in the product');

            }
          }); 
    }
  });  
    $('#upserver').click(function(e){
      var id = $('#product_id').val();
      var image = $('#customimg').val();
     
      
      if(id && image)
      {
        $.ajax({
           url: '/admin/products/saveprodimg/'+ id +'?image=' + image,
           dataType: 'html',
           type: 'GET',
           error: function (jqXHR, textStatus, errorThrown) {alert('Error ajax saving img function');},
           success:
            function (data)
            {
             if (data != 0)
             {
                $('#saveimgservok').html('ok'); 
             }
             else
              alert('Error saving image in the product');

            }
          }); 
    }
  });
  
  $("#customimg").keyup(function () {
        var value = $(this).val();
             
        $("#listimgs").empty();
        if(value.length<3 ) $('.togg_block').hide();
        else
        { 
                $.ajax({
              url: '/admin/products/getlistimgs/' + value,
              dataType: 'json',
              type: 'GET',
              error: function (jqXHR, textStatus, errorThrown) {alert('get imglist error');},
              success: function(result,textStatus,jqXHR)
                {
                    if(result)
                    {
                        $("#listimgs").append('<li class="li_link">tm_</li>');
                        for(var i in result) 
                        {
                            
                            $("#listimgs").append('<li class="li_link">'
                            + result[i].title +'</li>');
                            
                        }
                        $('.togg_block').show();
                        
                    }
                    else { $('.togg_block').hide(); }
                }
            
                });
            }
});
$("#listimgs").on('click', 'li',function () {
    $("#customimg").val($(this).html());
    
});

/* filters */
$('.popup-filter').click(function(e){
    $('#prodtitle').html($(this).data('prodtitle'));
    $('#brand').html($(this).data('brand'));
    $('#prodimg').html('<a target="_blank" href="'+$(this).data('img')+'"><img width="100px" src="'+$(this).data('img')+'"></a>');
     $('#prod_id').val($(this).data('id'));
     var prod = $(this).data('id');
     var category = $(this).data('catid'); /*$('#categoryid').val();*/
     $('#savefilterok').html('');
     if(prod && category)
     {
        getfilteroptionsEdit(category, prod);
     }
     else {
        $('#savefilterok').html('Не выбрана категория с фильтрами');
        $('#subfilter').attr('disabled','disabled');
     }
  });

$('#subfilter').click(function(e){
    var checked = [];
    $(':checkbox:checked').each(function () {
        checked.push($(this).val());
            
});
var id = $('#prod_id').val();
var str = checked.join(';');
      $.ajax({
           url: '/admin/ajaxprods/setfilters/'+ id +'/' + str,
           dataType: 'html',
           type: 'GET',
           error: function (jqXHR, textStatus, errorThrown) {alert('Error ajax saving filters');},
           success:
            function (data)
            {
             if (data != 0)
             {
                $('#savefilterok').html('ok'); 
             }
             else
              alert('Error saving image in the product');

            }
          });   
});
  
});