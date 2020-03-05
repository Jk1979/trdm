<script type='text/javascript'>
      price_from = <?=$price_from?>;
      price_to = <?=$price_to?>;
      current_price_from = <?php if(isset($current_price_from))  echo $current_price_from;  else echo $price_from?>;
      current_price_to = <?php if(isset($current_price_to))  echo $current_price_to;  else echo $price_to?>;
</script>
<?=html::script('public/js/double-slider.js')?>
<div class="fl-range">
        <div class="fl-title">Ценовой диапазон:</div>
        от: <input class="minCost idinp" type="text" id="minCost" value="<?=$price_from?>"/>
        до: <input class="maxCost idinp" type="text" id="maxCost" value="<?=$price_to?>"/>
        <div class="double-slider"><div id="slider"></div></div>
</div>
  