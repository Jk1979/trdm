<div class="menu_links">
<?php foreach($categories as $cat):?>
	<?php if($cat->parent_id == 0): continue; endif;?>
    <?php if($cat->parent_id == 1): $root = 'font-weight: bold'; ?>
    <?php else: $root = ''; ?>
    <?php endif;?>
<?php if($select == $cat->path):?>
    <?=html::anchor('cat/' . $cat->path,  $cat->title, array('class' => ' select','style' => $root))?>
    <?php else:?>
    <?=html::anchor('cat/' . $cat->path, $cat->title, array('style' => $root))?>
<?php endif?>
<?php endforeach?>
</div>



