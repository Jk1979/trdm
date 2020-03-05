

function getCartInfo(elem, id , count)
{
    var url = '/cart/getcartinfo';
    if ((id !== undefined) && (count!== undefined)) {
        url += '/' + id + '/' + count;
    }
    $.ajax({
        url: url,
        dataType: 'json',
        type: 'GET',
        error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
        success:
            function (data)
            {
                    $(elem).html('');
                    $(elem).append(gettxt(data));
                    var sum = data.totalsum;
                    var countitems = data.totalelements;
                    $('#cart_totalsum').html(sum);
                    $('#cart_totalitems').html(countitems);
                    $('.cart_nums').html(countitems);
                    var urlar = (window.location.href).split('/');
                    var path = urlar[urlar.length-1];
                    if(path.toLowerCase() == 'cart') CartReload();

                if(data.totalelements == 0)
                {
                    $(elem).html(' В корзине пусто !');
                }
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
function gettxt(data)
{
    if(data)
    {
       var str='', units='шт';
        for(k in data)
        {
            if(is_numeric(k))
            {


               str += ' <div class="row cart-row">\
                    <div class="col-md-2 col-sm-2 col-xs-12 cart-img-prod">\
                    <a href="#"><img src="'+ data[k]['img']+'" alt=""></a>\
                    </div>\
                    <div class="col-md-5 col-sm-4 col-xs-12 cart-desc-prod">\
                    <a href="/product/'+ data[k]['path']+'">'+ data[k]['title']+'</a>\
                    <p>'+ data[k]['price']+' <span>грн.</span></p>\
                    </div>\
                    <div class="col-md-2 col-sm-2 col-xs-12 cat-qt-prod">\
                    <input type="text" data-id="'+ k +'" class="cart-num" name="qnt" onchange="editmodal('+ k +',this.value,false)" autocomplete="off" value="'+ data[k]['count']+'">\
                    <div class="left">\
                    <div class="cart-m" onclick="editmodal('+ k +',1,1)"></div>\
                    <div class="cart-pl" onclick="editmodal('+ k +',1,2)"></div>\
                    </div>\
                    <span>'+ data[k]['units']+' </span>\
                    </div>\
                    <div class="col-md-2 col-sm-2 col-xs-12 cart-sum-prod">\
                    <div class="cs-cost"><span id="offer_sum">'+ data[k]['sum']+'</span><span class="valuta"> грн</span></div>\
                    </div>\
                    <div class="col-md-1 col-sm-1 col-xs-12 cart-del-prod">\
                    <a href="#" title="Удалить из корзины" class="delet"  data-id="'+ k +'" onclick="deleteprodfrommodal('+ k +');return false;"></a>\
                    </div>\
                    </div>';

            }
        }
        str+='<div class="row all-prods-row">\
            <div class="col-md-9 col-sm-6  col-xs-12 all-prods-text">\
        Всего товаров: <span id="total_qnt">'+ data['totalelements']+'</span> на  </div><div class="col-md-3 col-sm-6 col-xs-12">\
        <div class="cs-cost"><span id="total_sum">'+ data['totalsum']+'</span><span class="valuta"> грн</span></div></div>\
        </div>';
        return str;
    }
}

function deleteprodfrommodal(id) {

    getCartInfo('#prodadd_content', id , 0);
}
function editmodal(id,count,inc){

    var newcount = parseFloat($('input[data-id="'+ id +'"]').val().replace(/,/, '.'));
    console.log(newcount);
    if(inc==1) newcount+=count;
    if(inc==2) newcount-=count;
    if(!inc) newcount=count;

    getCartInfo('#prodadd_content', id , newcount);
}


function ElementAdd(element_id, element_count)
{
  $.ajax({
  url: '/cart/addElement/' + element_id + "/" + element_count,
  headers: {
      'Cache-Control': 'no-cache, no-store, must-revalidate',
      'Pragma': 'no-cache',
      'Expires': '0'
  },
  dataType: 'json',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
    if (data != 0)
    {

        var sum = data.totalsum;
        var countitems = data.totalelements;
        $('#cart_totalsum').html(sum);
        $('#cart_totalitems').html(countitems);
        $('.cart_nums').html(countitems);

                //var el = document.getElementById('prodadd_content');
                var txt = gettxt(data);
                //el.insertAdjacentHTML("afterbegin", txt);
                $('#prodadd_content').html('');
                $('#prodadd_content').append(txt);


        opendialog();
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
  headers: {
      'Cache-Control': 'no-cache, no-store, must-revalidate',
      'Pragma': 'no-cache',
      'Expires': '0'
  },
  dataType: 'text',
  type: 'GET',
  error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
  success:
   function (data)
   {
    if (data != 0)
    {
        var result = JSON.parse( data );
          $('#sumorder').val(result.sum.toFixed(2));
          $('#cart_totalsum').html(result.sum);
          $('#cart_totalitems').html(result.count);
          $('.cart_nums').html(result.count);
        var urlar = (window.location.href).split('/');
        var path = urlar[urlar.length-1];
        if(path.toLowerCase() == 'cart') CartReload();
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

        var urlar = (window.location.href).split('/');
        var path = urlar[urlar.length-1];
        if(path.toLowerCase() == 'cart') CartReload();
        
          
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

function opendialog(dialogclass){
        if (dialogclass === undefined) {
                dialogclass = ".prodaddform";
            }
        $('body').addClass('noscroll');
        $(".overlay").css('visibility','visible');
        $(".overlay").css('opacity','1');
    $(dialogclass).fadeIn(); //плавное появление блока
}

function closedialog(dialogclass){
    if (dialogclass === undefined) {
                dialogclass = ".prodaddform";
    }
   $(dialogclass).fadeOut(); //плавное исчезание блока
   $(".overlay").css('visibility','hidden');
   $(".overlay").css('opacity','0');
    $('body').removeClass('noscroll');
}

$(document).ready(function(){



    $('#cart_button').on('click',function(e){
        getCartInfo('#prodadd_content');
        opendialog();
    });
    $('#cart_button').on('mouseenter',function(e){
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
// call back form
 $("#cbform").submit(function () {
 // Получение ID формы
 var formID = $(this).attr('id');
 // Добавление решётки к имени ID
 var formNm = $('#' + formID);
 $.ajax({
    type: "POST",
    url: '/ajax/callback',
    data: formNm.serialize(),
    success: function (data) {
    // Вывод текста результата отправки
        closedialog('.callbackform');
        alert(data);
       
    },
    error: function (jqXHR, text, error) {
    // Вывод текста ошибки отправки
         alert(error);
    }
 });
 return false;
 });

    var sum = parseInt($('#cart_sum_all').text());
    var min_sum = parseInt($('#min_sum').text());
    if(sum) {
        if (sum < min_sum) {
            $('#cartmessage').append('Сумма заказа должна быть не менее '+ min_sum +' грн').css('display', 'block');
        }
        $('#getorder').click(function (e) {

            if (sum < min_sum) {
                e.preventDefault();
                $('#cartmessage').text('Сумма заказа должна быть не менее '+ min_sum +' грн').css('display', 'block');

            }
            else {
                $('.formorder').submit();
            }

        });

        setInterval(function () {
            if (!$('#cart_sum_all').length > 0) {
                $('#cartmessage').text('').css('display', 'none');
            }
            else {
                var sum = parseInt($('#cart_sum_all').text());

                if (sum > min_sum) {

                    $('#cartmessage').text('').css('display', 'none');
                }
                if (sum < min_sum) {
                    $('#cartmessage').text('Сумма заказа должна быть не менее ' + min_sum + ' грн').css('display', 'block');
                }
            }

        }, 2000);
    }

});