<?php if(isset($products) && $countprs):?>
    <div class="row prodrow">
        <?php foreach($products as $i => $product):?>
            <?php
            if($product->images->count_all()>0) $im_name = 'public/uploads/imgproducts_small/small_' . $product->main_image->name;
            else $im_name = 'public/img/noimage.jpg';

            ?>
            <div class = "col-lg-4 col-md-6 col-sm-6 col-xs-12 product">
                <div class="gtile-i-wrap">
                    <div class="gtile-i">
                        <div class="gtile-i-box">
                            <div class="prodbox">
                                <div class="imgitem">
                                    <?=html::anchor("product/$product->path",html::image($im_name,array('alt'=>$product->title)))?>
                                </div>
                                <div></div>
                                <?=html::anchor("product/$product->path", "$product->title",array('class'=>'toptitle'))?>
                                <p><span>Код товара: </span>TM-<?=$product->code?></p>
                                <div class="prod-cat-count">
                                    <input type="text" value="1" id="<?='element_' . $product->prod_id. '_count'?>" onchange="calculate_p(<?=$product->prod_id?>,false);"/>
                                    <span style="display: none" id="<?='el_' . $product->prod_id. '_unit'?>"><?php if(isset($product->unit)) echo $product->unit;?></span>
                                    <span style="display: none" id="<?='el_' .$product->prod_id. '_pieces'?>"><?php if(isset($product->pieces)) echo $product->pieces;?></span>
                                    <span style="display: none" id="<?='el_' .$product->prod_id. '_meters'?>"><?php if(isset($product->meters)) echo $product->meters;?></span>
                                    <?php if(isset($product->unit)) echo $product->unit; else echo "шт"?>
                                    <?php if(isset($product->meters) && isset($product->pieces)):?>
                                        <?php if($product->meters > 0 && $product->pieces > 0):?>
                                            <input type="number" value="1" class="inputPieces" id="<?='element_' . $product->prod_id. '_pieces'?>" onchange="calculate_pc(<?=$product->prod_id?>);"/>  шт
                                        <?php endif;?>
                                    <?php endif;?>
                                    <div style="color: blue;" class="in_pack"></div>
                                </div>
                                <p><span id="<?='element_' . $product->prod_id. '_price'?>" class="catprice"><?=$product->price?></span><span class="cat-price-u"> грн.</span><?php if(isset($product->unit)):?> <span>(за 1 <?=$product->unit;?>)</span><?php endif?></p>
                                <a class="button" onclick="ElementAdd(<?=$product->prod_id?>,$(<?="&quot;#element_" .$product->prod_id . "_count&quot;"?>).val()*1);return false;" href="">Купить</a>
                                <p class="product_s_desc">
                                    <?php if($product->present==0):?> <p style="color:red;">Нет в наличии</p><?php endif;?>
                                <?=$shortDesc[$product->prod_id]?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <?php endforeach?>
    </div>
    <div class="clearfix"></div>
    <?=$pagination?>
<?php else:?>
    <div class="empty">Товары c заданными условиями фильтра не найдены</div>
<?php endif?>