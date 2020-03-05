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
    
$(document).ready(function(){
    var dir = $('#importdir',window.opener.document).val();
    var im = $('#listFiles :selected');
    if(im.text() != "") ShowImg(im.val());
    
    var filename = $('#selectedFile',window.opener.document).val();  
    $("#listFiles [value='"+ filename +"']").attr("selected", "selected");
   // Выбор изображения на сервере
   $('#choiceFromList').click(function() {
       var im = $('#listFiles :selected');
       if(im.text() != "") 
       {
           var selectedFile = $('#selectedFile',window.opener.document);
           var outputDiv = $('#fileimport',window.opener.document);
           selectedFile.val(im.val());
           outputDiv.html(im.val());
           $("#files",window.opener.document).attr('disabled', false);
           $("#dataagr",window.opener.document).attr('disabled', false);
           window.close();
       }
   });
    $('#deleteFromList').click(function(){
        if(confirm('Действительно удалить?'))
        {
            var filepath =  $('#listFiles :selected').val();
            var filepatharr = filepath.split('/');
            var filename = filepatharr[filepatharr.length-1];
            
            $.post('/admin/importfile/deleteimage/'+ dir +'/' + filename,  function(data) {
                // alert(data);
            });
               location.replace("/admin/importfile/index/"+ dir +"/");
        }
    });   
   
   
});    

</script>
</head>

<body>
<form action="" enctype="multipart/form-data" method="post">
    <table border="1" cellpadding="0" cellspacing="0" width="99%">
        <tr>
            <td>
             <select id="listFiles" name="filesList" size="17" style="width:400px;"  OnChange="">
                 <option value="">Не выбрано</option>
                 <?php foreach($files as $file):?>
                 <option value="<?= $webdir . $file;  ?>" <?php if($file == $filename) echo ' selected="selected"';?> > <?=$file?> </option>
                 <?php endforeach;?>
             </select> 
             <br/>  
                <input id="choiceFromList" type="button" value="Выбрать" />    
                <input id="deleteFromList" type="button" value="Удалить" />    
            </td>
        </tr> 
    </table>  
       
<form action="" enctype="multipart/form-data" method="post">
    <input type="file" id="files" name="files" />
    <input type="submit" name="upload" value="Загрузить"/>
</form>
</body>
</html>
