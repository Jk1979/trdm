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
<?php if(isset($products) && ($products->count() > 0)):?>

    <?php foreach($products as $i => $product):?>
        <?php 
               if($product->images->count_all()>0) $im_name = 'public/uploads/imgproducts_small/small_' . $product->main_image->name;
               else $im_name = 'public/img/book.jpg';
        ?>
		<div class = "prod-box">
             <?=html::anchor("product/$product->path",
                     html::image($im_name, array('width' => '150')))?><br/>
                      <?=html::anchor("product/$product->path", "<h4>$product->title</h4><br/>")?>
             <div class="cost"><?=$product->price?> грн.</div>
             <a onclick="ElementAdd(<?=$product->prod_id?>, 1);return false;"
               href=""> <?=html::image('public/img/buy.gif')?></a>
	   </div>
	    <?php if (((($i+1) % 3) === 0) && $i>0):?><div class="cat-separator"></div>  <?php endif?>
    <?php endforeach?>
<?php else:?>
<div class="empty">Нет товаров в этой категории</div>
<?php endif?>
<?=$pagination?>


