<h3>Каталог товаров</h3>
<div class ="cat">
<div style="width:200px">
        <ul class="menu_vert">
            <?php foreach($categories as $cat): ?>
                <?php if($cat->parent_id==1): ?>
            <li><?=HTML::anchor('cat/' . $cat->path,  $cat->title)?>
                <?php if($cat->has_children()):?>
                <ul>
                <?php foreach($cat->children as $l): ?>
                    <li><?=HTML::anchor('cat/' . $l->path,  $l->title)?>
                        <?php if($l->has_children()):?>
                            <ul>
                                <?php foreach($l->children as $m): ?>
                                <li><?=HTML::anchor('cat/' . $m->path,  $m->title)?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif;?>
                    </li>
                <?php endforeach; ?>
                </ul>
                <?php endif;?>
            </li>
             <?php endif;?>
            <?php endforeach; ?>   
        </ul>
    </div>
</div>    





