<script>
  $(function() {
    $("#tabs" ).tabs();
  });
  </script>
<br/>
<table width="100%" border="0" cellpadding="10" cellspacing="10">
    <tr>
        <td width="150" align="center" valign="top">
        <?php if(count($images) > 0):?>
        <div id="main_icon">
        <?php //=html::anchor('public/uploads/imgproducts/'.$mainimage->name,html::image('public/uploads/imgproducts/' . $mainimage->name, array('width' => '150','rel'=>'lightbox-one')))?>
        <a href="/public/uploads/imgproducts/<?=$mainimage->name?>" rel="lightbox" title="<?=$product['title']?>"><img src="/public/uploads/imgproducts/<?=$mainimage->name?>" alt=" " width="150" /></a>
        </div>
        <table align="center" width="100" border="0" class="images_icon" >
            <tr align="center">
                <?php foreach($images as $image):?>
                <td><?=html::image('public/uploads/imgproducts_small/small_' . $image->name, array('width' => '50', 'class' => 'icon'))?></td>
                <?php endforeach?>
            </tr>
        </table>
        <?php else:?>
        <div id="main_icon">
            <?=html::image('public/img/book.jpg', array('width' => '150'))?>
        </div>
         <?php endif?>
        </td>
        <td align="left" valign="top">
          <p class="prod-code">Артикул: <?=$product['code']?></p>
          <div class="prod-count">
              Количество:
              <input type="text" value="1" id="<?='element_' . $product['prod_id']. '_count'?>" onkeyup="price_calculate(<?=$product['prod_id']?>);">
              <div class="prod-count-ctrl">
                <a class="prod-count-up" href="#" onclick="price_calculate(<?=$product['prod_id']?>, false, &quot;+&quot;);return false;"></a>
                <a class="prod-count-down" href="#" onclick="price_calculate(<?=$product['prod_id']?>, false, &quot;-&quot;);return false;"></a>
              </div>
              шт.
          </div>
          <span class="prod-price-c" id="<?='element_' . $product['prod_id']. '_price'?>" style="display: none"><?=$product['price']?></span> 
            <span class="prod-price-c" id="<?='element_' . $product['prod_id']. '_price_total'?>"><?=$product['price']?></span> 
            <span class="prod-price-u">грн.</span>
            <br/>
            <a onclick="ElementAdd(<?=$product['prod_id']?>, $(<?="&quot;#element_" .$product['prod_id'] . "_count&quot;"?>).val()*1);return false;"
               href=""> <?=html::image('public/img/buy.gif')?></a> 
        </td>
    </tr>
    <tr>
        <td colspan="2">
         <div id="tabs">
              <ul>
                <li><a href="#tabs-1">Описание товара</a></li>
                <li><a href="#tabs-2">Отзывы</a></li>
              </ul>
              <div id="tabs-1">
                <p class="product_content"><?=$product['content']?></p>
              </div>
               <div id="tabs-2">
                <h3>Отзывы:</h3>
                    <?php if (count($comments) > 0):?>
                    <?php foreach($comments as $comment):?>
                    <div class="box">
                        <h4><?=$comment->author?></h4>
                        <p><?=$comment->content?></p>
                    </div>
                    <?php endforeach?>
                    <?php else:?>
                    <div class="box">Нет отзывов</div>
                    <?php endif?>
                    <?=$comproduct?>;
              </div>
            </div>   
        </td>
        <td></td>
    </tr>
</table>


