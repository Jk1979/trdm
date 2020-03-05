<h2>Добрый день!</h2>

<p>К сожалению, <?php if($count>1) echo 'данных товаров'; else echo 'данного товара';?> нет в наличии.</p>

<hr />

Заказ сделан на сайте Trademag.com.ua<br/>
Форма доставки: <b><?=$delivery?></b><br />
Способ оплаты: <b><?=$pay?></b><br />
ФИО: <b><?=$name?></b><br />
Телефон: <b><?=$phone?></b><br />
E-mail: <?=$email?><br />
<?php if($message !==""):?>Другое: <?=$message?><br /><?php endif;?>

<?php if($sum):?>Общая сумма заказа: <u><?=$sum?></u> грн <?php endif;?><br />
<h2>Товары</h2>
<ul>
   <?php if(count($products)>0):?>
    <?php foreach($products as $prod):?>
        <li><b><?=$prod['title'] . " "?></b>Код товара: <?=$prod['code'] . " "?> 
            Количество - <?=$prod['count']?> <?php if(isset($units[$prod['prod_id']])) echo $units[$prod['prod_id']]; else echo "шт";?> &nbsp;&nbsp;,
            цена за единицу - <?=$prod['price']?> грн.,Сумма <?=round($prod['count'] * $prod['price'],2) ?> грн.
        </li>
    <?php endforeach;?>
    <?php endif;?>
</ul>