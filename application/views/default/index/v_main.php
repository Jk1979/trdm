<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $main_title . $page_title; ?></title>
<meta name="description" content="<?php echo $description; ?>" />
<?php foreach($styles as $style): ?>
    <link href="<?php echo URL::base(); ?>public/css/<?php echo $style; ?>.css" 
    rel="stylesheet" type="text/css" />
<?php endforeach; ?>
    <?php if(isset($scripts) && count($scripts)>0):?>
        <?php foreach ($scripts as $script):?>
        <?=html::script($script)?>
    <?php endforeach;?>
    <?php endif;?>
    <script type='text/javascript'>
      price_from = 1;
      price_to = 1000000;
      current_price_from = 1;
      current_price_to = 1000000;
    </script>
<script language="JavaScript" type="text/javascript">
<!--
client_cart_currency_grn = 'грн';
client_cart_e01 = 'Внимание, корзина работает неправильно!';
client_cart_e02 = 'Товар не удалось добавить в корзину!';
client_cart_e03 = 'Товар не удалось удалить из корзины!';
client_cart_e04 = 'Ошибка при пересчете!';
client_cart_things = 'шт';
client_cart_feedback_done = 'Ваш заказ принят и ожидает модерации!';
-->
</script>
<script>
    $(document).ready(function () {

  $('.vert-nav').hover(
    function() {
      $('ul.menu_vert', this).slideDown(110);
    },
    function() {
      $('ul.menu_vert', this).slideUp(110);
    }
  );

  });
$(function(){
	$('.menu_vert').liMenuVert({
		delayShow:300,		//Задержка перед появлением выпадающего меню (ms)
		delayHide:300	    //Задержка перед исчезанием выпадающего меню (ms)
	});
});


      
</script> 
</head>
 
<body>
    <div id="container">
        <div id="top_menu"><?=$topmenu?></div>
        <div class="header">
            <div class="shoptitle">
                <h1><?=$sitename?></h1>
                <p><?=$description?></p>
            </div>
            <div>
                <?=$search?>
            </div>
			<div class="head-phones">
				(096) <span class="head-ph-numb">466-99-47</span><br>
				(066) <span class="head-ph-numb">328-75-09</span><br>
				(093) <span class="head-ph-numb">679-03-20</span><br>
		    </div>
            <div class="cart">
                <?=$cart?>
            </div>
           <div class="cl"></div> 
        </div>
        <?php if(isset($left_block)):?>
            <div class="leftblock">
                <?php foreach ($left_block as $block):?>    
                <div class="l_block"><?=$block?></div>
                <?php endforeach; ?>
                <?php if(isset($filter)):?>
                <?=$filter?>
                <?php endif;?>
            </div>
        <?php endif; ?>    
        <?php if(isset($right_block)):?>
            <div class="rightblock">
                <?php foreach ($right_block as $block):?>    
                <div class="r_block"><?=$block?></div>
                <?php endforeach; ?>    
            </div>
        <?php endif; ?>    
        <?php if(isset($center_block)):?>
            <div class="content">
                <?php if(isset($breadcrumbs)) echo $breadcrumbs;?>
                <?php if(isset($page_caption)):?>
                    <h2 class="page_title"><?=$page_caption?></h2>
                 <?php endif; ?>  
                    <?php foreach ($center_block as $block):?>    
                        <div class="c_block"><?=$block?></div>
                    <?php endforeach; ?>  
            </div>
        <?php endif; ?>
        <div class="clearing"></div>
         </div>
	<div class="footer">2014 Brovtsin</div>
</body>
</html>