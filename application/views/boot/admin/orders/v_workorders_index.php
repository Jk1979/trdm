<?php foreach ($orders as $order):?>
    <div class="order">
           <span class="order-item-str"><b>ФИО:&nbsp;</b><?=$order->name;?><br/></span>
           <span class="order-item-str"><b>Адрес:&nbsp;</b><?=$order->adress;?><br/></span>
           <span class="order-item-str"><b>Телефон:&nbsp;</b><?=$order->phone;?><br/></span>
           <span class="order-item-str"><b>E-mail:&nbsp;</b><?=$order->email;?><br/></span>
           <span class="order-item-str"><b>Дата:&nbsp;</b><?=$order->date;?><br/><br/></span>
           <span class="order-item-str"><b>Общая сумма заказа:</b> &nbsp;<?=$sum_all[$order->order_id]?> грн</span>
           <div class="order-content"><b>Дополнительная информация:</b> &nbsp;<?=$order->content?></div>
           <div class="order_items">
                <ol class="order_item_list">
                    <?php foreach ($prods[$order->order_id] as $prod):?>
                <li>
                    <?=$prod->code;?>&nbsp;&nbsp;<?=HTML::anchor('admin/products/edit/'. $prod->prod_id, $prod->title)?>&nbsp;&nbsp;
                    Цена: <?=$prod->price;?>&nbsp;&nbsp;
                    Кол-во: <?=$sum[$prod->prod_id]['count'];?>&nbsp;
                    <?php if(isset($units[$prod->prod_id])) echo $units[$prod->prod_id]; else echo "шт";?> &nbsp;&nbsp;
                    Сумма: <?=$sum[$prod->prod_id]['sum'];?>
                    <br/>
                </li>       
                    <?php  endforeach?> 
                </ol>
           </div>
      <?php if($order->status == 1):?> 
      <?=HTML::anchor('admin/workorders/orderdone/'. $order->order_id,'Готово', array('class'=>'order-towork'));?>         
      <?php else:?> 
     <span class="orderdone">Заказ выполнен</span>
     <?php endif;?>
     <?=HTML::anchor('admin/workorders/delete/'. $order->order_id,'Удалить', array('class'=>'order-delete'));?>
    </div>



<?php endforeach?>
