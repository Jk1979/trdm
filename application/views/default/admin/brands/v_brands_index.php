<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/brands/add', 'Добавить')?>
</p>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30" align="left">
            <th>Название</th><th>Алиас</th><th>Серии</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($brands as $brand):?>
    
<tr>
<td ><?=HTML::anchor('admin/brands/edit/'. $brand->brand_id, $brand->title)?></td>
<td ><?= $brand->path;?></td>
<td><?=HTML::anchor('admin/series/'. $brand->brand_id,'серии')?></td>
<td width="50" align="center">
<?=HTML::anchor('admin/brands/edit/'. $brand->brand_id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/brands/delete/'. $brand->brand_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/brands/add', 'Добавить')?>
</p>
<?=$pagination?>