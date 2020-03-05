<hr/>
<?php foreach($articles as $article):?>
<div style="border_bottom:#333 1px solid dotted; padding: 10px; margin-bottom:10px;">
<h2><strong><?=$article->title?></strong></h2>
<p><i>Автор: <?=$article->author?></i></p>
<p><i>Дата: <?=$article->date?></i></p>
<br/>
<p><?=Text::limit_words($article->content_short, 50);?></p>
<p style="text-align:right; text-decoration:underline;">
            <a href="<?php echo URL::site('articles/'. $article->id .'-'. $article->path); ?>">Подробнее</a>
 </p>
</div>
<?php endforeach;?>
<?=$pagination?>