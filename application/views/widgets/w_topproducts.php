<h2>Лучшие товары</h2>
<div style="width:100%">
<?php if(isset($products) && ($products->count() > 0)):?>
<div class="topitems">
    <?php foreach($products as $i => $product):?>
        <?php 
               if($product->images->count_all()>0) $im_name = 'public/uploads/imgproducts_small/small_' . $product->main_image->name;
               else $im_name = 'public/img/book.jpg';
        ?>
		<div class = "topitem">
            <div class="imgtop">
             <?=html::anchor("product/$product->path",
                     html::image($im_name, array('width' => '150')))?>
            </div>         
              <?=html::anchor("product/$product->path", "<h4>$product->title</h4><br/>")?>
             <div class="cost"><?=$product->price?> грн.</div>
             <a onclick="ElementAdd(<?=$product->prod_id?>, 1);return false;"
               href=""> <?=html::image('public/img/buy.gif')?></a>
	   </div>
       <?php if ((($i % 3) == 0) && $i!=0):?><div class="cat-separator"></div>  <?php endif?>
    <?php endforeach?>
</div>       
<?php else:?>
<div class="empty">Нет товаров в этой категории</div>
<?php endif?>
 <div class="clpag"></div>
</div>



