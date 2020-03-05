 <?php if(count($flbrands)): ?>
 <div class="filter">	
    <div class="fl-set">
          <div class="fl-title">Производители:</div>
          <ul style="overflow-y: scroll;">
             <?php foreach($flbrands as $k => $v):?>
              <li>
                  <label>
                    <input type="checkbox" onclick="window.location=&quot;/<?php echo $v["path"] . "&quot;"?>"
                           <?php if($v["check"]):?>  checked="checked" <?php endif;?>/>
                    <?=$v["title"]?>
                  </label>
              </li>
             <?php endforeach;?>  
             
        </ul>
    </div>
</div>
<?php endif;?>                                           
 
