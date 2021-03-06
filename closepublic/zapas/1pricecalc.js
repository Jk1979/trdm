
function is_numeric (mixed_var) {
    return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


var timeout_id = null;

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
function cart_calculate(element_id, element_count, sign){
                if(sign){
                    price_calculate(element_id, true, sign);
                }else{
                    price_calculate(element_id, true);
                  }
               
			clearTimeout(timeout_edit_id);
			timeout_edit_id = setTimeout(function(){
				ElementEdit(element_id, $('#element_' + element_id + '_count').val());
			}, 2000);
                        
                /*        
                if(!is_numeric(element_count) || element_count < 1 ){
		$('#element_' + element_id + '_count').val(1);
                }
                if($('#element_' + element_id + '_count').val() > 0){
			clearTimeout(timeout_id);
			clearTimeout(timeout_edit_id);
			ElementEdit(element_id, $('#element_' + element_id + '_count').val());
		}else{
			price_calculate(element_id, true);
			clearTimeout(timeout_edit_id);
			timeout_edit_id = setTimeout(function(){
				ElementEdit(element_id, $('#element_' + element_id + '_count').val());
			}, 1000);
		}   */
}


function cart_price_calculate(element_id, element_key){
                        price_calculate(element_id, true);
			clearTimeout(timeout_edit_id);
			timeout_edit_id = setTimeout(function(){
				ElementEdit(element_key, $('#element_' + element_id + '_count').val());
			}, 2000);
    
/*		if($('#element_' + element_id + '_count').val() > 0){
			clearTimeout(timeout_id);
			clearTimeout(timeout_edit_id);
			ElementEdit(element_key, $('#element_' + element_id + '_count').val());
		}else{
			price_calculate(element_id, true);
			clearTimeout(timeout_edit_id);
			timeout_edit_id = setTimeout(function(){
				ElementEdit(element_key, $('#element_' + element_id + '_count').val());
			}, 2000);
		}   */
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

$(document).ready(function(){
    
    $(".inputPieces").each(function(index ){
        var count;
        var id = $( this ).attr('id');
        
        var ss = id.split("_");
        id =  ss[1];
        
        var count_p = $('#el_' + id + '_pieces').html();
        var count_m = $('#el_' + id + '_meters').html();
        if(count_p && count_m)
        {
            
            count = (count_m/count_p); 
            count = count.toFixed(3);
            $('#element_' + id + '_count').val(count);
        }
        
     });
});
