<?php if($pay->count() > 0):?>
<br/>
<table width="100%" border="0" class="tbl-pay"  cellspacing="0">
    <thead>
        <tr height="30">
            <th>Название</th>
            <th>Функции</th>
        </tr>
    </thead>
<?php foreach ($pay as $p):?>
<tr>
    <td ><?=$p->title;?></td>
    <td width="100" align="center">
    <?=HTML::anchor('admin/pay/delete/'. $p->id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
    </td>
</tr>
<?php endforeach;?>

</table>

<?php else:?>
<p align="center">Добавьте способ оплаты</p>
<?php endif?>

<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>

<?=HTML::anchor('admin/pay/add', 'Добавить')?>
</p>
<?=$pagination?>