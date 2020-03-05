<?=Form::open('admin/saninstall/pageinfo')?>
<?php if(isset($data['page_id'])):?>
    <input type="hidden" name="id" value="<?=$data['page_id']?>">
<?php endif; ?>
<?php if(isset($errors)): ?>
    <?php foreach($errors as $e => $error):?>
        <div class="error">
            Поле&nbsp;<?=$error?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
    <div class="form-group">
       <label for="title">Заголовок страницы</label>
        <input type="text" style="width: 600px" class="form-control" name="title" value="<?=$data['title']?>">
    </div>
    <div class="form-group">
        <label for="text">Содержание</label>
        <textarea name="text" id="" cols="100" rows="20">
            <?=$data['text']?>
        </textarea>
    </div>
    <div class="form-group">
        <label for="meta_title">Заголовок страницы meta title</label>
        <input type="text" style="width: 600px" class="form-control" name="meta_title" value="<?=$data['meta_title']?>">
    </div>
    <div class="form-group">
        <label for="meta_keywords">Мета тег страницы meta keywords</label>
        <input type="text" style="width: 600px" class="form-control" name="meta_keywords" value="<?=$data['meta_keywords']?>">
    </div>
    <div class="form-group">
        <label for="meta_keywords">Мета тег страницы meta description</label>
        <input type="text" style="width: 600px" class="form-control" name="meta_description" value="<?=$data['meta_description']?>">
    </div>
    <input type="hidden" name="alias" value="installpage">
    <input type="hidden" name="status" value="0">
    <button type="submit" name="save" class="btn btn-default">Сохранить</button>
<?=Form::close()?>