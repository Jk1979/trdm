<script>
      function sendComment() {
         
        var msg   = $('#formx').serialize();
        $.ajax({
          type: 'POST',
          url: '/ajax/createProdcomment/',
          data: msg,
          success: function(data) {
              
             if (data != 0)
                {
                    var result = JSON.parse( data );
                    if(result.error)
                    {
                        $('#errors').css('visibility','visible');
                        for(e in result)
                        {
                            if(e == 'error') continue;
                            $('#errors').append(result[e] + '<br/>');
                        }
                    }
                    else
                    {   
                        $('#errors').empty();
                        $('#author').val('');
                        $('#content').val('');
                        $('#errors').css('visibility','hidden');
                        $("#comments").append('<h4>' + result.author + '</h4>' +
                        '<p>' + result.content + '</p>');
                    }
                } 
          },
          error:  function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
 
    }
    
 </script>
<?php /*if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<b>Поле&nbsp;<?=$error?></b>
<?php endforeach; ?>
<?php endif; */?>
<div id="errors" style="visibility: hidden" class="error"> </div>
<form id="formx" class="form form-prodcomments" action="" method="post">
       <?=Form::label('author', 'Автор:')?>
        <?=Form::input('author', '',array('id'=>'author','required'=>'required'))?>
        <?=Form::label('date', 'Дата:')?>
       <?=Form::input('date', date('d.m.Y'),array('id'=>'date'))?>
       <?=Form::label('content', 'Текст:')?>
        <?=Form::textarea('content', '',array('id'=>'content','required'=>'required'))?>
        <input type="hidden" name="prodid" value="<?=$prodid?>">
        <?=Form::submit('add', 'Добавить отзыв', array('class'=>'button','onclick'=>'sendComment();return false;'))?>
</form>