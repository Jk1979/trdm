

<?php foreach($items as $item):?>
<li id="li-comment-<?=$item['id']?>" class="comment even">
    <div id="comment-<?=$item['id']?>" class="comment-container">
        <div class="comment-author vcard">
            <?php $hash =  isset($item['email']) ? md5($item['email']) : ''; ?>
            <img alt="" src="https://www.gravatar.com/avatar/<?=$hash?>?d=mm&75" class="avatar" height="75" width="75" />
            <cite class="fn"><?=$item['name']?></cite>
        </div>

        <div class="comment-meta commentmetadata">
            <div class="intro">
                <div class="commentDate">
                    <a href="#comment-2">
                        <?=isset($item['created_at']) ? $item['created_at'] : '';?></a>
                </div>
                <div class="commentNumber">#&nbsp;</div>
            </div>
            <div class="comment-body">
                <p><?=$item['message']?></p>
            </div>
            <div class="reply group">
                <a class="comment-reply-link" href="#respond" onclick="return addComment.moveForm(&quot;comment-<?=$item['id']?>&quot;, &quot;<?=$item['id']?>&quot;, &quot;respond&quot;, &quot;<?=$item['article_id']?>&quot;)">Ответить</a>
            </div>
            <!-- .reply -->
        </div>
        <!-- .comment-meta .commentmetadata -->
    </div>
    <!-- #comment-##  -->
    <?php if(isset($comments[$item['id']])):?>
    <ul class="children">
        <?= View::factory('boot/index/articles/one_comment',array('items'=>$comments[$item['id']],'article'=>$article, 'comments'=>$comments))->render()?>

    </ul>
    <?php endif; ?>
</li>
<?php endforeach;?>

