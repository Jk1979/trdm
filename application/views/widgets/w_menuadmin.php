<?=HTML::anchor('', HTML::image('public/img/home.png'))?>
<?php foreach ($menu as $name => $menu):?>
<?php if(in_array(mb_strtolower($select), $menu)):?>
<?=HTML::anchor('admin/'. $menu[0], $name, array('class' => 'select'))?>
<?php else:?>
<?=HTML::anchor('admin/'. $menu[0], $name)?>
<?php endif?>
<?php endforeach ?>
<?=HTML::anchor('logout', HTML::image('public/img/quit.png'))?>