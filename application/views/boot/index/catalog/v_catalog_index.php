<br/>
<?php foreach($categories as $cat):?>
    <?php if($cat->parent_id == 0): continue; endif;?>
    <?=html::anchor('cat/'. $cat->path . '', "<h4>$cat->title</h4>")?><br/>
<?php endforeach?>
<br/>    
<?php if(isset($products)):?>
    <?php if($products->count() > 0):?>

    <br/>
    <table border="0" width="100%"  cellpadding="0" cellspacing="10">
        <tr>
        <?php foreach($products as $i => $product):?>
             <?php if ($i % 3 == 0):?>
                </tr><tr>
            <?php endif ?>
            <td align="center" width="33%">
			
            <?php $cat = $product->categories->find();?>    
            <?php if(count($product->images->find_all())>0): ?>
                <?=html::anchor("product/$product->path",
                html::image('public/uploads/imgproducts/' . $product->main_image->name, array('width' => '150')))?><br/>
                <?php else:?>
                <?=html::anchor("product/$product->path",
                html::image('public/img/book.jpg', array('width' => '150')))?>
            <?php endif?>
                    <?=html::anchor("product/$product->path", "<h4>$product->title</h4><br/>")?>
                    <div class="cost"><?=$product->price?> грн.</div>
				
            </td>

        <?php endforeach; ?>
        </tr>
    </table>
    <?php else:?>
    <div class="empty">Нет товаров в этой категории</div>
    <?=$search_form?>
    <?php endif?>
<?php endif ?>

