<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/contacts')?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
        <td><?=Form::input('title', $contacts->title, array('size' => 100))?></td>
    </tr>
   
    <tr>
        <td valign="top"><?=Form::label('content', 'Описание')?>: </td>
        <td><?=Form::textarea('content', $contacts->content, array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('map', 'Блок карты')?>: </td>
        <td><?=Form::textarea('map', $contacts->map, array('cols' => 100, 'rows' => 7))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_title', 'Заголовок страницы, title')?>:</td>
        <td><?=Form::input('meta_title', $contacts->meta_title, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_keywords', 'Заголовок страницы, keywords')?>:</td>
        <td><?=Form::input('meta_keywords', $contacts->meta_keywords, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_description', 'Заголовок страницы, description')?>:</td>
        <td><?=Form::input('meta_description', $contacts->meta_description, array('size' => 100))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('save', 'Сохранить')?></td>
    </tr>
</table>
<?=Form::close()?>