<?php foreach ($menu as $name => $menu):?>
<?php if(in_array(mb_strtolower($select), $menu)):?>
<?=Html::anchor('admin/'. $menu[0], $name, array('class' => 'select'))?>
<?php else:?>
<?=Html::anchor('admin/'. $menu[0], $name)?>
<?php endif?>
<?php endforeach?>