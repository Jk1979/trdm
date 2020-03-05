<div class="widget"
<h3>Меню</h3>
<br />
<?php if(isset($menu)):?>    
<ul>
<?php foreach ($menu as $anchor => $name):?>   
    <?php if(!is_array($name)):?>        
         <li><a href="<?=$anchor?>"><?=$name?></a></li>
    <?php endif; ?>   
        <?php if(is_array($name)):?>
                <ul>
                <?php foreach ($name as $anch => $name2):?>
                    <li><a href="<?=$anch?>">&nbsp;&nbsp;<?=$name2?></a></li>        
                <?php endforeach;?>
                </ul>
        <?php endif; ?>
<?php endforeach;?>
</ul>
<?php endif; ?>
</div>


