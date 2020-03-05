<?php if($data):?>
<div class="block_one">
    <?php if($data->title_one):?>
    <h1 class="h1"><?=$data->title_one?></h1>
    <?php endif;?>
    <?php if($data->block_one):?>
    <p><?=$data->block_one?></p>
    <?php endif;?>
</div>
<div class="block_two">
    <?php if($data->title_two):?>
    <h1 class="h1"><?=$data->title_two?></h1>
    <?php endif;?>
    <?php if($data->block_two):?>
    <p><?=$data->block_two?><p>
    <?php endif;?>
</div>
<?php endif;?>