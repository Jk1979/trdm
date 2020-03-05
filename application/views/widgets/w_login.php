<div class="w_login">
<h2>Вход</h2>
<br/>
<?php if(!$logged_in):?>
<?=Form::open('login')?>
    <?=Form::label('username', 'Логин')?>:<br/>
    <?=Form::input('username', '', array('size' => 20))?><br/>
    <?=Form::label('password', 'Пароль')?>:<br/>
    <?=Form::password('password', '', array('size' => 20))?><br/>
    <?=Form::checkbox('remember')?> Запомнить<br/><br/>
    <?=Form::submit('submit', 'Войти')?>
    <?=HTML::anchor('register', 'Регистрация')?>
<?=Form::close()?>
<?php else:?>
    <?=$user->first_name?><br/><br/>
    <?php if ($auth->logged_in('admin')):?>
        <?=HTML::anchor('admin', 'Панель администратора')?>
    <?php else:?>
        <?=HTML::anchor('account', 'Личный кабинет')?>
    <?php endif?>
    <br/><br/>
    <?=HTML::anchor('logout', 'Выйти')?>
<?php endif?>
</div>



