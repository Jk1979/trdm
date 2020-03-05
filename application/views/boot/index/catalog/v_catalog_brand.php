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
        <div class="col-md-3 col-sm-4 col-xs-6 srs-item"><?=html::anchor("cat/$cat-$brand/series-$serie->path",$serie->title);?></div>
    <?php /*if (((($i+1) % 4) == 0) && $i>0):*/?><!-- </div><div class="row brandsrow">--><?php /*endif;*/?>
    <?php endforeach?>
    </div> 
</div>   
<?php endif?>

<?php include('productslist.php'); ?>

<?php if($needDesc == 'yes'):?>
<div class="row">
     <div class="col-md-12  col-sm-12">
         <?=$brand_desc?>
     </div>
 </div>
<?php endif?>


