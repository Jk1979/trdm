<?php if($delivery->count() > 0):?>
<br/>
<table width="100%" border="0" class="tbl-pay"  cellspacing="0">
    <thead>
        <tr height="30">
            <th>Название</th>
            <th>Стоимость, грн</th>
            <th>Функции</th>
        </tr>
    </thead>
<?php foreach ($delivery as $d):?>
<tr>
    <td ><?=$d->title;?></td>
    <td ><?=$d->price;?></td>
    <td width="100" align="center">
    <?=HTML::anchor('admin/delivery/delete/'. $d->id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
    </td>
</tr>
<?php endforeach;?>

</table>

<?php else:?>
<p align="center">Добавьте способ доставки</p>
<?php endif?>

<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>

<?=HTML::anchor('admin/delivery/add', 'Добавить')?>
</p>
<?=$pagination?>