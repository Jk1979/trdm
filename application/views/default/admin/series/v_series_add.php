<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div style="color: #cc0000; background-color: #ffcccc; width:500px; margin:7px auto; font: 18px sans-serif;">
    &nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/series/add/' . Request::current()->param('id'))?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
        <td>
         <input type="text" name="title" 
                id="brand_title" value="<?=$data['title']?>" size="100" 
                onkeyup="$('#serie_path').val(genpath($(this).val()));">    
        </td>
    </tr>
    <tr>
        <td ><?=Form::label('path', 'Алиас')?>:</td>
        <td><?=Form::input('path', $data['path'], array('size' => 100,'id'=>'serie_path'))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('description', 'Описание')?>: </td>
        <td><?=Form::textarea('description', $data['description'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('add', 'Добавить')?></td>
    </tr>
</table>
<?=Form::close()?>
