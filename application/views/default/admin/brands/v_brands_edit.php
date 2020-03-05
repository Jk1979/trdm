<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div style="color: #cc0000; background-color: #ffcccc; width:500px; margin:7px auto; font: 18px sans-serif;">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/brands/edit/' . $id)?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
        <td>
         <input type="text" name="title" 
                id="brand_title" value="<?=$data['title']?>" size="100" 
                onkeyup="$('#brand_path').val(genpath($(this).val()));"> 
        </td>
    </tr>
    <tr>
        <td ><?=Form::label('path', 'Адрес')?>:</td>
        <td><?=Form::input('path', $data['path'], array('size' => 100,'id'=>'brand_path'))?></td>
    </tr>
    <tr>
        <td>  <?=Form::label('image', 'Загрузить изображения')?>: </td>
        <td>
            <input type="file" id="files" name="files[]" multiple />
            <input type="hidden" value="<?php if(strlen($data['image'])>0) echo 'public/uploads/imgbrand/' . $data['image']?>" name="selectImage" id="selectedFile">
            <input type="hidden" value="1" id="maxFiles">
            <input type="hidden" value="imgbrand" id="imagedir">
            <a id="uploadServer" href="#" onclick="return false;">Загрузить с сервера </a>
            <div id="selectedImageView">
                <?php if(strlen($data['image'])>0):?>
               <span>
                   Выбранное изображение:<br>
                   <img class="thumb" src="<?=URL::base() . 'public/uploads/imgbrand/' . $data['image']?>" title="<?='public/uploads/imgbrand/' . $data['image']?>">
               </span>
               <br>
               <span class="upload-img-del" id="deleteImageView">Удалить</span>
               <?php endif;?>
            </div>
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
        <td valign="top"><?=Form::label('description', 'Описание')?>: </td>
        <td><?=Form::textarea('description', $data['description'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
     <tr>
        <td ><?=Form::label('meta_title', 'Заголовок страницы, title')?>:</td>
        <td><?=Form::input('meta_title', $data['meta_title'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_keywords', 'Заголовок страницы, keywords')?>:</td>
        <td><?=Form::input('meta_keywords', $data['meta_keywords'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_description', 'Заголовок страницы, description')?>:</td>
        <td><?=Form::input('meta_description', $data['meta_description'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('save', 'Сохранить')?></td>
    </tr>
</table>
<?=Form::close()?>