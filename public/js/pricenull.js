$( document ).ready(function() {
    var prodprice;
    
    prodprice = parseFloat($('.prod-price-c').html());
        if(prodprice<1)
        {
            $(".prod-price-c").parent().html('Цену уточняйте');
            
        }
    $(".catprice").each(function(){
            var pr;
            pr = parseFloat($(this).html());
            if(pr<1) {
                $(this).parent().html('Цену уточняйте');
            }
        });  
});