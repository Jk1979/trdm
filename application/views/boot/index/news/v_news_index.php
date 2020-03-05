<hr/>
<?php foreach($news as $new):?>
<div class="row">
   <div class="col-md-12 col-sm-12 "> 
    <h3><?=HTML::anchor('news/' . $new->path, $new->title)?></h3>
    <p class="date"><?=$new->date?></p>
    <p><?=$new->content?></p>
    <a href="<?=URL::base() . 'news/' . $new->path?>">Читать полностью...</a>
    </div>
</div>
<?php endforeach?>
<?=$pagination?>