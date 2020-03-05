<div class="menu_brands">
    <ul>
        <?php if(count($pbrands)): ?>
            <li><a href="#">Бренды плитка</a>
                <div class="subtopline__brands">
                    <div class="row">
                        <div class="col-md-3 ">
                        <?php foreach($pbrands as $i => $brand):?>
                                <?=HTML::anchor("cat/plitka-$brand->path",$brand->title,array('class'=>'subbrands_link')); ?>
                            <?php if (((($i+1) % 30) == 0) && $i>0):?>
                                </div>
                                <div class="col-md-3">
                            <?php endif;?>
                            <?php endforeach?>
                    </div>
                    </div>
                </div>
            </li>
            <?php endif;?>
                <?php if(count($sbrands)): ?>
                    <li><a href="#">Бренды сантехника</a>
                        <div class="subtopline__brands">
                            <div class="row">
                                <div class="col-md-3 ">
                                <?php foreach($sbrands as $i => $brand):?>
                                        <?=HTML::anchor("cat/santehnika-$brand->path",$brand->title,array('class'=>'subbrands_link')); ?>
                                    <?php if (((($i+1) % 12) == 0) && $i>0):?>
                                        </div>
                                        <div class="col-md-3">
                                        <?php endif;?>
                                <?php endforeach?>
                            </div>
                            </div>
                        </div>
                    </li>
                    <?php endif;?>
                    <li><a href="<?php echo URL::base();?>cat/plitka">Вся плитка</a></li>
                    <li><a href="<?php echo URL::base();?>saninstall">Установка сантехники</a></li>
    </ul>
</div>