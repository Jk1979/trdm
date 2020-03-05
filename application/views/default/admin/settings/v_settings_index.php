<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/settings')?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('email', 'E-mail')?>:</td>
        <td><?=Form::input('email', $settings->email, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('phone_up', 'Телефон(сверху)')?>:</td>
        <td><?=Form::input('phone_up', $settings->phone_up, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('phone_down', 'Телефон(снизу)')?>:</td>
        <td><?=Form::input('phone_down', $settings->phone_down, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('mode', 'Режим работы')?>:</td>
        <td><?=Form::input('mode', $settings->mode, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('copyright', 'Строка копирайта (снизу):')?>:</td>
        <td><?=Form::input('copyright', $settings->copyright, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('slider_main', 'Слайдер, Главная:')?>:</td>
        <td><?=Form::checkbox('slidermain',1, (bool) $settings->slider_main)?></td>
    </tr>
    <tr>
        <td ><?=Form::label('slader_inside', 'Слайдер, Внутр.стр.:')?>:</td>
        <td><?=Form::checkbox('sladerothers',1,(bool) $settings->slader_inside)?></td>
    </tr>
    <tr>
        <td ><?=Form::label('title_one', 'Главная, Блок #1:<br/>Заголовок:')?>:</td>
        <td><?=Form::input('title_one', $settings->title_one, array('size' => 100))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('block_one', 'Главная, Блок #1:')?>: </td>
        <td><?=Form::textarea('block_one', $settings->block_one, array('cols' => 100, 'rows' => 20))?></td>
    </tr>
   <tr>
        <td ><?=Form::label('title_two', 'Главная, Блок #2:<br/>Заголовок:')?>:</td>
        <td><?=Form::input('title_two', $settings->title_two, array('size' => 100))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('block_two', 'Главная, Блок #2:')?>: </td>
        <td><?=Form::textarea('block_two', $settings->block_two, array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_title', 'Заголовок страницы, title')?>:</td>
        <td><?=Form::input('meta_title', $settings->meta_title, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_keywords', 'Заголовок страницы, keywords')?>:</td>
        <td><?=Form::input('meta_keywords', $settings->meta_keywords, array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_description', 'Заголовок страницы, description')?>:</td>
        <td><?=Form::input('meta_description', $settings->meta_description, array('size' => 100))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('save', 'Сохранить')?></td>
    </tr>
</table>
<?=Form::close()?>