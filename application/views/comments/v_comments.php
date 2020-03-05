<script>
      function sendComment() {
      var msg   = $('#formx').serialize();
        $.ajax({
          type: 'POST',
          url: '/ajax/createComment/',
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
                        $('#user').val('');
                        $('#message').val('');
                        $('#email').val('');
                        $('#errors').css('visibility','hidden');
                        $("#comments").append('<div><strong>Имя пользователя:</strong> ' + result.user + 
                        '<br/><strong>Комментарий пользователя:</strong><br/> ' + result.message + '<hr/></div>');
                    }
                }
          },
          error:  function(xhr, str){
                alert('Возникла ошибка: ' + xhr.responseCode);
            }
        });
 
    }
    
    </script>
 
<?php if(isset($comments)):?>
    <div id="comments">
<?php foreach($comments as $comment): ?>
    <strong>Имя пользователя:</strong><br />
    <?php echo $comment->user; ?><br />
    <strong>Комментарий пользователя:</strong><br />
    <?php echo $comment->message; ?>
    <br /><hr /><br />
<?php endforeach; ?>
    </div>
<?php endif;?>
 <div id="errors" style="visibility: hidden" class="error"> </div>      
<form class="form" id="formx" action="" method="post">
    <input id="articleid" name = "articleid" type="hidden" value="<?=  Request::current()->param('id');?>">  
  <table width="500" border="0" cellspacing="5" cellpadding="5">
    <tr>
      <td>
      Ваше имя: <br />
      <input name="user" id = "user" type="text" required value="<?php echo HTML::chars(Arr::get($_POST, 'user')); ?>" />
      </td>
      <td>
      <strong style="color:#f00;"><?php if(isset($errors['user'])) echo $errors['user']; ?></strong>
      </td>
    </tr>
    <tr>
      <td>
      Ваш e-mail: <br />
      <input id="email" name="email" type="email" value="<?php echo HTML::chars(Arr::get($_POST, 'email')); ?>" />
      </td>
      <td>
      <strong style="color:#f00;"><?php if(isset($errors['email'])) echo $errors['email']; ?></strong>
      </td>
    </tr>
    <tr>
      <td>
      Сообщение: <br />
     <textarea id="message" name="message" cols="25" rows="5"><?php echo HTML::chars(Arr::get($_POST, 'message')); ?>
</textarea>
      </td>
      <td valign="middle">
        <strong style="color:#f00;"><?php if(isset($errors['message'])) echo $errors['message']; ?></strong>
      </td>
    </tr>
  </table>
    <input name="snd" type="button" value="Отправить" onclick="sendComment();return false;"/>
</form>
    
    <div id="results"></div>