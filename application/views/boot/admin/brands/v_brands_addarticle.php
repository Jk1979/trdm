<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div style="color: #cc0000; background-color: #ffcccc; width:500px; margin:7px auto; font: 18px sans-serif;">
    &nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<br>
<?=Form::open('admin/brands/addarticle/'. $brand_id)?>

 <select name="cat_id" style="margin-bottom: 15px">
    <option value="0">
        < Корневая категория >
    </option>
    <?php foreach ($categories as $cat):?>
        <option value="<?=$cat->cat_id?>">
            <?=str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title ?>
        </option>
    <?php endforeach?>
</select>

<?=Form::textarea('description', $data['description'], array('cols' => 100, 'rows' => 20))?>
<br>
<?=Form::submit('add', 'Добавить')?>
<?=Form::close()?>    



