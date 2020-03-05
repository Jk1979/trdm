<div class="cat_index">
    <p>
        <?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
        <?=HTML::anchor('admin/filters/add/' . $cat_id, 'Добавить')?>
    </p>
<br/>
<table width="100%" border="0" class="tbl cat_tbl"  cellspacing="0">
    <thead>
        <tr height="30">
            <th style="text-align: center">№</th><th>Название</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($filters as $fr):?>
<tr class="tr-prods">
<td width="20"><?=$fr->filter_orderid?></td>
<td><?=HTML::anchor('admin/filters/edit/'. $fr->filter_id, $fr->filter_title)?></td>
<td><?=HTML::anchor('admin/filteroptions/index/'. $fr->filter_id, 'Опции')?></td>
<td class = "hr-td-icons-arrow">
<div>
    <a href="<?=URL::base()?>admin/filters/moveup/<?=$fr->filter_id?>" title="Поднять вверх" onmouseover="document.getElementById('arrow_up_<?=$fr->filter_id?>').src ='<?=URL::base()?>public/img/arrow_up.png';" 
        onmouseout="document.getElementById('arrow_up_<?=$fr->filter_id?>').src = '<?=URL::base()?>public/img/arrow_up_off.png';">
       <?=HTML::image('public/img/arrow_up_off.png', array('id'=>'arrow_up_'. $fr->filter_id, 'class'=>'hr-png'))?> </a>
</div>
<div>
    <a href="<?=URL::base()?>admin/filters/movedown/<?=$fr->filter_id?>" title="Опустить вниз" onmouseover="document.getElementById('arrow_down_<?=$fr->filter_id?>').src ='<?=URL::base()?>public/img/arrow_down.png';" 
        onmouseout="document.getElementById('arrow_down_<?=$fr->filter_id?>').src = '<?=URL::base()?>public/img/arrow_down_off.png';">
       <?=HTML::image('public/img/arrow_down_off.png', array('id'=>'arrow_down_'. $fr->filter_id, 'class'=>'hr-png'))?> </a>
</div>
</td>
<td width="50" align="center" >
<?=HTML::anchor('admin/filters/edit/'. $fr->filter_id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/filters/delete/'. $fr->filter_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
</tr>
<?php endforeach?>
</table>
<br/>
<p>
        <?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
        <?=HTML::anchor('admin/filters/add/' . $cat_id, 'Добавить')?>
</p>
</div>


