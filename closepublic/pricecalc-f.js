
$(document).ready(function(){

    $(".inputPieces").each(function(index ){
        var count, square;
        var id = $( this ).attr('id');

        var ss = id.split("_");
        id =  ss[1];

        var count_p = $('#el_' + id + '_pieces').html();
        var count_m = $('#el_' + id + '_meters').html();
        if(count_p && count_m)
        {

            square = (count_m/count_p).toFixed(3);

            $('#element_' + id + '_count').val(square);
            var total_price = square*Number($('#element_' + id + '_price').html());
            console.log(total_price);
            $('#element_' + id + '_price_total').html(total_price.toFixed(2));
            var cm = $('#element_' + id + '_count').val();
            var cp = $('#element_' + id + '_pieces').val();
            var ceilpacks = Math.floor(cm/count_m);
            var ceilpieces = cp - ceilpacks*count_p;
            if(ceilpieces < 0) ceilpieces = 0;
            $(this).next('.in_pack').html( '( ' + ceilpacks + ' уп ' + ceilpieces + ' шт )');
        }
    });
    $('.cbut').click(function() {
        $('.inputPieces').change();
    });
    $(document).on('change', 'input', function() {

        var count, square;
        var id = $( this ).attr('id');
        var ss = id.split("_");
        id =  ss[1];

        var count_p = $('#el_' + id + '_pieces').html();
        var count_m = $('#el_' + id + '_meters').html();
        if(count_p && count_m)
        {

            square = (count_m/count_p).toFixed(3);

            var cm = $('#element_' + id + '_count').val();
            var cp = $('#element_' + id + '_pieces').val();
            var ceilpacks = Math.floor(cm/count_m);
            var ceilpieces = cp - ceilpacks*count_p;
            if(ceilpieces < 0) ceilpieces = 0;
            if(is_numeric(ceilpacks) && is_numeric(ceilpieces)) $(this).parent().find('.in_pack').html( '( ' + ceilpacks + ' уп ' + ceilpieces + ' шт )');
        }
    });
});

function is_numeric (mixed_var) {
    return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


var timeout_id = null;

function calculate_pc(element_id) {
    var count = Math.ceil($('#element_' + element_id + '_pieces').val());
    var price = $('#element_' + element_id + '_price').html();
    var unit = $('#el_' + element_id + '_unit').html();
    var pieces = $('#el_' + element_id + '_pieces').html();
    var meters = $('#el_' + element_id + '_meters').html();
    if(!is_numeric(count) || count <= 0 ){

        $('#element_' + element_id + '_count').val(1);
        $('#element_' + element_id + '_price_total').html(price);

        return;
    }
    if(unit == 'м2' && meters >0 && pieces > 0)
    {

        var square = meters/pieces;

        var count_m  = (count*square).toFixed(3);


    }


    if (unit  &&  count && count_m)
    {
        if(unit == 'м2') price = price * count_m;
        else price = price * count;
        price = (price % 1 == 0) ? price : Number(price).toFixed(2);
        $('#element_' + element_id + '_price_total').html(price);
        $('#element_' + element_id + '_count').val(count_m);
        if(count) $('#element_' + element_id + '_pieces').val(count);


    }
    else {
        price = price * count;
        price = (price % 1 == 0) ? price : Number(price).toFixed(2);
        $('#element_' + element_id + '_price_total').html(price);
        if(unit != "м2") { count = Math.ceil(count);}
        $('#element_' + element_id + '_count').val(count);
    }

}
function calculate_p(element_id,timeout, sign) {
    /*timeout_id = setTimeout(function(){*/

    var count = $('#element_' + element_id + '_count').val().replace(',', '.');
    var price = $('#element_' + element_id + '_price').html();
    var unit = $('#el_' + element_id + '_unit').html();
    var pieces = $('#el_' + element_id + '_pieces').html();
    var meters = $('#el_' + element_id + '_meters').html();
    var  count_p;
    count = (count % 1 == 0) ? count : Number(count).toFixed(3);
    if(sign){
        if(sign == '+'){
            count++;
        }else{
            count--;
        }

    }
    if(!is_numeric(count) || count <=0 ){

        $('#element_' + element_id + '_count').val(1);
        $('#element_' + element_id + '_price_total').html(price);
        $('#element_' + element_id + '_pieces').val(1);
        $('.inputPieces').change();
        console.log($('#element_' + element_id + '_pieces').val());
        return;
    }
    if(unit == 'м2' && meters >0 && pieces > 0)
    {
        var square = meters/pieces;
        if(meters === 1)
        {
            count_p = count*pieces;
        }
        else {
            count = Math.ceil(count/square)*square;
            count  = Number(count).toFixed(3).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1');
            count_p = Math.ceil(count/square);
        }
    }
    if(unit == 'шт' || unit == 'компл')
    {
        count = Math.ceil(count);
        count_p = count;
    }
    if (unit  && meters  && pieces)
    {
        var pricetotal = price * count;
        pricetotal = (pricetotal % 1 == 0) ? pricetotal : Number(pricetotal).toFixed(2).replace(/([0-9]+(\.[0-9]+[1-9])?)(\.?0+$)/,'$1');
        if(pricetotal != price) {
            $('#element_' + element_id + '_price_total').html(pricetotal);
            $('#element_' + element_id + '_count').val(count);
            if (count_p) $('#element_' + element_id + '_pieces').val(count_p);
        }
    }
    else {
        pricetotal = price * count;
        pricetotal = (pricetotal % 1 == 0) ? pricetotal : Number(pricetotal).toFixed(2);
        if(pricetotal != price) {
            $('#element_' + element_id + '_price_total').html(pricetotal);
            if (unit != "м2") {
                count = Math.ceil(count);
            }
            $('#element_' + element_id + '_count').val(count);
        }
    }


/* }, 1000);*/
}


function price_calculate(element_id, timeout, sign){
	var count = $('#element_' + element_id + '_count').val().replace(',', '.');
	var price = $('#element_' + element_id + '_price').html();
        //count = count.replace(",", ".");
        
  
  if(sign){
    if(sign == '+'){
      count++;
    }else{
      count--;
    }
  }

  if(timeout){
    clearTimeout(timeout_id);
    timeout_id = setTimeout(function(){
      check_num(count, price, element_id);
    }, 800);
  }else{
    check_num(count, price, element_id);
  }
}

var timeout_edit_id = null;
function cart_calculate(element_id,  sign){
                if(sign){
                    price_calculate(element_id, false, sign);
                }else{
                    price_calculate(element_id, true);
                  }
               
			clearTimeout(timeout_edit_id);
			timeout_edit_id = setTimeout(function(){
				ElementEdit(element_id, $('#element_' + element_id + '_count').val());
			}, 1000);
                        

}


function cart_price_calculate(element_id, element_key){
                        price_calculate(element_id, true);
			clearTimeout(timeout_edit_id);
			timeout_edit_id = setTimeout(function(){
				ElementEdit(element_key, $('#element_' + element_id + '_count').val());
			}, 2000);
    

}

function check_num(count, price, element_id){
       if(!is_numeric(count)){
		count = 1;
                $('#element_' + element_id + '_count').val(count);
                $('#element_' + element_id + '_price_total').html(price);
                
                return;
	}
       if(count > 0 ){
                count = (count % 1 == 0) ? count : Number(count).toFixed(2);
                
                $.ajax({
                        url: '/cat/checkcount/' + element_id + "/" + count,
                        dataType: 'text',
                        type: 'GET',
                        error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
                        success:
                         function (data)
                        {
                         if (data != 0)
                         {
                             var result = JSON.parse( data );
                             
                                if(result.count) count = result.count;
                                price = price * count;
                                price = (price % 1 == 0) ? price : Number(price).toFixed(2);
                                $('#element_' + element_id + '_price_total').html(price);
                                $('#element_' + element_id + '_count').val(count);
                                if(result.count_p) $('#element_' + element_id + '_pieces').val(result.count_p);
                              
                         } 
                         else {
                             price = price * count;
                                price = (price % 1 == 0) ? price : Number(price).toFixed(2);
                                $('#element_' + element_id + '_price_total').html(price);
				if($('.unit').html().indexOf("м2") == -1) { count = Math.ceil(count);}
                                $('#element_' + element_id + '_count').val(count);
                         }
                        }
                       });
        }else{
		$('#element_' + element_id + '_count').val(1);
	}
}


function price_calculate_pieces(element_id, timeout)
{
    var count = $('#element_' + element_id + '_pieces').val();//.replace('/[^0-9]+$/', '');
    var price = $('#element_' + element_id + '_price').html();
    if(timeout){
    clearTimeout(timeout_id);
    timeout_id = setTimeout(function(){
      check_num_p(count, price, element_id);
    }, 800);
    }else{
      check_num_p(count, price, element_id);
    }
}

function check_num_p(count, price, element_id){
       if(!is_numeric(count)){
		count = 1;
                $('#element_' + element_id + '_pieces').val(count);
                $('#element_' + element_id + '_price_total').html(price);
                
                return;
	}
       if(count > 0 ){
                count = (count % 1 == 0) ? count : Number(count).toFixed(2);
                
                $.ajax({
                        url: '/cat/checkpiece/' + element_id + "/" + count,
                        dataType: 'text',
                        type: 'GET',
                        error: function (jqXHR, textStatus, errorThrown) {alert(client_cart_e01);},
                        success:
                         function (data)
                        {
                         if (data != 0)
                         {
                             
                             var result = JSON.parse( data );
                             
                                if(result.count) count = result.count;
                                price = price * count;
                                price = (price % 1 == 0) ? price : Number(price).toFixed(2);
                                $('#element_' + element_id + '_price_total').html(price);
                                $('#element_' + element_id + '_count').val(count);
                                if(result.count_p) $('#element_' + element_id + '_pieces').val(result.count_p);
                              
                         } 
                         else {
                             count = $('#element_' + element_id + '_count').val();
                             price = price * count;
                                price = (price % 1 == 0) ? price : Number(price).toFixed(2);
                                $('#element_' + element_id + '_price_total').html(price);
                                $('#element_' + element_id + '_count').val(count);
                         }
                        }
                       });
        }else{
		$('#element_' + element_id + '_count').val(1);
	}
}


