<?php if($article): ?>
    <div style="padding:10px; margin-bottom:10px; border-bottom:#333 2px solid;">
        <strong><?php echo $article['title']; ?></strong><br />
        <i>Автор: <?php echo $article['author']; ?></i> / 
        <i>Дата публикации: <?php echo $article['date']; ?></i><br /><br />
        <p><?php echo $article['content_full']; ?></p>
    </div>    
    <?php echo $comments; ?>
<?php else: ?>
    <div style="padding:10px; margin-bottom:10px;">
		Статья не найдена или не существует
    </div>
<?php endif; ?>