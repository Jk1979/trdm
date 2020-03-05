
function is_numeric (mixed_var) {
    return (typeof(mixed_var) === 'number' || typeof(mixed_var) === 'string') && mixed_var !== '' && !isNaN(mixed_var);
}

function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}


var timeout_id = null;

function price_calculate(element_id, timeout, sign){
	count = $('#element_' + element_id + '_count').val()*1;
	price = $('#element_' + element_id + '_price').html();
        
        if(!is_numeric(count)){
		count = 1;
	}
  
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
    }, 2000);
  }else{
    check_num(count, price, element_id);
  }
}

var timeout_edit_id = null;
function cart_calculate(element_id, element_count){
                
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
		}
}


function cart_price_calculate(element_id, element_key){
		if($('#element_' + element_id + '_count').val() > 0){
			clearTimeout(timeout_id);
			clearTimeout(timeout_edit_id);
			ElementEdit(element_key, $('#element_' + element_id + '_count').val());
		}else{
			price_calculate(element_id, true);
			clearTimeout(timeout_edit_id);
			timeout_edit_id = setTimeout(function(){
				ElementEdit(element_key, $('#element_' + element_id + '_count').val());
			}, 2000);
		}
}

function check_num(count, price, element_id){
	if(count > 0 && count % 1 == 0){
		price = price * count;
		price = (price % 1 == 0) ? price : Number(price).toFixed(2);
		$('#element_' + element_id + '_price_total').html(price);
		$('#element_' + element_id + '_count').val(count);
	}else{
		$('#element_' + element_id + '_count').val(1);
	}
}