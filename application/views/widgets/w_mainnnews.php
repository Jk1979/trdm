<div class="row">
   
    <div class="box-heading">
        <h6>Новости</h6>
    </div>
    
    <?php foreach($news as $new):?>
    <div class="col-md-6">
    <p class="onenewtitle"><?=HTML::anchor('news/' . $new->path, $new->title)?></p>
    <p><?=Text::limit_words($new->content,30)?></p>
    </div>
    <?php endforeach?>
</div>


