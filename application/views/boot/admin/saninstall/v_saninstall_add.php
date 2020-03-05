<?=Form::open('admin/saninstall/add')?>
<?php if(isset($errors)): ?>
    <?php foreach($errors as $e => $error):?>
        <div class="error">
            Поле&nbsp;<?=$error?>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
    <div class="form-group">
       <label for="title">Наименование</label>
        <input type="text" style="width: 600px" class="form-control" name="title" value="<?=$data['title']?>">
    </div>
    <div class="form-group">
        <label for="price">Цена:</label>
        <input type="text" style="width: 150px" class="form-control" name="price" value="<?=$data['price']?>">
    </div>
    <div class="form-group">
       <label for="unit">Единица измерения</label>
       <input type="text" style="width: 150px" class="form-control" name="unit" value="<?=$data['unit']?>">
    </div>

    <button type="submit" name="add" class="btn btn-default">Добавить</button>
<?=Form::close()?>