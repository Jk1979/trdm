<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Выбор картинки</title>
    <meta content="text/html; charset=utf8" http-equiv="content-type">
   <?php if(isset($styles)):?>
        <?php foreach($styles as $style): ?>
         <link href="<?php echo URL::base(); ?>public/css/<?php echo $style; ?>.css" 
         rel="stylesheet" type="text/css" />
         <?php endforeach; ?>
    <?php endif;?>
    <?php if(isset($scripts)):?>
        <?php foreach ($scripts as $script):?>
        <?=HTML::script($script)?>
    <?php endforeach;?>
    <?php endif;?>
    <style>
    .upload-img-del {
    color: #00758f;
    cursor: pointer;
    margin-top: 7px;
    }
    .thumb {
  height: 75px;
  border: 1px solid #000;
  margin: 15px 5px 0 0;
}
#imagepreview
{
    background-color: #ffffff;
    width: 141px;
    height: 121px;
    text-align: center; 
    margin-top: 5px;
}
#imagepreview img {
    width : 200px;
    height:250px;
}
    </style>  
<script>
function ShowImg(path)
{
 html = '';
 if (path != '')
  html = '<img src="'+path+'" border="0" />';
 document.getElementById('imagepreview').innerHTML = html;
}
$(document).ready(function(){
    
    var im = $('#listFiles :selected');
    if(im.text() != "") ShowImg(im.val());
    
    if( $('#list div[id^=img]', window.opener.document).size() > 0 )
    {
        alert('Вы уже выбрали изображение! Сначала удалите выбранное изображение');
        window.close();
    }
   
    var filename = $('#selectedFile',window.opener.document).val();  
    $("#listFiles [value='"+ filename +"']").attr("selected", "selected");
   
   
});    

</script>
</head>

<body>
<form action="" enctype="multipart/form-data" method="post">
    <table border="1" cellpadding="0" cellspacing="0" width="99%">
        <tr>
            <td width="200">
             <select id="listFiles" name="filesList" size="17" style="width:200px;"  OnChange="ShowImg(this.value);">
                 <option value="">Не выбрано</option>
                 <?php foreach($files as $file):?>
                 <option value="<?= $webdir . $file;  ?>" <?php if($file == $filename) echo ' selected="selected"';?> > <?=$file?> </option>
                 <?php endforeach;?>
             </select> 
                <input id="choiceFromList" type="button" value="Выбрать" />    
                <input id="deleteFromList" type="button" value="Удалить" />    
            </td>
            <td valign="top">
                <strong>Просмотр:</strong> 
                <div id="imagepreview" >
                </div>
            </td>
        </tr> 
    </table>  
       
        <?=Form::label('image', 'Загрузить изображения')?>: 
        <input type="file" id="files" name="files[]" multiple />
        <br/>
        <label>Выбранные файлы:</label>
        <div id="dropped-files">
        <output id="list"></output>
        <br/>
            <div id="upload-button">
                  <span>0 Файлов</span>
                  <a href="#" class="upload" >Загрузить</a>
               <a href="#" class="delete">Удалить</a>
           </div>
        </div>  
        <!-- Список загруженных файлов -->
        <div id="file-name-holder">
           <ul id="uploaded-files">
              <p>Загруженные файлы</p>
           </ul>
        </div>   
</form>
</body>
</html>
