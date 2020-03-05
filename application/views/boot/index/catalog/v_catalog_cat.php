<?php if($category->has_children()):?>
<div class="row category_outerblock">
<p>Подкатегории:</p>
<?php foreach($category->children as $c): ?>
<div class="col-md-6 category_block">
    <a  href="<?= $c->path?>">
        <img src="<?php echo URL::base(); ?>public/img/nav/<?=$c->image?>" width="50px;"/>
        <?=$c->title?>
    </a>
</div>
<?php endforeach; ?>
</div>
<?php endif?>
<?php if($showproducts):?>
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
<?php if(isset($brands) && ($brands->count() > 0)):?>
<div class="brs-list hidden">
        <div class="srs-title">Список производителей:</div>
        <div class="row brandsrow">
        <?php foreach($brands as $i => $brand):?>
            <div class="col-md-3 srs-item"><?=html::anchor("cat/$cat-$brand->path",$brand->title); ?></div>
        <?php if (((($i+1) % 4) == 0) && $i>0):?> </div><div class="row brandsrow"><?php endif;?>
        <?php endforeach?>
        </div>
</div>
<?php endif?>

<?php include('productslist.php'); ?>
<?php endif;?>
<?php if($needDesc == 'yes'):?>
 <div class="row">
     <div class="col-md-12  col-sm-12">
         <?=$category->description?>
     </div>
 </div>
<?php endif?>

<?php if($articles):?>
    <?=$articles?>
<?php endif?>



