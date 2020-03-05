<div class="cat_index">
    <p>
        <?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
        <?=HTML::anchor('admin/categories/add', 'Добавить')?>
    </p>
<br/>
<table width="100%" border="0" class="tbl cat_tbl"  cellspacing="0">
    <thead>
        <tr height="30">
            <th>Название</th><th>Адрес</th><th>Фильтры</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($categories as $cat):?>
    
<tr>
<td><?=HTML::anchor('admin/categories/edit/'. $cat->cat_id, str_repeat('&nbsp;', 2 * $cat->lvl) . $cat->title)?></td>
<td ><?=$cat->path?></td>
<td>
    <?php if($cat->title!='root'):?>
        <?=HTML::anchor('admin/filters/index/'. $cat->cat_id, 'Категории фильтров')?>
    <?php endif;?>
</td>
<td class = "hr-td-icons-arrow">
<?php if($cat->title != 'root' ):?>    
<div>
    <a href="<?=URL::base()?>admin/categories/catmoveup/<?=$cat->cat_id?>" title="Поднять вверх" onmouseover="document.getElementById('arrow_up_<?=$cat->cat_id?>').src ='<?=URL::base()?>public/img/arrow_up.png';" 
        onmouseout="document.getElementById('arrow_up_<?=$cat->cat_id?>').src = '<?=URL::base()?>public/img/arrow_up_off.png';">
       <?=HTML::image('public/img/arrow_up_off.png', array('id'=>'arrow_up_'. $cat->cat_id, 'class'=>'hr-png'))?> </a>
</div>
<div>
    <a href="<?=URL::base()?>admin/categories/catmovedown/<?=$cat->cat_id?>" title="Опустить вниз" onmouseover="document.getElementById('arrow_down_<?=$cat->cat_id?>').src ='<?=URL::base()?>public/img/arrow_down.png';" 
        onmouseout="document.getElementById('arrow_down_<?=$cat->cat_id?>').src = '<?=URL::base()?>public/img/arrow_down_off.png';">
       <?=HTML::image('public/img/arrow_down_off.png', array('id'=>'arrow_down_'. $cat->cat_id, 'class'=>'hr-png'))?> </a>
</div>
<?php endif;?>
</td>
<td width="50" align="center" >
<?=HTML::anchor('admin/categories/edit/'. $cat->cat_id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/categories/delete/'. $cat->cat_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>
<br/>
<p>
        <?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
        <?=HTML::anchor('admin/categories/add', 'Добавить')?>
</p>
</div>


