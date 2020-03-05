<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/news/add')?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
		<td><input type="text" name="title" size="100" value="<?=$data['title']?>" onkeyup="$('#path').val(genpath($(this).val()));"></td>
    </tr>
	<tr>
        <td ><?=Form::label('path', 'Алиас')?>:</td>
        <td><?=Form::input('path', $data['path'], array('size' => 100,'id'=>'path'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('date', 'Дата')?>:</td>
        <td><?=Form::input('date', date('d.m.Y'), array('size' => 100, 'id'=>'datepicker'))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('content', 'Текст')?>: </td>
        <td><?=Form::textarea('content', $data['content'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('status', 'Статус')?>:</td>
        <td><?=Form::checkbox('status', 1, true)?> Активен</td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('add', 'Добавить')?></td>
    </tr>
</table>
<?=Form::close()?>