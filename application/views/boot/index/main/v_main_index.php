<?php if($data):?>
<div class="block_one">
    <?php if($data->title_one):?>
    <h1 class="title-h1"><?=$data->title_one?></h1>
    <?php endif;?>
    <?php if($data->block_one):?>
    <?=$data->block_one?>
    <?php endif;?>
</div>
<div class="block_two">
    <?php if($data->title_two):?>
    <div class="title-h1"><?=$data->title_two?></div>
    <?php endif;?>
    <?php if($data->block_two):?>
    <?=$data->block_two?>
    <?php endif;?>
</div>
<?php endif;?>
<?php if(isset($articles)) echo $articles; ?>
<?php if(isset($mainnews)) echo $mainnews; ?>