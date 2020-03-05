
  <form action="" id="updatefrm" method="post">
    <div>
              Плитка <input id="type" type = "radio" name ="typeGoods" value = "plitka" checked /><br />
              Сантехника <input id="type" type = "radio" name ="typeGoods" value = "santehnika"  /><br />
      </div>
    <div class="fileloadbox">
    <input type="hidden" name="directory" value="import" id="importdir">
    <input type="hidden" value="" name="selectFile" id="selectedFile">
     </div>

    <?php if(isset($brands)): ?>
    <p class="importbrands">
        <select size="20"  name="brands[]" id = "brands" multiple>
        <? foreach($brands as $k => $brand):?>
        <option value="<?=$k?>"><?=$brand?></option>
       <?endforeach?>
       </select>
   </p>
   <?php endif; ?>
    <input class="submit" type="submit" name="updateprodattributes" value="Обновить таблицу jk_prodattributes по agromat"/>
    <input class="submit" type="submit" name="upd" value="Обновить"/>
  </form>

  <?php if(isset($log) && is_array($log)): ?>
      <?php foreach($log as $l):?>
          <?php if(is_array($l)) { print_r($l); continue; } ?>
          <div class="log">
              <span><?=$l;?></span>
          </div>
      <?php endforeach; ?>
  <?php elseif(isset($log)): echo $log; ?>

  <?php endif; ?>

