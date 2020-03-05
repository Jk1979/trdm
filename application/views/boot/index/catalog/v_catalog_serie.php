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
<?php include('productslist.php'); ?>




