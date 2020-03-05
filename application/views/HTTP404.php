<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8" />
  <title>Ошибка HTTP 404</title>
  <?php
   echo HTML::style('public/css/main.css');
  ?>
 </head>
 <body>
 <div id="container">
  <header id="header">
        <h1>ТРЕЙДМАГ</h1>
  </header>
  <div id="post">
    <div style="text-align:center;">
    <h2><?php echo $message?></h2>
    <?php echo HTML::image('public/img/404.jpg', array('alt' => '404')) ?> <br />
    <h4>Ошибка 404 или Not Found («не найдено») стандартный код ответа HTTP о том, что клиент был в состоянии общаться с сервером, но сервер не может найти данные согласно запросу.</h4> <br />
    </div>
  </div>
  <footer id="footer">
    &copy; <?php echo HTML::anchor('/', 'trademag'); ?> 2010-<?php echo date('Y'); ?> г
  </footer>
</div>
</body>
</html>