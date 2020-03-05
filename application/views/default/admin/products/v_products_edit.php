<script>
  $(function() {
    $( "#tabs" ).tabs();
	var cat = $("#selectCats option:selected").val();
	var prod = $("#prodId").val();
	if(cat>1) {
		getfilteroptionsEdit(cat, prod);
	}
  });
  </script>
   
<div id="tabs">
<?=Form::open('admin/products/edit/' . $id, array('enctype' => 'multipart/form-data'))?>
<input type="hidden" id="prodId" name="prodId" value="<?=$id;?>"/>
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
        <td><?=Form::checkbox('present', 1, (bool) $data['present'])?></td>
    </tr>
    <tr>
        <td ><?=Form::label('status', 'Активен?')?>:</td>
        <td><?=Form::checkbox('status', 1, (bool) $data['status'])?></td>
    </tr>
    <tr>
        <td ><?=Form::label('top', 'Лучший товар?')?>:</td>
        <td><?=Form::checkbox('top', 1,  (bool) $data['top'])?></td>
    </tr>
    <tr>
        <td ><?=Form::label('price', 'Цена')?>:</td>
        <td><?=Form::input('price', $data['price'], array('size' => 100))?></td>
    </tr>
     <tr>
        <td ><?=Form::label('categories', 'Категория')?>:</td>
        <td><?=Form::select('categories[]', 
                                $cats,
                                $data['categories'], array('multiple'=>"multiple",'id'=>'selectCats',
								'onchange'=>' if(confirm("При смене категории пропадут настройки фильтров!")){getfilteroptions(this.value); return true;} else return false;') )?></td>
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
        <td colspan="2" align="center"><?=Form::submit('save', 'Сохранить')?></td>
    </tr>
	<tr >
        <td colspan="2">
            <?=Form::label('images', 'Изображения')?>: <br/><br/>
            <?php if (!empty($data['images'])):?>
                    <a name="img"></a>
                        <table width="100%" cellspacing="20" class="tbl_img_sml" >
                        <tr >
                        <?php foreach($data['images'] as $i =>  $image):?>
                            <td align="center">
                                <?=html::anchor('public/uploads/imgproducts/'. $image->name, html::image('public/uploads/imgproducts_small/small_' . $image->name), array('target' => '_blank','name'=> "image_str"))?>
                                <br><?=html::anchor('admin/products/delimage/' . $image->id, 'Удалить')?> 
                                <?php if ($data['main_img'] == $image->id):?>   
                                &nbsp;Главная
                                <?php else:?>
                                <?=html::anchor('admin/products/setmain/' . $image->id, 'Главная')?>
                                <?php endif?>    
                            </td>
                            <?php if ((($i+1) % 3)==0):?>
                        </tr>
                        <tr>
                            <?php endif?>
                        <?php endforeach?>
                        </tr>
            </table>
            <?php else:?>
            <div class="empty">Нет изображений</div>
            <?php endif?>
        </td>
    </tr>
</table>
</div>
  <div id="tabs-2">
    <div id="filterOptions"><div>
  </div>
 <?=Form::close()?>
</div>