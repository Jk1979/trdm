<?php if(isset($products) && ($products->count() > 0)):?>

      <div class="box-heading">
          <h6>Рекомендуем</h6>  
      </div>   
   <div class="owl-carousel">
    <?php foreach($products as $i => $product):?>
      <?php 
      if($product->images->count_all()>0) $im_name = 'public/uploads/imgproducts_small/small_' . $product->main_image->name;
      else $im_name = 'public/img/book.jpg';
      ?>
      <div class="topproduct">
        <div class="slide_item">
          <div class="imgitem">
           <?=html::anchor("product/$product->path",html::image($im_name, array('alt'=>$product->title)))?>
         </div>
         <?=html::anchor("product/$product->path", "$product->title",array('class'=>'toptitle'))?>
         <p><?php if ($product->price > 0):?>Цена: <span><?=$product->price?> грн</span> <?php else:?> &nbsp;<?php endif;?></p>
         <a onclick="ElementAdd(<?=$product->prod_id?>, 1);return false;" href="" class="button">Купить</a>
       </div>
     </div>
   <?php endforeach?>

 </div>

<?php endif;?>





