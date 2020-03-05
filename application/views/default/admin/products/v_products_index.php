<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/products/add', 'Добавить')?>
</p>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30">
            <th>Код товара</th><th>Название</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($products as $prod):?>
    
<tr>
<td  width="100" style = "padding-left:20px;"><?=$prod->code;?></td>
<td ><?=HTML::anchor('admin/products/edit/'. $prod->prod_id, $prod->title)?></td>
<td width="50" align="center">
<?=HTML::anchor('admin/products/edit/'. $prod->prod_id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/products/delete/'. $prod->prod_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/products/add', 'Добавить')?>
</p>
<p> <?=$pagination; ?> </p>
