<?php if($article): ?>
<div class="row">
    <div class="col-md-12  col-sm-12 article-content">

        <h1><?=$article['title']; ?></h1>
        <div>
            <i>Дата публикации: <?php echo $article['date']; ?></i>
        </div>
        <?php if($article['image']):?>
        <img class="article-image" src="<?=URL::site()."public/uploads/imgarticle/".$article['image']?>" alt="<?=$article['title']?>" alt="">
        <?php endif; ?>

        <p><?php echo $article['content_full']; ?></p>

    </div>
</div>
    <!-- START COMMENTS -->
    <div id="comments">
        <h3 id="comments-title">
            <span>Всего комментариев: <?=$countcomments?></span>
        </h3>
        <?php if(count($comments) > 0):?>

        <ol class="commentlist group">
            <?php foreach ($comments as $k => $coms):?>
            <?php if($k !== 0): break; endif?>
            <?=View::factory('boot/index/articles/one_comment',array('items'=>$coms,'article'=>$article, 'comments'=>$comments))->render();?>
            <?php endforeach;?>

        </ol>
        <?php endif; ?>

        <div id="respond">
            <h3 id="reply-title">Оставьте <span>комментарий</span> <small><a rel="nofollow" id="cancel-comment-reply-link" href="#respond" style="display:none;">Отменить</a></small></h3>
            <form action="/ajax/createnewcomment" method="post" id="commentform">
                <input id="comment_post_ID" type="hidden" name="comment_post_ID" value="<?=$article['id']?>"/>
                <input id="comment_parent" type="hidden" name="comment_parent" value="0"/>
                <textarea id="scomment" style="display: none;" name="scomment"></textarea>
                <p class="comment-form-author"><label for="name">Имя</label> <input id="name" name="name" type="text" value="" size="30" aria-required="true" /></p>
                <p class="comment-form-email"><label for="email">Email</label> <input id="email" name="email" type="text" value="" size="30" aria-required="true" /></p>
                <p class="comment-form-comment"><label for="text">Ваш комментарий</label><textarea id="text" name="message" cols="45" rows="8"></textarea></p>
                <div class="clear"></div>
                <p class="form-submit">
                    <input name="submit" type="submit" id="submit" value="Отправить" />
                </p>
            </form>
        </div>
        <!-- #respond -->
    </div>
    <!-- END COMMENTS -->

    <div class="wrap_result"></div>

<?php else: ?>
    <div style="padding:10px; margin-bottom:10px;">
		Статья не найдена или не существует
    </div>
<?php endif; ?>