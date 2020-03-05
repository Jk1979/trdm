<div class="row">
    <?php if(count($images) > 0):?>
    <div class="col-md-3">
        <div id="main_icon">
        <a href="/public/uploads/imgproducts/<?=$mainimage->name?>" data-lightbox="image-group" title="<?=$product['title']?>">
            <img src="/public/uploads/imgproducts_small/small_<?=$mainimage->name?>" alt="<?=$product['title']?>"/>
        </a>
        </div>
        <?php if(count($images) > 1):?>
        <table align="center" width="150" border="0" class="images_icon" >
                <tr align="center">
                    <?php foreach($images as $image):?>
                     <td><?php if($image->name == $mainimage->name) continue; ?>
                     <a href="/public/uploads/imgproducts/<?=$image->name?>" data-lightbox="image-group" title="<?=$product['title']?>">
                     <?=html::image('public/uploads/imgproducts_small/small_' . $image->name, array('width' => '100', 'style'=>'cursor:pointer'))?>
                     </a></td>
                    <?php endforeach?>
                </tr>
        </table>
        <?php endif?>
     </div> 
     <?php endif?>
     <div class="col-md-5">

         <p class="changeback">Официальная гарантия | Обмен / возврат в течении 14 дней</p>
         <p id="avail"  data-code="<?=$product['code']?>" style="cursor:pointer;">Проверить наличие</p>
         <p class="prod-code">Код товара: TM-<?=$product['code']?></p>  
         <div>    
             <span class="price"><?php if($product['price']>0) echo $product['price'] . ' грн.'; else echo 'Цену уточняйте ';?> </span> 
             <span class="unit">( за 1 <?php if($product['unit']) echo $product['unit']; else echo "шт."?> )</span>
         </div>    
          <?php if($product['present']==0):?> <p style="margin-left: 10px;color:red; font-weight: bold;">Нет в наличии</p><?php endif;?> 
          <div class="prod-count">
              <span class="count-span">Количество:</span>
              <input type="text" value="1" id="<?='element_' . $product['prod_id']. '_count'?>" onchange="calculate_p(<?=$product['prod_id']?>,false);">
              <div class="prod-count-ctrl">
                <a class="cbut prod-count-up" href="#" onclick="calculate_p(<?=$product['prod_id']?>, false, &quot;+&quot;);return false;"></a>
                <a class="cbut prod-count-down" href="#" onclick="calculate_p(<?=$product['prod_id']?>, false, &quot;-&quot;);return false;"></a>
              </div>
              <span style="display: none" id="<?='el_' . $product['prod_id']. '_unit'?>"><?php if(isset($product['unit'])) echo $product['unit'];?></span>
              <span style="display: none" id="<?='el_' .$product['prod_id']. '_pieces'?>"><?php if(isset($product['pieces'])) echo $product['pieces'];?></span>
              <span style="display: none" id="<?='el_' .$product['prod_id']. '_meters'?>"><?php if(isset($product['meters'])) echo $product['meters'];?></span>

              <?php if($product['unit']) echo $product['unit']; else echo "шт."?>
              <!--<div class="product_q_select_wrap">
                  <select id="product_q_select">
                      <option class="product_q_m active" value="m" name="product_q_select">м2</option>
                      <option class="product_q_pi active" value="pi" name="product_q_select">штуки</option>
                      <option class="product_q_pa active" value="pa" name="product_q_select">упаковки</option>
                  </select>
              </div>-->
              <?php if($product['meters'] > 0 && $product['pieces'] > 0):?>
              <input class="inputPieces" type="text" value="1" id="<?='element_' . $product['prod_id']. '_pieces'?>" onchange="calculate_pc(<?=$product['prod_id']?>);">  шт.
              <?php endif;?>
              <div style="margin: 10px 0px 10px 20px;color: blue;" class="in_pack"></div>
          </div>
          <div class="prod_price">
            <span class="prod-price-c" id="<?='element_' . $product['prod_id']. '_price'?>" style="display: none"><?=$product['price']?></span> 
            <span class="sum_total">Всего на сумму: </span><span class="prod-price-c" id="<?='element_' . $product['prod_id']. '_price_total'?>"><?=$product['price']?></span> 
            <span class="prod-price-u">грн.</span>
          </div>
            <a class="button" onclick="ElementAdd(<?=$product['prod_id']?>, $(<?="&quot;#element_" .$product['prod_id'] . "_count&quot;"?>).val()*1);return false;"
               href="">Купить</a>
      </div>
    <?php if(in_array($product['cat_id'],array(53,54,55,61,62,56))){?>
    <div class="col-md-4">
        <a href="/catalog/cat/stroitelnie_smesi" style="display: block;text-align: center; "><img src="/public/img/zatirka.jpg">
            <p style="color:#4285be;font-size: 24px;">Затирки для плитки</p>
        </a> 
    </div>
    <?php } ?>
</div>


      <div class="prod-info" id='tabs_1'>
        <ul class="prod-tab">
          <?php if(isset($product["content"])):?>
            <li class="selected">Описание</li>
          <?php endif?>
            <li>Доставка</li>
            <li>Варианты оплаты</li>
        </ul>
        <div class="prod-info-box" id='tabs_1_content'>
          
            <div class="prod-desc text" style="display:block">
              <?php if(isset($product["content"]) && $product["content"]):?>
                      <?=$product["content"];?>
              <?php else:?> Описание товара <?=$product["title"]?>
              <?php endif?>
              <?php if(count($characks)>0):?>
              <p style="font-size:21px;font-weight: bold;">Характеристики товара</p>
              <table width="100%" border="0" cellspacing="0" class="tblcharacks">
                  <tbody>
                <?php foreach($characks as $f => $op):?>
                 <tr class="tr-prods">
                    <td  width="25"><?=$f?></td>
                    <td  width="100"><?=$op;?></td>
                 </tr>
                <?php endforeach;?>
                  </tbody>
                </table>
              <?php endif?>
            </div>
          <div class="prod-desc text" style="display:none">
            <p class="delivery-details">
            <p>Доставку по Киеву мы осуществляем при заказе от 1000грн. 
            По Киеву доставка осуществляется с 9 до 18 часов.</p>
              <p>Доставка товара при заказе на сумму более 15000 грн и весе до 2000 кг - <span style="color: red;">бесплатно</span></p>
            <p><strong>Подъем товара на этаж</strong></p>
            <p>При доставке товара Вы можете заказать дополнительную услугу - 
            подъем товара на этаж. Стоимость данной услуги оговаривается отдельно и 
            расчитывается в зависимости от веса и количества товара.
            Как правило стоимость услуги составляет 12 грн / м2, в случае если это керамическая плитка
            и в доме работает лифт. (При не работающем лифте - 10 грн/м2 за каждый этаж).
            </p>
            <p>
            Доставку по Украине мы осуществляем, как правило, перевозчиком "Новая почта". Курьер приезжает к нам на склад 
            и забирает товар. По факту доставки клиент расчитывается непосредственно с представителями "Новая почта".
            Важно: по прибытию на склад Новой почты, товар хранится там 5 дней. В течении этого времени товар нужно забрать, 
            так как на 5-й день простоя Новая почта автоматически отправляет товар обратно отпраивтелю.
            </p>
            <ul>
              <li>Стоимость доставки товаров  согласуется с нашим менеджером и составляет в 
                  среднем  150 - 450грн за крупногабаритный товар, в зависимости от адреса доставки.</li>
              <li>- доставка осуществляется на следующий день после оформления заказа</li>
              <li>- доставка производится до парадного без выгрузки</li>
              <li>- стоимость выгрузки и заноса товара заранее оговаривается с менеджером</li>
            </ul>
            </p>
          </div>
          <div class="prod-desc text" style="display:none">
            <p class="pay-details">
            <ul>
            <li>Наличными курьеру при получении товара (Киев, Киевская обл.). Для заказного товара необходима предоплата.</li>
            <li>Переводом на карту ПриватБанка.</li>
            <li>Безналичная оплата с НДС.</li>
            </ul>
            </p>
          </div>
        </div>
      </div>
<?php if(isset($anotherProducts) && count($anotherProducts)>0):?>
<div class="anotherProds" id='tabs_2'>
<ul class="prod-tab">
        <li class="selected">Товары из коллекции <?=$prodSerie->title?>, бренд <?=$prodBrand->title?></li>
</ul>
<div class="prodsAnotherBox" id="tabs-2-content">
<div class="text" style="display:block">
      <div class="row prodrow">
    <?php foreach($anotherProducts as $i => $prod):?>
        <?php 
               if($prod->images->count_all()>0) $im_name = 'public/uploads/imgproducts_small/small_' . $prod->main_image->name;
               else $im_name = 'public/img/noimage.jpg';
        ?>
		<div class = "col-lg-4 col-md-6 col-sm-6 col-xs-12 product">
           <div class="gtile-i-wrap">
                <div class="gtile-i">
                <div class="gtile-i-box">
                <div class="prodbox">
                <div class="imgitem">
                <?=html::anchor("product/$prod->path",html::image($im_name,array('alt'=>$prod->title)))?>
                </div> 
                 <div></div>
                <?=html::anchor("product/$prod->path", "$prod->title",array('class'=>'toptitle'))?>
                <p><span>Код товара: </span>TM-<?=$prod->code?></p>
                <p><span class="catprice"><?=$prod->price?></span><span class="cat-price-u"> грн.</span><p>
                <a class="button" onclick="ElementAdd(<?=$prod->prod_id?>, 1);return false;" href="">Купить</a>
                </div>
                </div>
                </div>
                </div>
      </div>
    <?php endforeach?>
    </div>
</div>
</div>
</div>
<?php endif?>
