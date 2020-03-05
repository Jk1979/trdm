<div class="cart-content">
<p align="left"><?=HTML::anchor(Url::base(), '&larr; Вернуться в магазин')?></p>
<div class="row">
<div class="col-md-6 col-sm-12 col-xs-12">
    <div id="cartmessage" class="alert alert-danger"></div>
    <span id="min_sum" style="display: none;"><?=$min_sum?></span>
    <div id="cart_content">
        <?php if (count($products) > 0):?>
            <div class="cart_order" id="cart_order">
                <?=HTML::anchor('cart/clear', 'Очистить корзину',array('class'=>'cart-clear'))?>
                    <?php foreach ($products as $product):?>
                        <div class="cart_product">
                            <a class="cart-close" href="#" onclick="ElementDelete(<?=$product->prod_id?>);return false;"></a>
                            <div class="cart-imgbox">
                                <?=HTML::anchor('product/' . $product->path,
                            HTML::image('public/uploads/imgproducts_small/small_' . $product->main_image->name, 
                                    array('alt'=>$product->title)))?>
                                    <br/>
                            </div>
                            <div class="cart-desc">
                                <div class="cart-title">
                                    <?=HTML::anchor('product/' . $product->path,$product->title)?>
                                </div>
                                <div class="cart-count">
                                    Количество:&nbsp;
                                    <input type="text" value="<?=$products_s[$product->prod_id]?>" id="<?='element_' . $product->prod_id . '_count'?>" onkeyup="cart_calculate(<?=$product->prod_id?>);">
                                    <div class="prod-count-ctrl">
                                        <a class="prod-count-up" href="#" onclick="cart_calculate(<?=$product->prod_id?>, &quot;+&quot;);return false;"></a>
                                        <a class="prod-count-down" href="#" onclick="if ($(<?='&quot;#element_' . $product->prod_id . '_count&quot;'?>).val()*1-1 > 0) 
                        { cart_calculate(<?=$product->prod_id?>, &quot;-&quot;); } return false; "></a>
                                    </div>
                                    <span class="unit" style="color:#000;"><?php if(isset($units[$product->prod_id])) echo $units[$product->prod_id]; else echo "шт.";?> </span>
                                </div>
                                <div class="cart-price">
                                    <span class="cart-price-t">Цена:</span>
                                    <span class="cart-price-c"><?=round($product->price,2)?></span>
                                    <span class="cart-price-u">грн.</span>
                                    <br>
                                    <span class="cart-price-t">Сумма:</span>
                                    <span class="cart-price-c-summ" id="<?='element_' . $product->prod_id . '_price'?>" style="display: none"><?=$product->price?></span>
                                    <span class="cart-price-c-summ" id="<?='element_' . $product->prod_id . '_price_total'?>"><?=round($product->price * $products_s[$product->prod_id],2)?></span>
                                    <span class="cart-price-u">грн.</span>
                                </div>
                            </div>
                            <?php $sum += $product->price * $products_s[$product->prod_id]?>
                        </div>
                        <?php endforeach?>
                            <div class="cart-total">
                                <span class="cart-total-t">Итого:</span>
                                <span class="cart-total-c" id="cart_sum_all"><?=round($sum,2)?></span>
                                <span class="cart-total-u">грн.</span>
                            </div>
            </div>
            <div id="debug"></div>
    </div> 
</div> 
<div class="col-md-6 col-sm-12 col-xs-12">
    <form class="form formorder" action="" method="post">
       <?php if(isset($errors)): ?>
    <?php foreach($errors as $e => $error):?>
    <div class="error">
        Поле&nbsp;<?=$error?>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
        <?=Form::label('pay_id', 'Способ оплаты:')?>
        <?=Form::select('pay_id', $pay,$data['pay_id'])?>
        <?=Form::label('delivery_id', 'Способ доставки:')?>
        <?=Form::select('delivery_id', $delivery,$data['delivery_id'],
                array('id'=>'delivery', 'onchange'=>'mustAdress();'))?>
        <?=Form::label('name', 'Ф.И.О.:')?>
        <?=Form::input('name', $data['name'], array('required' => 'required'))?>
        <?=Form::label('phone', 'Телефон:')?>
        <?=Form::input('phone', $data['phone'], array( 'required' => 'required'))?>
        <?=Form::label('email', 'email')?>
        <?=Form::input('email', $data['email'], array('type'=>'email', 'required' => 'required'))?>
        <?=Form::label('adress', 'Адрес')?>
        <?=Form::input('adress', $data['adress'], array('id'=>'adress'))?>
        <?=Form::label('content', 'Описание')?>
        <?=Form::textarea('content', $data['content'])?>
        <input type="hidden" id="sumorder" name="sum" value="<?=$sum?>">
        <?=Form::submit('order', 'Заказать', array('class'=> 'button',"id"=>"getorder"))?>
    </form>
</div>   
</div> 
</div>  
<?php else:?>
    <div class="empty">Нет товаров в корзине</div>
<?php endif;?>