<div class="parent__cartblock">
<button class="cart_button" id="cart_button"><span><i class="fa fa-shopping-cart"></i>Корзина</span>
<span class="cart_spandiv"><span class="cart_nums"><?=$count;?></span><span class="cart_prods">товаров</span></span>
</button>
<div class="cart_sub">
       <div class="cart_sub_content">
        <p> Товаров - <span id="cart_totalitems"><?= $count ?> </span>шт. </p>
        <p> Сумма <span class="cart_sum" id="cart_totalsum"><?=$sum?></span> грн</p>
        <a href="/cart" class="aorder">Оформить заказ</a> 
         <a href="#" class="cart_clear"><i class="fa fa-trash" aria-hidden="true"></i>Очистить корзину</a>  
         </div>
        <p class="cart_empty">В корзине пусто !</p>
</div> 
</div> 
   





