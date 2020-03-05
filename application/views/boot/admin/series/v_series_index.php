<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/series/add/' . $brand_id, 'Добавить')?>
</p>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30" align="left">
            <th>Название</th><th>Алиас</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($series as $serie):?>
    
<tr>
<td ><?=HTML::anchor('admin/series/edit/'. $serie->serie_id, $serie->title)?></td>
<td ><?= $serie->path;?></td>
<td width="50" align="center">
<?=HTML::anchor('admin/series/edit/'. $serie->serie_id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/series/delete/'. $serie->serie_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/series/add/' . $brand_id, 'Добавить')?>
</p>

<?=$pagination?>

