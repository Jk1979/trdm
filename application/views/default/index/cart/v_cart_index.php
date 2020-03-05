<div class="cart-content">
    <br/>
    <p align="left"><?=Html::anchor('catalog', '&larr; Вернуться в магазин')?></p>
    <br/>
    <div  id="cart_content">
        <?php if (count($products) > 0):?>
        <br/>
        <div class="cart_order" id="cart_order">
             <?=Html::anchor('cart/clear', 'Очистить корзину',array('class'=>'cart-clear'))?>
             <?php foreach ($products as $product):?>
            <div class="cart_product">
                <a class="cart-close" href="#" onclick="ElementDelete(<?=$product->prod_id?>);return false;"></a>
                <div class="cart-imgbox">   
                <?=Html::anchor('product/' . $product->path,
                        html::image('public/uploads/imgproducts/' . $product->main_image->name, 
                                array('width' => '60','alt'=>$product->title)))?> <br/>
                </div>
                <div class="cart-desc">
                    <div class="cart-title">
                    <?=Html::anchor('product/' . $product->path,$product->title)?>
                    </div>
                <div class="cart-count">
                Количество:&nbsp;
                <input type="text" value="<?=$products_s[$product->prod_id]?>" id="<?='element_' . $product->prod_id . '_count'?>" 
                            onkeyup="cart_calculate(<?=$product->prod_id?>,this.value);">
                <div class="prod-count-ctrl">
                <a class="prod-count-up" href="#" onclick="ElementEdit(<?=$product->prod_id?>, $(<?='&quot;#element_' . $product->prod_id . '_count&quot;'?>).val()*1+1);return false;"></a>
                <a class="prod-count-down" href="#" onclick ="if ($(<?='&quot;#element_' . $product->prod_id . '_count&quot;'?>).val()*1-1 > 0) 
                    { ElementEdit(<?=$product->prod_id?>, $(<?='&quot;#element_' . $product->prod_id . '_count&quot;'?>).val()*1-1); } return false; "></a>
              </div>
              шт.
            </div>
             <div class="cart-price">
                <span class="cart-price-t">Цена:</span>
                <span class="cart-price-c"><?=$product->price?></span>
                <span class="cart-price-u">грн.</span>
                <br>
                <span class="cart-price-t">Сумма:</span>
                <span class="cart-price-c-summ" id="<?='element_' . $product->prod_id . '_price'?>" style="display: none"><?=$product->price?></span>
                <span class="cart-price-c-summ" id="<?='element_' . $product->prod_id . '_price_total'?>"><?=$product->price * $products_s[$product->prod_id]?></span>
                <span class="cart-price-u">грн.</span>
              </div>
            </div>   
            <?php $sum += $product->price * $products_s[$product->prod_id]?>
            </div>
        <?php endforeach?>
        <div class="cart-total">
                            <span class="cart-total-t">Итого:</span>
                            <span class="cart-total-c" id="cart_sum_all"><?=$sum?></span>
                            <span class="cart-total-u">грн.</span>
        </div>
        </div>
        <div id = "debug"></div>
    </div>
        <p align="right"><?=Html::anchor('cart/order', 'Оформить заказ &rarr;')?></p>    
</div>    
<?php else:?>
    <div class="empty">Нет товаров в корзине</div>
<?php endif;?>