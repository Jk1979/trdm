<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/users/add', 'Добавить')?>
</p>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30" align="left">
            <th>Пользователь</th><th>Роли</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php foreach ($users as $user):?>
    
<tr>
<td ><?=HTML::anchor('admin/users/edit/'. $user->id, $user->username)?></td>
    <td >
        <?php foreach ($roles[$user->id] as $r):?>
        <?=$r->name ?>
        <?php endforeach;?>
    </td>
<td width="50" align="center">
<?=HTML::anchor('admin/users/edit/'. $user->id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/users/delete/'. $user->id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p align="right">
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/users/add', 'Добавить')?>
</p>

<?=$paginator?>