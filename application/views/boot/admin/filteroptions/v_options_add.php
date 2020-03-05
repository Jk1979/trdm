<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/filteroptions/add/' . $id)?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('option_title', 'Название')?>:</td>
        <td>
         <input type="text" name="option_title"  value="<?=$data['option_title']?>" 
                onkeyup="$('#option_path').val(genpath($(this).val()));" size="100">    
        </td>
    </tr>
    <tr>
        <td ><?=Form::label('option_path', 'Алиас')?>:</td>
        <td><?=Form::input('option_path', $data['option_path'], array('size' => 100,'id'=>'option_path'))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('option_color', 'Цвет')?>: </td>
        <td><?=Form::input('option_color', $data['option_color'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td>  <?=Form::label('option_image', 'Загрузить изображения')?>: </td>
        <td>
            <input type="file" id="files" name="files[]" multiple />
            <input type="hidden" value="" name="selectImage" id="selectedFile">
            <input type="hidden" value="1" id="maxFiles">
            <input type="hidden" value="imgfilter" id="imagedir">
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
        <td colspan="2" align="center"><?=Form::submit('add', 'Добавить')?></td>
    </tr>
</table>
<?=Form::close()?>