<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div style="color: #cc0000; background-color: #ffcccc; width:500px; margin:7px auto; font: 18px sans-serif;">
    &nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/attributes/edit/' . $id)?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('name', 'Название')?>:</td>
        <td><input type="text" name="name" id="attribute_name" value="<?=$data['name']?>" size="100"></td>
    </tr>
    <tr>
        <td><?=Form::label('path', 'Алиас')?>:</td>
        <td><?=Form::input('path', $data['path'], array('size' => 100,'id'=>'attribute_path'))?></td>
    </tr>
    <tr>
    <td><?=Form::label('showcats', 'Показывать в категориях')?>:</td>
    <td><?=Form::select('categories[]',$cats,$data['categories'], array('multiple'=>"multiple",'id'=>'category') )?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('save', 'Сохранить')?></td>
    </tr>
</table>
<?=Form::close()?>