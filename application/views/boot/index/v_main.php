<!DOCTYPE html>
<!--[if lt IE 7]><html lang="ru" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html lang="ru" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html lang="ru" class="lt-ie9"><![endif]-->
<!--[if gt IE 8]><!-->
<html lang="ru">
<!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo $page_title . $main_title; ?></title>
    <meta name="description" content="<?php echo $description; ?>" />
    <meta name="keywords" content="<?php echo $keywords; ?>"/>
    <meta property="og:title" content="<?php echo $page_title . $main_title; ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://trademag.com.ua/<?php echo $thisuri;?>">
    <meta property="og:image" content="https://trademag.com.ua/public/img/tradelogo_og.jpg">
    <meta property="og:site_name" content="Трейдмаг">
    <meta property="og:locale" content="ru_RU" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="robots" content="index, follow">
    <link href="https://trademag.com.ua<?php if($thisuri) echo '/'.$thisuri;?>" rel="canonical">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <?php foreach($styles as $style): ?>
    <link href="<?php echo URL::base(); ?>public/<?php echo $style; ?>.css" 
    rel="stylesheet" type="text/css" />
    <?php endforeach; ?>

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
    function addLink() {
    var body_element = document.getElementsByTagName('body')[0];
    var selection;
    selection = window.getSelection();
    var pagelink = "<br /><br /> Источник: <a href='"+document.location.href+"'>"+document.location.href+"</a><br />© trademag.com.ua"; 
    var copytext = selection + pagelink;
    var newdiv = document.createElement('div');
    newdiv.style.position='absolute';
    newdiv.style.left='-99999px';
    body_element.appendChild(newdiv);
    newdiv.innerHTML = copytext;
    selection.selectAllChildren(newdiv);
    window.setTimeout(function() {
        body_element.removeChild(newdiv);
    },0);
    }
    document.oncopy = addLink;
    var price_from = price_from || 1;
    var price_to = price_to || 100000;
    var current_price_from = current_price_from || 1;
    var current_price_to = current_price_to || 1;
</script>

    
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74371882-1', 'auto');
  ga('send', 'pageview');

</script>
    <?php if(Request::current()->controller() == 'Catalog'):?>
        <script>
            var catalogPage = true;
        </script>
     <?php else: ?>
        <script>
            var catalogPage = false;
        </script>
    <?php endif;?>
</head>
<body>
	<!--[if lt IE 9]>
	<script src="libs/html5shiv/es5-shim.min.js"></script>
	<script src="libs/html5shiv/html5shiv.min.js"></script>
	<script src="libs/html5shiv/html5shiv-printshiv.min.js"></script>
	<script src="libs/respond/respond.min.js"></script>
	<![endif]-->
	
	
	<!-- Google Analytics counter --><!-- /Google Analytics counter -->
	<?php  $container="container-fluid";$wd ="col-lg-12 col-md-12" ?>
	<?php if(isset($left_block)) {$wd ="col-lg-9 col-md-9"; $lbwd="col-lg-3 col-md-3"; $container = "container-fluid"; }?>
	<?php if(isset($right_block)) {$wd ="col-lg-8 col-md-8"; $lbwd="col-lg-3 col-md-3"; $container = "container-fluid"; }?>
   
<div class="header" id="header">
    <div class="header_topline">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <a href="viber://add?number=380677654006"><i class="fa fa-fw fa-phone-square"></i> viber 0677654006</a>
                        <a href="mailto:info.trademag@ukr.net"><i class="fa fa-fw fa-envelope-o"></i> info.trademag@ukr.net</a>
                    </div>
                    <div class="col-md-6 pull-right">
                            <?=$menulogin?>
                            <button id="topmnu" class="main_mnu_button hidden-md hidden-lg pull-right"><i class="fa fa-bars"></i></button>
                            <nav id="topnav" class="nav pull-right">
                                <?=$topmenu?>
                            </nav>
                    </div>
                </div>
            </div>
    </div>
    <div class="header_bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
                    <a href="/" class="logo"><img src="/public/img/tradelogo_1.png" alt=""></a>
                </div>
                <div class="col-lg-4 col-md-3 col-sm-12 col-xs-12 search">
                <p class="sitetitle hidden-xs hidden-sm ">Интернет магазин плитки и сантехники</p>
                <?=$search?>
                <div class="worktime hidden-sm hidden-xs hidden-md">
                    <span>Время работы: Пн - Сб</span><span class="worktime-sup">9<sup>00</sup>-18<sup>00</sup></span>
                </div>
                </div>
                <div class="col-lg-2 col-md-2 col-sm-12 col-xs-12  parent_headphones">
                    <div class="head-phones">
                        <div class="phone">
                                (044)  <span class="head-ph-numb">331-38-59</span><br>
                                (093)  <span class="head-ph-numb">679-03-20</span><br>
                        </div>
                        <div class="phone">
                                (050)  <span class="head-ph-numb">833-97-19</span><br>
                                (067)  <span class="head-ph-numb">765-40-06</span><br>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-md-offset-0 col-lg-offset-0 col-sm-8 col-sm-offset-2 col-xs-12 minicart_block">
                        <?=$cart?>
                    <div><button class="callbacklink btn btn-primary" onclick="opendialog('.callbackform');return false;">Обратный звонок</button></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">
    <div class="row lineNav">
           <?php if(isset($nav)) echo $nav;?>

        <div class="col-md-9">
         <?php if(isset($topline)):?>
         <div class="row hidden-xs hidden-sm">
               <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                 <?=$topline;?>
               </div>
          </div>
         <?php endif;?>
         <?php if(isset($slider)):?>
          <div class="row">
               <div class="col-md-12">
                     <?=$slider;?>       
               </div>
           </div>
           <?php endif;?>
        </div>
    </div>
</div>
<div class="<?=$container?>"> 
   <div class="row main-content">
   <?php if(isset($left_block)):?>
    <div class="<?=$lbwd?> leftblock">
        <?php foreach ($left_block as $block):?>    
        <div class="l_block"><?=$block?></div>
        <?php endforeach; ?>
        <?php if(isset($filter)):?>
        <?=$filter?>
        <?php endif;?>
    </div>
   <?php endif; ?>
   <?php if(isset($center_block)):?>
    <div class="content <?=$wd;?>"> 
        <?php if(isset($breadcrumbs)) echo $breadcrumbs;?>
        <?php if(isset($page_caption)):?>
            <h1 class="page_title"><?=$page_caption?></h1>
         <?php endif; ?>  
            <?php foreach ($center_block as $block):?>    
                <div class="c_block"><?=$block?></div>
            <?php endforeach; ?>  
    </div>
   <?php endif; ?>
   <?php if(isset($right_block)):?>
    <div class="<?=$lbwd?> rightblock">
        <?php foreach ($right_block as $block):?>    
        <div class="r_block"><?=$block?></div>
        <?php endforeach; ?>    
    </div>
    <?php endif; ?>  
   </div>
</div>
<footer class="footer">
    <div class="footer-popup">
        <a href="">
            <img src="/public/img/chat.svg">
        </a>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4">
                <p class="foot-left">© 2010 - <?=date('Y')?>, Трейдмаг</p>
            </div>
            <div class="col-md-7">
                <p class="foot-right">Режим работы: c 10.00 до 18.00&nbsp;
                <span class="foot-phone">(067) 765-40-06<span></span></span></p>
            </div>
            <div class="col-md-1"><a href="https://www.facebook.com/trademagkiev/" target="_blank"><img style="margin-top: 7px;" src="/public/img/facebook.png" alt="Мы в Facebook" width="35" height="35"></a></div>
        </div>
    </div>
</footer>
<?php if(isset($scripts) && count($scripts)>0):?>
        <?php foreach ($scripts as $script):?>
        <?=HTML::script($script)?>
        <?php endforeach;?>
<?php endif;?>
<div class="overlay">
    <div class="chat-popup">
        <div class="popup-wr">
            <div class="header">
                <div class="title">Помощь 24/7</div>
                <div class="close-btn">×</div>
            </div>
            <div class="popup-body">
                <p>Напишите нам</p>
                <form class="form-whriteback" id="frmwhriteback">
                    <input name="name" placeholder="Укажите ваше имя!" class="name" required />
                    <input name="email" placeholder="Укажите ваш Email!" class="email" type="email" required />
                    <textarea rows="4" cols="50" name="subject" placeholder="Введите ваше сообщение:" class="message" required></textarea>
                    <input name="submit" class="btn" type="submit" value="Отправить" />
                </form>
                <div class="whriteback-result">

                </div>
            </div>
        </div>
    </div>
    <div class="prodaddform">
        <div class="form_add_header">Корзина</div>
            <form id="prod_add" class="pop_form">
                <div id="prodadd_content">

                </div>
                <a href="#" class="button" onclick="closedialog();return false;">Продолжить покупку</a>
                <a href="#" class="button" onclick="window.location=&quot;/cart&quot;">Оформить заказ</a>
            </form>
    </div>
  <div class="callbackform">
      <a href="" class="closecb" onclick="closedialog('.callbackform');return false;">close</a>
            <form  class="cbform" id="cbform">
                <p>Обратный звонок</p>
                <input type="text" name="cbuser" placeholder="Ваше имя" required/>
                <input type="text" name="cbphone" placeholder="Номер телефона" required/>
                <input type="hidden" name="formData" value="Заявка с сайта">
                <input type="submit" name="cbsubmit" class="cbsubmit" value="Заказать звонок"/>
            </form>
    </div>
</div>
    <!--<a href="#x" class="overlay" id="win1" ></a>-->
<div id="scrollup"><img alt="Прокрутить вверх" src="/public/img/go-up.png" ></div>
<?php if(!empty($jivosite)) echo $jivosite ?>
</body>
</html>