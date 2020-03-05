<div class="filter">
 <p>   
  ФИЛЬТРЫ надо доработать !!!
 </p>
 
 <?php if($filters):?>
 <?php foreach ($filters as $f => $v):?>
     <?=$v['title'] . "<br/>";
         foreach($v['options'] as $op)
         {
             echo '--' . $op->option_title . "<br/>";
         }
     ?>
<?php endforeach;?>
 <?php endif;?>
</div>
