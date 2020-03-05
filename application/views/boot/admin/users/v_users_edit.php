<br/>
<?php if($errors):?>
 <?php foreach ($errors as $error):?>
        <?php if(is_array($error)):?>
        <div class="error"><?='Ошибка ввода пароля'; ?></div>
        <?php else:?>
        <div class="error"><?=$error ?></div>
        <?php endif;?>            
<?php endforeach?>
<?php endif?>

<?=Form::open('admin/users/edit/'. $id)?>
<table width="300" cellspacing="5">
    <tr>
        <td ><?=Form::label('username', 'Логин')?>:</td>
        <td><?=Form::input('username', $data['username'], array('size' => 20))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('first_name', 'ФИО')?>:</td>
        <td><?=Form::input('first_name', $data['first_name'], array('size' => 20))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('email', 'Email')?>:</td>
        <td><?=Form::input('email', $data['email'], array('size' => 20))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('roles', 'Роли')?>:</td>
        <td><?=Form::select('roles[]', 
                                $roles,
                                $data['roles'], array('multiple'=>"multiple") )?></td>
    </tr>
     <tr>
        <td ><?=Form::label('password', 'Пароль')?>:</td>
        <td><?=Form::password('password', '', array('size' => 20,  'placeholder'=> 'Введите пароль'))?></td>
    </tr>
     <tr>
        <td ><?=Form::label('password_confirm', 'Повторить пароль')?>:</td>
        <td><?=Form::password('password_confirm','', array('size' => 20,  'placeholder'=> 'Повторите пароль'))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('save', 'Сохранить')?></td>
    </tr>
</table>
<?=Form::close()?>

        