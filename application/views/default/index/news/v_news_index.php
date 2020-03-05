<hr/>
<br/>
<?php foreach($news as $new):?>
<div class="index_news">
<h3><?=HTML::anchor('news/' . $new->path, $new->title)?></h3>
<p class="date"><?=$new->date?></p>
<p><?=$new->content?></p>
</div>
<div class="news_block_bottom">
    <a href="<?=URL::base() . 'news/' . $new->path?>">Читать полностью...</a>
</div>
<?php endforeach?>
<?=$pagination?>