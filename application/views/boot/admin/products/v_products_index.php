<script>
function setcat(value)
{
    var newval = '/admin/products/index/' + value;
   $('#newhref').attr('href', newval); 
}
</script>
<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/products/add', 'Добавить')?>
</p>
<div>
    <form action="" name="search" method="post">
        <div class="parent_block">
            <input type="text" id="editProd" name = "query" value="" width="25"/>
            <input type="submit" name="sub" id="brandsearch"  value="Поиск"> 
            Выбрать категорию: 
            <?=Form::select('categories',$cats,$selected, array('id'=>'category','onchange'=>'setcat(this.value);') )?> <?=HTML::anchor('admin/products/', 'перейти',array('id'=>'newhref'))?>
            <div class="toggled_block"><ul id="listProds"> </ul></div>
        </div>
        
    </form>
</div>
<table width="100%" border="0" class="tbl"  cellspacing="0">
    <thead>
        <tr height="30">
            <th>№</th><th>Код товара</th><th>Название</th><th>Цена</th><th>Изображения</th><th>Фильтры</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th><th>&nbsp;&nbsp;</th>
        </tr>
    </thead>
<?php $n=0;?>    
<?php foreach ($products as $prod):?>
    
<tr class="tr-prods">
<td  width="15" style = "padding-left:20px;text-align:right;"><?=++$n?></td>
<td  width="100" style = "padding-left:20px;"><?=$prod->code;?></td>
<td ><?=HTML::anchor('admin/products/edit/'. $prod->prod_id, $prod->title)?></td>
<td><?=Form::input('price', $prod->price, array('class'=>'prodindex_price','size' => 8, 'data-id'=>$prod->prod_id))?></td>
<td><a href="#text-popup" class="popup-content" data-id="<?=$prod->prod_id?>">Загрузить картинки</a></td>
<td><a href="#filter-popup" class="popup-filter" data-id="<?=$prod->prod_id?>" data-brand="<?=$prod->brand->title?>" data-img="<?='/public/uploads/imgproducts/' . $prod->main_image->name?>" data-catid="<?=$prod->cat_id?>" data-prodtitle="<?=$prod->code . ' - ' . $prod->title?>">Фильтры</a></td>
<td width="50" align="center">
<?=HTML::anchor('admin/products/copy/'. $prod->prod_id, HTML::image('public/img/copy.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/products/edit/'. $prod->prod_id, HTML::image('public/img/edit.png'))?>
</td>
<td width="50" align="center">
<?=HTML::anchor('admin/products/delete/'. $prod->prod_id, HTML::image('public/img/delete.png'),
            array("onclick"=>"return clickHandler();"))?>
</td>
</tr>
<?php endforeach?>

</table>

<br/>
<p>
<?=HTML::image('public/img/add.png', array('valign' => 'top'))?>
    
<?=HTML::anchor('admin/products/add', 'Добавить')?>
</p>
<p> <?=$pagination; ?> </p>
<div id="text-popup" class="white-popup mfp-hide" style="min-height: 300px;">
    <form action="">
            <input id="product_id" type="hidden" value=""/>    
            <input type="file" id="files" name="files[]" multiple />
            <input type="hidden" value="" name="selectImage" id="selectedFile">
	    <input type="hidden" value="" name="anotherImages" id="anotherImages"/>
            <input type="hidden" value="1" id="maxFiles">
            <input type="hidden" value="imgproducts" id="imagedir">
            <a id="upserver" href="#" onclick="return false;">Загрузить файл с сервера</a>
            <div>
                <input type="text" value="" name="customimage" id="customimg"/><- название файла картинки на сервере
                <div class="togg_block"><ul id="listimgs" style="list-style-type: none;cursor: pointer;"> </ul></div>
            </div>
            <span id="saveimgservok" style="margin-left: 10px;font-size: 18px;color: green;"></span>
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
                   <a href="#" id="saveprodimg" style="font-size: 18px;color:red;">Сохранить изменения в товаре</a><span id="saveimgok" style="margin-left: 10px;font-size: 18px;color: green;"></span>
                  <p>Загруженные файлы</p>
               </ul>
            </div>
    </form>        
</div>
<div id="filter-popup" class="white-popup mfp-hide" style="min-height: 300px;">
    <div style="margin: 10px 20px"><span id="prodtitle" ></span><span style="margin-left: 20px" id="brand" ></span></div>
    <div id="prodimg" style="margin: 10px 20px"></div>
    <form action="">
            <input id="prod_id" type="hidden" value=""/> 
            <input id="categoryid" type="hidden" value="<?=$selected?>"/>
            <input id="subfilter" type="button" value="Сохранить"/> <span id="savefilterok" style="margin-left: 10px;font-size: 18px;color: green;"></span>
            <div id="filterOptions"></div>
            
    </form>        
</div>
