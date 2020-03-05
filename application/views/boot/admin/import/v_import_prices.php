<script>

</script>
  <form action="" id="importfrm" method="post">
    <div>
              Плитка <input id="type" type = "radio" name ="typeGoods" value = "plitka" checked /><br />
              Сантехника <input id="type" type = "radio" name ="typeGoods" value = "santehnika"  /><br />
      </div>
  <div class="fileloadbox">
    <input type="hidden" name="directory" value="import" id="importdir">
    <input type="hidden" value="" name="selectFile" id="selectedFile">
    <input class="submit" type="submit" name="createcsv" value="Создать agrom_tolkoplitka.csv"/>
    <input class="submit" type="button" id="cleantable" name="cleantable" value="Очистить таблицу розничных цен"/><br/>
    <input class="submit" type="submit" name="stock" value="Обновить данные по остаткам"/><br/>
    <div style="margin: 20px 0 20px 0">
    <span>Выберите файл для загрузки(agrom_tolkoplitka.csv): </span><a href="#" id="uploadsrv">Выберите</a>|<a href="#">Удалить</a>
    <span id=fileimport></span>
    <input class="submit" type="submit" value="Загрузить таблицу с ценами" name="uploadtable" id="files" class="importbut" disabled/>
    <input class="submit" type="submit" value="Обновить таблицу dataagromat" name="dataagromat" id="dataagr" class="importbut" disabled/>
    <input class="submit" type="submit" name="importattributes" value="Загрузить атрибуты" title="Загружает атрибуты или устанавливает опции по csv файлу"/><input type="checkbox" name="options" style="margin-left: 20px; "/> <label for="options">Опции</label>
    <input class="submit" type="submit" name="filters" value="Загрузить фильтры по jk_prodattributes"/>
    <input class="submit" type="submit" name="agromatbase" value="Загрузить данные в таблицу agromat из файла" title="выберите agrom_tolkopltika.csv"/> <br/>
     <div style="display: flex; flex-wrap: wrap; width: 800px;">
         <div style="max-width:700px;display: flex; margin: 25px auto;justify-content: space-between;">
            <div><span>ID Категории: </span><input type="text" name="catid" value=""/></div>
            <div><span>Колонка в таблице: </span><input type="text" name="column" value=""/></div>
            <div><span>Название фильтра:</span> <input type="text" name="filtername" value=""/></div>

        </div>

        <div style="display: flex; align-items:center;">
            <label for="filternames">Названия фильтров с маленькой буквы через запятую:  </label>
            <textarea name="filternames"  if="filternames" cols="30" rows="10"></textarea>
        </div>
         <div>
             <input class="submit" type="submit" name="importoptionsbycsv" value="Загрузить товары с картинками и фильтры по csv" title="загружает товары и фильтры и опции по csv файлу, нужно указать id категории и в папку uploads/tmp записать изображения товаров"/> <br/>
             <br/>
             <input class="submit" type="submit" name="addimagesbycatid" value="Загрузить и установить картинки по category ID из tmp с записью в tmpres"/>
             <br/>
             <input class="submit" type="submit" name="addimagesfromtmp" value="Загрузить и установить картинки из tmp"/> <input type="checkbox" name="setmainimg" style="margin-left: 20px; "/> <label for="options">Установить как главные</label>
             <br/>
             <input class="submit" type="submit" name="smallImages" value="Создать маленькие картинки"/>
             <br/>
         </div>
     </div>
        <hr/>
      <br/><input class="submit" type="submit" name="setfilteroptions_by_request" disabled="disabled" value="Установить опции фильтров по указанному в коде запросу"/> <br/>
      <br/><input class="submit" type="submit" name="addoptionsbyarray" value="Загрузка новых товаров по кодам из массива и опций фильтров к ним" /> <br/>
    </div>
    </div> 
    <div style="margin-bottom:20px;">
    <br/><input type="radio" name="plsan" value="1" checked>Плитка
    <br/><input type="radio" name="plsan" value="2" >Сантехника 
    </div>
    <hr style="margin:10px";/>
   
    <?php if(isset($brands)): ?>
    <p class="importbrands">
        <select size="20"  name="brands[]" id = "brands" multiple>
        <? foreach($brands as $k => $brand):?> 
        <option value="<?=$k?>"><?=$brand?></option>
       <?endforeach?>
       </select>
   </p>
   <?php endif; ?>
   <span class="importbrand">Коэффициент скидки: </span><input type="text" name="koef" id="koef"/>
   <input class="submit" type="submit" name="change" value="Поменять цены"/>
    <div style="margin: 20px 0">
        <input class="submit" type="submit" name="changeasfullhouse" value="Поменять цены по full house" style="vertical-align: top;margin-top: 10px;"/>
        Коэфициент разницы от прайса: <input type="text" name="diffkoef">
    </div>
      <div style="margin: 20px 0">
          <input class="submit" type="submit" name="changelastschema" value="Поменять цены по схеме 12 18 22" />
          <input class="submit" type="submit" name="changemyschema" value="Поменять цены по схеме 18 20 24" />
      </div>
    <div><input class="submit" type="submit" name="changesantehnika" value="Поменять цены по сантехнике" style="vertical-align: top;margin-top: 10px;"/></div>
    <span class="importbrand">Схема: </span>
    <input style="width:300px;" type="text" name="schema" id="schema"/><input type="checkbox" name="decor" style="margin-left: 20px; "/> <label for="decor">Только на декоры</label>
    <div><input class="submit" type="submit" name="changebypreset" value="Поменять цены по заданным схемам" style="vertical-align: top;margin-top: 10px;" />
        <select name="discountSelect"  style="margin-top: 10px;" disabled="disabled">
            <option value="0">5%</option>
            <option value="1">22%</option>
            <option value="2">23%</option>
            <option value="3">24%</option>
            <option value="4">25%</option>
            <option value="5">26%</option>
            <option value="6">27%</option>
            <option value="7">28%</option>
            <option value="8">30%</option>
            <option value="9">31%</option>
            <option value="10">32%</option>
            <option value="11">33%</option>
            <option value="12">34%</option>
          </select>
    </div>
    <br/>
  <input class="submit" type="submit" name="runconsole" value="Выполнить"/>
  <input class="submit" type="submit" name="getxbc" value="Выбрать хбс коды и записать в файл"/>
  <input class="submit" type="submit" name="tablagromat" value="Загрузить таблицу agromat из agrom_tolkoplitka"/>
  <input class="submit" type="submit" name="defaultprices" value="Поставить нулевые цены и скидки по ограничениям"/>

    
</form>
<?php if(isset($log) && is_array($log)): ?>
<?php foreach($log as $l):?>
<div class="log">
    <span><?=$l;?></span>
</div>
<?php endforeach; ?>
<?php elseif(isset($log)): echo $log; ?>

<?php endif; ?> 
