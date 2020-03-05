function CartTotalElements()
{
 $.ajax({
  url: '/cart/GetTotalElements',
  data: "", 
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
    $('#cart_totalelements').html(data + ' ' + client_cart_things);
   }
 });
}

function CartTotalSum()
{
 $.ajax({
  url: '/cart/GetTotalSum',
  //data: "",  
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
    $('#cart_totalsum').html(data + ' ' + client_cart_currency_grn);
   }
 });
}

function CartReload()
{
 $.ajax({
  url: '/cart/reload',
  dataType: 'html',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
    
    var content = $(data).find('#cart_content');
    $('#cart_content').html(content);
   }
 });
}

function ElementAdd(element_id, element_count)
{
  $.ajax({
  url: '/cart/addElement/' + element_id + "/" + element_count,
  //data: "/"+element_id+"/"+element_count,
  dataType: 'html',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
    if (data != 0)
    {
        var sum = $(data).filter('#totalsum');
        var countitems = $(data).filter('#totalitems');
        $('#cart_totalsum').html(sum.html());
        $('#cart_totalitems').html(countitems.html());
        $('.cart_nums').html(countitems.html());
        opendialog();
//     var temp = {state0: {html: data, buttons: {}, focus: 1}}
//     $.prompt(temp, {prefix: 'cart_add_'});
    //alert(data);
    //    opendialog();
    }
    else
     alert(client_cart_e02);
    
   }
 });
}
function ElementEdit(element_id, element_count)
{
  $.ajax({
  url: '/cart/ElementEdit/' + element_id + "/" + element_count,
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
    if (data != 0)
    {
        var result = JSON.parse( data );
          
//          var element_sum  = '#element_' + element_id + '_price_total';
//          var element_price  = '#element_' + element_id + '_price';
//          var cart_sum_all = '#cart_sum_all';
          
          $('#sumorder').val(result.sum.toFixed(2));
          $('#cart_totalsum').html(result.sum);
          $('#cart_totalitems').html(result.count);
          $('.cart_nums').html(result.count);
//          var sum = element_count * $(element_price).html();
//          $(element_sum).html(sum);
//          $(cart_sum_all).html(result.sum);
        CartReload();
    }
    else
     alert(client_cart_e02);
    
   }
 });
}

function ElementDelete(element_id)
{
 $.ajax({
  url: '/cart/ElementDelete/' + element_id,
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
	if (data != 0)
	{
     var result = JSON.parse( data ); 
     $('#sumorder').val(result.sum);
     $('#cart_totalsum').html(result.sum);
     $('#cart_totalitems').html(result.count);
     $('.cart_nums').html(result.count);
         
	 CartReload();
        
          
   }
	else
	 alert(client_cart_e03);
   }
 });
}

function mustAdress()
{
    if($('#delivery').val()!=1)
        $('#adress').attr('required','required');
    else $('#adress').removeAttr("required");
}

function opendialog(){
        $(".overlay").css('visibility','visible');
        $(".overlay").css('opacity','1');
        $(".prodaddform").fadeIn(); //плавное появление блока
}

function closedialog(){
   $(".prodaddform").fadeOut(); //плавное исчезание блока
   $(".overlay").css('visibility','hidden');
   $(".overlay").css('opacity','0');
}
 
$(document).ready(function(){
    $('#cart_button').on('mouseenter click',function(e){
        if($('#cart_totalitems').html() == '0 ')
            {
                $('.cart_sub_content').hide();
                $('.cart_empty').show();
            }
        else {
            $('.cart_sub_content').show();
            $('.cart_empty').hide();
        }
         if($('.cart_sub').css('display')!=='block')
         {
            $('.cart_sub').toggle();
             e.preventDefault();
         }
        $('#cart_button').click(function(e){
            $('.cart_sub').toggle();
             e.preventDefault();
        }); 
    });
   
   

$(".cart_clear").click(function(e){
    $.ajax({
  url: '/cart/ajaxclear/',
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert('cart_clear error');},
  success:
  function (data)
  {   
    $('#cart_totalsum').html('0 ');
    $('#cart_totalitems').html('0 ');
    $('.cart_nums').html('0 ');
     CartReload();  
   }
 });
    e.preventDefault();
}); 


});