<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/brands/addarticle/'. $brand->brand_id, 'Добавить статью')?>
</p>
<?php if(count($articles)):?>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30" align="left">
            <th>Название категории</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($articles as $art):?>
   
<tr>
<td ><?=HTML::anchor('admin/brands/editarticle/'. $art->id, $cats[$art->id])?></td>
<td width="50" align="center">
<?=HTML::anchor('admin/brands/editarticle/'. $art->id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/brands/deletearticle/'. $art->id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/brands/addarticle/'. $brand->brand_id, 'Добавить статью')?>
</p>
<?=$pagination?>
<?php endif;?>