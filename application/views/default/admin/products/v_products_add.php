<script>
  $(function() {
    $( "#tabs" ).tabs();
  });
  </script>
<div id="tabs">
<?=Form::open('admin/products/add', array('enctype' => 'multipart/form-data'))?>
  <ul>
    <li><a href="#tabs-1">Общее о товаре</a></li>
    <li><a href="#tabs-2">Опции фильтров</a></li>
  </ul>
  <div id="tabs-1">
<br/>
<?php if(isset($errors)): ?>
<?php foreach($errors as $e => $error):?>
<div class="error">
    Поле&nbsp;<?=$error?>
</div>
<?php endforeach; ?>
<?php endif; ?>

<table width="100%" cellspacing="10">
    <tr>
        <td ><?=Form::label('title', 'Название')?>:</td>
        <td>
            <?php //Form::input('title', $data['title'], array('size' => 100))?>
            <input type="text" name="title" 
                id="ctproduct_title" value="<?=$data['title']?>" size="100" 
                onkeyup="$('#ctproduct_path').val(genpath($(this).val()));">
        </td>
    </tr>
    <tr>
        <td ><?=Form::label('path', 'Адрес')?>:</td>
        <td><?=Form::input('path', $data['path'], array('size' => 100, 'id'=>'ctproduct_path'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('code', 'Код товара')?>:</td>
        <td><?=Form::input('code', $data['code'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('present', 'Товар есть в наличии?')?>:</td>
        <td><?=Form::checkbox('present', 1, TRUE)?></td>
    </tr>
    <tr>
        <td ><?=Form::label('status', 'Активен?')?>:</td>
        <td><?=Form::checkbox('status', 1, TRUE)?></td>
    </tr>
    <tr>
        <td ><?=Form::label('top', 'Лучший товар?')?>:</td>
        <td><?=Form::checkbox('top', 0, FALSE)?></td>
    </tr>
    <tr>
        <td ><?=Form::label('price', 'Цена')?>:</td>
        <td><?=Form::input('price', $data['price'], array('size' => 100))?></td>
    </tr>
     <tr>
        <td ><?=Form::label('categories', 'Категория')?>:</td>
        <td><?=Form::select('categories[]', 
                                $cats,
                                $data['categories'], array('multiple'=>"multiple",'id'=>'category',
											'onchange'=>'getfilteroptions(this.value);') )?>
        </td>
    </tr>
    <tr>
        <td ><?=Form::label('brand_id', 'Производитель')?>:</td>
        <td><?=Form::select('brand_id', $brands,$data['brand_id'],array('id'=>'ctbrand_id',
            'onchange'=>'GetCtsubbrands(this.value);', 'style'=>'width:200px;'))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('series_id', 'Серия')?>:</td>
        <td><?=Form::select('series_id', $series,$data['series_id'],array('id'=>'ctseries_id', 'style'=>'width:200px;'))?></td>
    </tr>
    <tr>
        <td valign="top"><?=Form::label('preview', 'Краткое описание')?>: </td>
        <td><?=Form::textarea('preview', $data['preview'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td>  <?=Form::label('image', 'Загрузить изображения')?>: </td>
        <td>
            <input type="file" id="files" name="files[]" multiple />
            <input type="hidden" value="" name="selectImage" id="selectedFile">
			<input type="hidden" value="" name="anotherImages" id="anotherImages"/>
            <input type="hidden" value="5" id="maxFiles">
            <input type="hidden" value="imgproducts" id="imagedir">
            <a id="uploadServer" href="#" onclick="return false;">Загрузить с сервера </a>
            <div id="selectedImageView"></div>
            <div id="dropped-files">
            <output id="list"></output>
            <br/>
                <div id="upload-button">
                      <span>0 Файлов</span>
                   <a href="#" class="upload">Загрузить</a>
                   <a href="#" class="delete">Удалить</a>
               </div>
            </div>  
            <!-- Список загруженных файлов -->
            <div id="file-name-holder">
               <ul id="uploaded-files">
                  <p>Загруженные файлы</p>
               </ul>
            </div>
        </td>
        
    </tr>  
    <tr>
        <td valign="top"><?=Form::label('content', 'Описание')?>: </td>
        <td><?=Form::textarea('content', $data['content'], array('cols' => 100, 'rows' => 20))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_title', 'Заголовок страницы, title')?>:</td>
        <td><?=Form::input('meta_title', $data['meta_title'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_keywords', 'Заголовок страницы, keywords')?>:</td>
        <td><?=Form::input('meta_keywords', $data['meta_keywords'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td ><?=Form::label('meta_description', 'Заголовок страницы, description')?>:</td>
        <td><?=Form::input('meta_description', $data['meta_description'], array('size' => 100))?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?=Form::submit('add', 'Добавить')?></td>
    </tr>

</table>

</div>
  <div id="tabs-2">
    <div id="filterOptions"><div>
  </div>

<?=Form::close()?>
</div>





