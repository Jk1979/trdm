<a href="#" onclick="window.location=&quot;/<?php echo $clearpath . "&quot;"?>;return false;" class="hidden-xs">Сбросить фильтр</a>
<?php if(isset($catlist) && !empty($catlist) && !empty($brand_path)): ?>
<div class="filter">	
    <div class="fl-set">
          <div class="fl-title">Категории:</div>
        <ul>
             <?php foreach($catlist as $c):?>
              <li>
                  <label>
                    <a href="/cat/<?=$c['path'] . '-' . $brand_path;?>"><?=$c['title']?></a>
                  </label>
              </li>
             <?php endforeach;?>  
             
        </ul>
    </div>
</div>
<?php endif;?>  
<?php if(count($flbrands)): ?>
 <div class="filter">	
    <div class="fl-set">
          <div class="fl-title">Производители:</div>
        <ul style="overflow-y: scroll;">
             <?php foreach($flbrands as $k => $v):?>
              <li>
                  <label>
                    <input type="checkbox" class="filtercheckbox" onclick="window.location=&quot;/<?php echo $v["path"] . "&quot;"?>"
                           <?php if($v["check"]):?>  checked="checked" <?php endif;?>/>
                    <span class="filtercheckbox_title"><?=$v["title"]?></span>
                  </label>
              </li>
             <?php endforeach;?>  
             
        </ul>
    </div>
</div>
<?php endif;?>                                           
 <?php if(isset($filters) && !empty($filters)): ?>
 <div class="showfilter hidden-sm hidden-lg hidden-md">Показать фильтр</div>
 <div class="filter hidden-xs">
        <?php foreach($filters as $filter):?> 
         <div class="fl-set">
          <div class="fl-title"><?=$filter["title"]?></div>
            <?php if(count($filter["options"])>0):?>
            <ul <?php if(count($filter["options"])>7) echo 'style="overflow-y: scroll;max-height:300px;"';?>>
                 <?php foreach($filter["options"] as $k => $v):?>
                  <li>
                      <label>
                        <input type="checkbox" data-opid="<?=$v["opid"]?>" class="filtercheckbox optid" onclick="window.location=&quot;/<?php echo $v["path"] . "&quot;"?>"
                               <?php if($v["check"]):?>  checked="checked" <?php endif;?>/>
                        <span class="filtercheckbox_title"><?=$v["title"]?></span>
                      </label>
                  </li>
                 <?php endforeach;?>  
            </ul>
            </div>
            <?php endif;?>
        <?php endforeach;?>
    
</div>
<?php endif;?>
<?php if(isset($attributes) && !empty($attributes)): ?>   
<div class="filter hidden-xs">
  <?php foreach($attributes as $k => $v):?>
     <div class="fl-set">
          <div class="fl-title"><?=$k?></div>
            <ul <?php if(count($attributes[$k])>7) echo 'style="overflow-y: scroll;max-height:300px;"';?>>
                 <?php foreach($attributes[$k] as $value):?>
                  <li>
                      <label>
                        <input id="<?=$value["id"]?>" type="checkbox" class="filtercheckbox attrvalue" onclick="window.location=&quot;/<?php echo $value["path"] . "&quot;"?>"
                               <?php if($value["check"]):?>  checked="checked" <?php endif;?>/>
                        <span class="filtercheckbox_title"><?=$value["title"]?></span>
                      </label>
                  </li>
                 <?php endforeach;?>  
            </ul>
            </div>
    <?php endforeach;?> 
</div>
<?php endif;?>
<div>
<a href="#" onclick="window.location=&quot;/<?php echo $clearpath . "&quot;"?>;return false;" class="hidden-xs">Сбросить фильтр</a>
<span id="catid" style="display: none;"><?=$cat?></span>
</div>