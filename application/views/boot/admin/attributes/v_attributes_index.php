<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/attributes/add', 'Добавить')?>
</p>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30" align="left">
            <th>№</th><th>Название</th><th>Алиас</th><th>Значения</th><th>Показывать в категориях</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($attributes as $attr):?>
    
<tr>
<td ><?=$attr->sort;?></td>
<td ><?=HTML::anchor('admin/attributes/edit/'. $attr->attr_id, $attr->name)?></td>
<td ><?=$attr->path;?></td>
<td><?=HTML::anchor('admin/attributes/attributesvalues/'. $attr->attr_id,'Значения')?></td>
<td><?=Form::input('prodattrcats', $attr->catids, array('class'=>'prodattrcats','size' => 15, 'data-id'=>$attr->attr_id))?></td>
<td width="50" align="center">
<?=HTML::anchor('admin/attributes/edit/'. $attr->attr_id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/attributes/delete/'. $attr->attr_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/attributes/add', 'Добавить')?>
</p>
<?=$pagination?>