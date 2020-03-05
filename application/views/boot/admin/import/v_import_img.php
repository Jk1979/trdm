<br/>
<p>Введите название производителя, как на сайте для проверки картинок</p>
<form action="" method="post">
    <input type="text" name = "brand" value = ""/>
    <br/>
    <input type="submit" name = "test" value="Проверить">
    <br/>
    <input type="submit" name = "set" value="Найти и установить картинки">
    <br>
    <input type="submit" name = "setbase" value="Найти и установить картинки по базе данных domplitki">
</form>
<br><br><br>
<?php if(isset($products)):?>
<?php $i=0;?>
<?php foreach($products as $prod):?>
    <?php echo "Код товара " . $prod->code . "  нет картинки<br/>"; $i++;?>
<?php endforeach; ?>
<?php echo "Всего  $i товаров без картинок у данного производителя"; ?>
<?php endif;?>
<?php if(isset($listimg)):?>
<?php $i=0;?>
<?php foreach($listimg as $img):?>
    <?php echo "Картинка " . $img . "<br/>"; $i++;?>
<?php endforeach; ?>
<?php echo "Всего  $i картинок найдено"; ?>
<?php endif;?>
<?php if(isset($count)) echo "Всего  $count картинок установлено";?>
<?php if(isset($countb)) echo "<br/>Всего  $countb картинок найдено в базе domplitki";?>
<?php if(isset($lstimg)):?>
<?php foreach($lstimg as $img):?>
    <?php echo $img . "<br/>";?>
<?php endforeach; ?>
<?php endif;?>