<hr/>
<?php foreach($articles as $article):?>
<div class="row">
   <div class="col-md-12">
    <div>
    <h2><strong><?=$article->title?></strong></h2>
    <?php if($article->author):?> <p><i>Автор: <?=$article->author?></i></p> <?php endif?>
    <p><i>Дата: <?=$article->date?></i></p>
    <?php if($article->image):?>
        <img style="float:left; margin: 10px 15px 15px 0px" src="<?=URL::site()."public/uploads/imgarticle_small/small_".$article->image?>" alt="<?=$article->title?>" alt="">
    <?php endif; ?>
    <div style="font-size: 16px"><?=Text::limit_words(strip_tags($article->content_short), 150);?></div>
    <p style="text-align:right; text-decoration:underline;">
                <a class="button" style="width:200px;text-transform:none;" href="<?php echo URL::site('articles/'. $article->path); ?>">Подробнее</a>
     </p>
    </div>
   </div>  
</div>
<?php endforeach;?>
<?=$pagination?>