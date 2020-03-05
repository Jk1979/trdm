<script>
    $(document).ready(function() {
    $('#uploadsrv').click(function (e){
        var dir = $('#importdir').val() ? $('#importdir').val() : 'import';
       console.log(dir);
        wnd = window.open('/admin/importfile/index/' + dir, 'wnd', "left=50,top=100, width=" + (450) + ",height=" + (screen.height-350) + ",resizable=yes,scrollbars=yes,status=yes");
        wnd.focus();
        });
        $('#files').click( function (e){ $('#importfrm').submit(); });
    });
</script>
  <div class="fileloadbox">
   <form action="" id="importfrm" method="post">
    <input type="hidden" name="directory" value="import" id="importdir">
    <input type="hidden" value="" name="selectFile" id="selectedFile">
    <span>Выберите файл для загрузки: </span><a href="#" id="uploadsrv">Выберите</a>|<a href="#">Удалить</a>
    <span id=fileimport></span>
    <input type="submit" value="Импортировать" name="files" id="files" class="importbut" disabled/>
    </form>        
</div>  
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php if(isset($countprods)):?> <div><?=$countprods?></div> <?php endif;?>
<?php if(isset($log)): ?>
<?php foreach($log as $l):?>
<div class="log">
    <span><?=$l;?></span>
</div>
<?php endforeach; ?>
<?php endif; ?> 

    
