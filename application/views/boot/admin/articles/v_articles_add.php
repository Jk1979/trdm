<br/>
<script>
    $( function() {
        $( "#datepicker" ).datepicker();
    } );
</script>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/articles/add')?>
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
        <td ><?=Form::label('mainpage', 'На главной')?>:</td>
        <td><?=Form::checkbox('mainpage', 0, FALSE)?></td>
    </tr>
    <tr>
        <td ><?=Form::label('date', 'Дата')?>:</td>
        <td><?=Form::input('date', date('d.m.Y'), array('size' => 100, 'id'=>'datepicker'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('categories', 'Категория')?>:</td>
        <td><?=Form::select('cat_id',$cats,$data['cat_id'] )?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('content', 'Текст')?>: </td>
        <td><?=Form::textarea('content_full', $data['content_full'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('status', 'Статус')?>:</td>
        <td><?=Form::checkbox('status', 1, true)?> Активен</td>
    </tr>
    <tr>
        <td>  <?=Form::label('image', 'Загрузить изображения')?>: </td>
        <td>
            <input type="file" id="files" name="files[]" multiple />
            <input type="hidden" value="" name="selectImage" id="selectedFile">
            <input type="hidden" value="1" id="maxFiles">
            <input type="hidden" value="imgarticle" id="imagedir">
            <a id="uploadServer" href="#" onclick="return false;">Загрузить с сервера </a>
            <div id="selectedImageView"></div>
            <div id="dropped-files">
                <output id="list"></output>
                <br/>
                <div id="upload-button">
                    <span>0 Файлов</span>
                    <a href="#" class="upload">Загрузить</a>
                    <a href="#" class="delete">Удалить</a>
                </div>
            </div>
            <!-- Список загруженных файлов -->
            <div id="file-name-holder">
                <ul id="uploaded-files">
                    <p>Загруженные файлы</p>
                </ul>
            </div>
        </td>

    </tr>
    <tr>
        <td ><?=Form::label('meta_title', 'Заголовок страницы, title')?>:</td>
        <td><?=Form::input('meta_title', $data['meta_title'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_keywords', 'Метаданные, keywords')?>:</td>
        <td><?=Form::input('meta_keywords', $data['meta_keywords'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_description', 'Метаданные, description')?>:</td>
        <td><?=Form::input('meta_description', $data['meta_description'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('add', 'Добавить')?></td>
    </tr>
</table>
<?=Form::close()?>