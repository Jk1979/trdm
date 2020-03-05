<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/attributes/attributesvaluesadd/'. $attribute->attr_id, 'Добавить')?>
</p>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30" align="left">
            <th>№</th><th>Название</th><th>Значение</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($values as $v):?>
 <tr>
<td ><?=$v->sort;?></td>
<td ><?=HTML::anchor('admin/attributes/attributesvaluesedit/'. $v->attrval_id, $v->title)?></td>
<td ><?=$v->path;?></td>
<td width="50" align="center"><?=HTML::anchor('admin/attributes/attributesvaluesedit/'. $v->attrval_id, HTML::image('public/img/edit.png'))?></td>
<td width="50" align="center"><?=HTML::anchor('admin/attributes/attributesvaluesdelete/'. $v->attrval_id, HTML::image('public/img/delete.png'),
        array("onclick"=>"return clickHandler();"))?></td>
</tr>
<?php endforeach?>

</table>

<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/attributes/attributesvaluesadd/'. $attribute->attr_id, 'Добавить')?>
</p>
<?=$pagination?>