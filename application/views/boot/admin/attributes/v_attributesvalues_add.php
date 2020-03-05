<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div style="color: #cc0000; background-color: #ffcccc; width:500px; margin:7px auto; font: 18px sans-serif;">
    &nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/attributes/attributesvaluesadd/' . $attr_id)?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('title', 'Наименование')?>:</td>
        <td><input type="text" name="title" id="value_title" value="<?=$data['title']?>" size="100"></td>
    </tr>
    <tr>
        <td><?=Form::label('path', 'Введите значение или <br>диапазон значений через "-"')?>:</td>
        <td><?=Form::input('path', $data['path'], array('size' => 100,'id'=>'value_path'))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('add', 'Добавить')?></td>
    </tr>
</table>
<?=Form::close()?>