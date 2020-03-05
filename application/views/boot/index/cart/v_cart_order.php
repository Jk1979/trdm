<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>


<?=Form::open('cart/order')?>
<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('pay_id', 'Способ оплаты:')?></td>
        <td><?=Form::select('pay_id', $pay,$data['pay_id'])?></td>
    </tr>
    <tr>
        <td ><?=Form::label('delivery_id', 'Способ доставки:')?></td>
        <td><?=Form::select('delivery_id', $delivery,$data['delivery_id'],
                array('id'=>'delivery', 'onchange'=>'mustAdress();'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('name', 'Ф.И.О.:')?></td>
        <td><?=Form::input('name', $data['name'], array('size' => 60, 'required' => 'required'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('phone', 'Телефон:')?></td>
        <td><?=Form::input('phone', $data['phone'], array('size' => 60, 'required' => 'required'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('email', 'email')?>:</td>
        <td><?=Form::input('email', $data['email'], array('size' => 60,'type'=>'email', 'required' => 'required'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('adress', 'Адрес')?>:</td>
        <td><?=Form::input('adress', $data['adress'], array('size' => 60,'id'=>'adress'))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('content', 'Описание')?>: </td>
        <td><?=Form::textarea('content', $data['content'], array('cols' => 60, 'rows' => 15))?></td>
    </tr>
    <tr>
        <td colspan="2" align="left"><?=Form::submit('order', 'Заказать', array('class'=> 'order_but'))?></td>
    </tr>
</table>
<?=Form::close()?>




