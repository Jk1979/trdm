<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/news/edit/'. $news['id'])?>
<table width="100%" cellspacing="3">
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
        <td><input type="text" name="title" value="<?=$news['title']?>" size="100" onkeyup="$('#path').val(genpath($(this).val()));"></td>
    </tr>
	<tr>
        <td ><?=Form::label('path', 'Алиас')?>:</td>
        <td><?=Form::input('path', $news['path'], array('size' => 100,'id'=>'path'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('date', 'Дата')?>:</td>
        <td><?=Form::input('date', $news['date'], array('size' => 100, 'id'=>'datepicker'))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('content', 'Текст')?>: </td>
        <td><?=Form::textarea('content', $news['content'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('status', 'Статус')?>:</td>
        <td><?=Form::checkbox('status', 1, (bool) $news['status'])?> Активен</td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('update', 'Сохранить')?></td>
    </tr>
</table>
<input type="hidden" name="id" value="<?=$news['id']?>" />
<?=Form::close()?>