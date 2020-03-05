<div class="sort">
    Сортировать:
    <div class="sort-list">
        <?=$sort_type?>				
<ul class="sort-drop" style="display: none;">
<li onclick="SortProducts(1);return false;"><a href="#" onclick="return false;">по названию</a></li>
<li onclick="SortProducts(2);return false;"><a href="#" onclick="return false;">от дешевых к дорогим</a></li>
<li onclick="SortProducts(3);return false;"><a href="#" onclick="return false;">от дорогих к дешевым</a></li>
<li onclick="SortProducts(4);return false;"><a href="#" onclick="return false;">последние добавленные</a></li>
</ul>
    </div>
</div>
<?php if(isset($series) && ($series->count() > 0)):?>
<div class="srs-list">
    <div class="srs-title">Список коллекций:</div>
    <div class="row brandsrow">
    <?php foreach($series as $i => $serie):?>
        <div class="col-md-3 srs-item"><?=html::anchor("cat/$cat-$brand/series-$serie->path",$serie->title);?></div>
    <?php if (((($i+1) % 4) == 0) && $i>0):?> </div><div class="row brandsrow"><?php endif;?>
    <?php endforeach?>
    </div> 
</div>   
<?php endif?>

<?php if(isset($products) && count($products)):?>
    <div class="row prodrow">
    <?php foreach($products as $i => $product):?>
        <?php 
               if($product->images->count_all()>0) $im_name = 'public/uploads/imgproducts_small/small_' . $product->main_image->name;
               else $im_name = 'public/img/noimage.jpg';
        ?>
		<div class = "col-lg-4 col-md-6 col-sm-6 col-xs-12 product">
           <div class="gtile-i-wrap">
                <div class="gtile-i">
                <div class="gtile-i-box">
                <div class="prodbox">
                <div class="imgitem">
                <?=html::anchor("product/$product->path",html::image($im_name,array('alt'=>$product->title)))?>
                </div> 
                 <div></div>
                <?=html::anchor("product/$product->path", "$product->title",array('class'=>'toptitle'))?>
                <p><span>Код товара: </span>TM-<?=$product->code?></p>
                <div class="prod-cat-count">
                <input type="text" value="1" id="<?='element_' . $product->prod_id. '_count'?>" onkeyup="price_calculate(<?=$product->prod_id?>,true);"/>
                <?php if(isset($dataAgrom[$product->code]['unit'])) echo $dataAgrom[$product->code]['unit']; else echo "шт"?> 
                <?php if(isset($dataAgrom[$product->code]['meters']) && isset($dataAgrom[$product->code]['pieces'])):?>
                <?php if($dataAgrom[$product->code]['meters'] > 0 && $dataAgrom[$product->code]['pieces'] > 0):?>
                <input type="text" value="1" id="<?='element_' . $product->prod_id. '_pieces'?>" onkeyup="price_calculate_pieces(<?=$product->prod_id?>,true);"/>  шт
                <?php endif;?>
                 <?php endif;?>
                </div>
                <p><span id="<?='element_' . $product->prod_id. '_price'?>" class="catprice"><?=$product->price?></span><span class="cat-price-u"> грн.</span></p>
                <a class="button" onclick="ElementAdd(<?=$product->prod_id?>,$(<?="&quot;#element_" .$product->prod_id . "_count&quot;"?>).val()*1);return false;" href="">Купить</a>
                <p class="product_s_desc">
                <?php if($product->present==0):?> <p style="color:red;">Нет в наличии</p><?php endif;?> 
                <?=$shortDesc[$product->prod_id]?>
                </p>
                </div>
                </div>
                </div>
                </div>
	   </div>
    <?php endforeach?>
    </div>
<?php else:?>
<div class="empty">Товары не найдены</div>
<?php endif?>
 <div class="clearfix"></div>
<?=$pagination?>
<?php if($needDesc == 'yes'):?>
<div class="row">
     <div class="col-md-12  col-sm-12">
         <?=$brand_desc?>
     </div>
 </div>
<?php endif?>


