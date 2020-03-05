
<?php if(isset($articles) && count($articles) > 0):?>
    <div class="container-fluid">
        <div class="row">
            <div class="box-heading">
                <h6>Статьи и обзоры</h6>
            </div>
            <div class="owl-carousel owl-article">
                <?php foreach($articles as $article):?>
                    <div class="slide-article">
                        <div class="slide_item">
                            <div class="slide-image">
                            <img src="<?=URL::site()."public/uploads/imgarticle_small/small_".$article->image?>" alt="<?=$article->title?>" width="80%" >
                            </div>
                            <h3 class="slide-title"><?=$article->title?></h3>
                            <?=html::anchor("articles/$article->path", "Читать",array('class'=>'button'))?>
                        </div>
                    <div class="slide-article-desc"><?=Text::limit_words(strip_tags($article->content_short), 40);?></div>
                    </div>
                    <!--<div class="slide-article-desc">
                        <p></p>
                    </div>-->

                <?php endforeach?>

            </div>
        </div>
    </div>
<?php endif;?>
