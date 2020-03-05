<br/>
<?php if($errors):?>
<?php foreach ($errors as $error):?>
<div class="error"><?=$error?></div>
<?php endforeach?>
<?php endif?>

<?=Form::open('admin/pages/edit/' . $page_id)?>
<table width="100%" cellspacing="5">


    <tr>
        <td ><?=Form::label('alias', 'Алиас')?>:</td>
        <td><?=URL::base('http')?>page/ <?=Form::input('alias', $data['alias'], array('size' => 20))?></td>
    </tr>
    
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
        <td><?=Form::input('title', $data['title'], array('size' => 100))?></td>
    </tr>
    
   
    <tr>
        <td valign="top"><?=Form::label('text', 'Текст')?>: </td>
        <td><?=Form::textarea('text', $data['text'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
     <tr>
        <td valign="top"><?=Form::label('status', 'Статус')?>:</td>
        <td><?=Form::checkbox('status', 1, (bool) $data['status'])?> Активен</td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('submit', 'Сохранить')?></td>
    </tr>
</table>
<?=Form::close()?>
