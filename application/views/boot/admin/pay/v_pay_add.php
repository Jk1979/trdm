<br/>
<?php if($errors):?>
<?php foreach ($errors as $error):?>
<div class="error"><?=$error?></div>
<?php endforeach?>
<?php endif?>

<?=Form::open('admin/pay/add')?>
<table width="100%" cellspacing="5">
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
        <td><?=Form::input('title', $data['title'], array('size' => 100))?></td>
    </tr>
     <tr>
        <td valign="top"><?=Form::label('status', 'Статус')?>:</td>
        <td><?=Form::checkbox('status', 1, true)?> Активен</td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('submit', 'Добавить')?></td>
    </tr>
</table>
<?=Form::close()?>
