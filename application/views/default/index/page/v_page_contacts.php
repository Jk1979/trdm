<p><?=$contacts->content?></p>
<div><?=$contacts->map?></div>
<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<b>Поле&nbsp;<?=$error?></b>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('contacts.html')?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('name', 'ФИО')?>:</td>
        <td><?=Form::input('name', '', array('size' => 60))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('email', 'E-mail')?>:</td>
        <td><?=Form::input('email', '', array('size' => 60))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('text', 'Текст сообщения')?>: </td>
        <td><?=Form::textarea('text', '', array('cols' => 60, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('send', 'Отправить')?></td>
    </tr>
</table>
<?=Form::close()?>
<br/>