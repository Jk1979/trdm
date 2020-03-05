<div class="w_news">
    <div class="wnews">Новости</div>
    
    <?php foreach($news as $new):?>
    <div class="index_news">
    <p class="onenewtitle"><?=HTML::anchor('news/' . $new->path, $new->title)?></p>
    <p class="date"><?=$new->date?></p>
    <p><?=Text::limit_words($new->content,10)?></p>
    </div>
    <?php endforeach?>
    <div class="news_block_bottom">
        <p><a href="<?=URL::base() .  'news' ?>">Все новости...</a></p>
    </div>
</div>


