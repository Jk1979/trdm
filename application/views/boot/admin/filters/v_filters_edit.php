<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?=Form::open('admin/filters/edit/' . $id)?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('filter_title', 'Название')?>:</td>
        <td>
         <input type="text" name="filter_title"  value="<?=$data['filter_title']?>" size="100">    
        </td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('save', 'Сохранить')?></td>
    </tr>
</table>
<?=Form::close()?>