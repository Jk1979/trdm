$( document ).ready(function() {
    
    
    $("#editBrand").keyup(function () {
        var value = $(this).val();
        var loc = document.location.href;
        var locarr = loc.split('/');
        var locserie = locarr[0] + '/admin/series/';
        $("#listBrands").empty();
        if(value.length==0 || value.length==1 ) $('.toggled_block').hide();
        if(value.length>1)
        {
                $.ajax({
              url: '/ajax/getbrand/' + value,
              dataType: 'json',
              type: 'GET',
              error: function (jqXHR, textStatus, errorThrown) {alert('get brand error');},
              success: function(result,textStatus,jqXHR)
                {
                    if(result)
                    {
                        $('.toggled_block').show();
                        for(var i in result) 
                        {

                            $("#listBrands").append('<li class="li_link"><a  href="'+ loc + "/edit/" + result[i].id +'">'
                            + result[i].title +'</a>' + '    <a href="'+ locserie  + result[i].id +'">'
                            + 'серии</a></li>');
                        }
                    }
                    else { $('.toggled_block').hide(); }
                }
            
                });
            }
});
    $("#editProd").keyup(function () {
        var value = $(this).val();
        var loc = document.location.href;
        $("#listProds").empty();
        if(value.length==0 || value.length==1 ) $('.toggled_block').hide();
        if(value.length>1)
        {
                $.ajax({
              url: '/ajax/getprod/' + value,
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

                            $("#listProds").append('<li class="li_link"><a href="'+ loc + "/edit/" + result[i].id +'">'
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


});

function genpath(path){
 var find = new Array('А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я','а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',' ','.',',','0','1','2','3','4','5','6','7','8','9','!','@','#','$','%','^','&','*','(',')','-','+','=',':',';','\'','"','<','>','?','і','ї','є','~','№','`','|','/','\\','{','}','[',']','™');
 var replace = new Array('a','b','v','g','d','e','yo','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','i','','e','yu','ya','a','b','v','g','d','e','yo','zh','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','','i','','e','yu','ya','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','_','_','_','0','1','2','3','4','5','6','7','8','9','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','i','ji','je','_','_','_','_','_','_','_','_','_','_','_');

 path = $.trim(path);
 var res = '', character, flag;
 for (i=0; i < path.length; i++){
  character = path.charAt(i,1);
	
  flag = false;
  for (j=0; j < find.length; j++)
   if(character == find[j]){
    flag = true;
		break;
   }
	 
  if (flag)
   res += replace[j];
  else
   res += character;
 }
 return res;
}
function getfilteroptionsEdit(category, prod)
{
 $.ajax({
  url: '/ajax/getfilteroptions/' + category + '/' + prod,
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert('get filter options error');},
  success:
  function (data)
  {   
	  $("#filterOptions").empty();
      if (data != 0)
      {
          $('#filterOptions').append( data );
      }
       
   }
 });
}	

function getfilteroptions(category)
{
 $.ajax({
  url: '/ajax/getfilteroptions/' + category,
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert('get filter options error');},
  success:
  function (data)
  {   
	  $("#filterOptions").empty();
      if (data != 0)
      {
          $('#filterOptions').append( data );
      }
       
   }
 });
}


function GetCtsubbrands(ctbrand_id)
{
 $.ajax({
  url: '/ajax/getsubbrands/' + ctbrand_id,
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert('get ctproducts error');},
  success:
  function (data)
  {
	  $("#ctseries_id").empty();
	  $("#ctseries_id").append( $('<option value="0">Без серии</option>'));
      if (data != 0)
      {
          $('#ctseries_id').append( data );
      }
       
   }
 });
}





