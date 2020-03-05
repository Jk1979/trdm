<div class="row">
    <div class="col-md-12  col-sm-12">
        <h1 class="page_title">Цены на сантехнические работы</h1>
    </div>
    </div>
<div class="row">
    <div class="col-sm-12 col-md-8">

    <?=Form::open('saninstall/userform',array('class'=>'form-horizontal'))?>

    <?php if(isset($errors)): ?>
        <?php foreach($errors as $e => $error):?>
            <div class="error">
                Поле&nbsp;<?=$error?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="form-group">
        <label for="price" class="col-sm-4 control-label">Наименование</label>
        <div class="col-sm-3">
        <label for="price" class="control-label">Цена, грн</label>
        </div>
    </div>

    <?php foreach($prices as $price):?>
    <div class="form-group">
        <label for="price" class="col-sm-4 control-label"><?=$price->title?></label>
        <div class="col-sm-2">
            <input type="hidden" name="id[]" value="<?=$price->id?>">
            <input type="number" min="1" class="form-control" name="price_<?=$price->id?>">
        </div>
    </div>


    <?php endforeach; ?>
        <div class="form-group">
            <label for="price" class="col-sm-12 col-md-4 control-label">Ваше имя</label>
            <div class="col-sm-12 col-md-8">
                <input type="hidden" name="id[]"   title ="Цена, грн" value="<?=$price->id?>">
                <input type="text"  class="form-control" name="fio">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
    <button type="submit" name="send" class="btn btn-success margin-bottom margin-top">Отправить</button>
            </div>
        </div>
    <?=Form::close()?>
    </div>



</div>
