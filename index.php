<?php 
/*
* Автор скрипта: KrotovRoman.ru
* Специально для проекта: AutoOrder- бесплатный скрипт приема заказов и партнерской программы который так же можно скачать бесплатно здесь: http://videolamp.org/courses/autoorder
* По вопросам настройки IROBOT пишите сюда внизу в комментариях: http://krotovroman.ru/irobot/
*/
set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__."/library/")); 
require_once 'config.php';
function __autoload($name_class) {require_once $name_class.'.php';} //автозагрузка классов из папки
$object = new stdClass(); // Создаём объект
foreach( $_REQUEST as $key=>$val )  $object->$key = $val; //получаем переменные
?>
<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>I ROBOT - парсер ВКонтакте.</title>
	<!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/css/bootstrap.min.css">
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="css/bootstrap-theme.min.css">
	<link href="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/jquery-ui.min.css" rel="stylesheet">
    
    <!-- Custom styles for this template -->
    <link href="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/css/sticky-footer.css" rel="stylesheet">
    <link href="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/css/dashboard.css" rel="stylesheet">
    
    <script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/jquery-2.1.4.min.js"></script>
    <script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/jquery-ui.min.js"></script>
    <script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/jquery.maskedinput.js"></script>
    <script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/bootstrap.min.js"></script>
    <script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/bootstrap_checkbox/bootstrap-checkbox.min.js"></script>
    <script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/sisyphus.min.js"></script>
    <script src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/js/main.js"></script>
  </head>

  <body>

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">I ROBOT</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="http://krotovroman.ru/irobot">Помощь</a></li>
          </ul>
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">            
            <li <?php if ($object->p=='search') echo 'class="active"';?>><a href="?p=search">Поиск сообществ</a></li>
          </ul>
          <ul class="nav nav-sidebar">
          	<li <?php if (empty($object->p)) echo 'class="active"';?>><a href="/">Сбор аудитории</a></li>
            <li <?php if ($object->p=='friends') echo 'class="active"';?>><a href="?p=friends">Друзья пользователей</a></li>
            <li <?php if ($object->p=='liking') echo 'class="active"';?>><a href="?p=liking">Лайкинг</a></li>
          </ul>
          <ul class="nav nav-sidebar">   
            <li><a href="http://krotovroman.ru/smarttimer/">Умный таймер</a></li>
            <li><a href="http://krotovroman.ru/utm/">Генератор UTM ссылок</a></li>
            <li><a href="http://autoorder.biz/">AutoOrder - скрипт приема заказов и партнерской программы</a></li>
            <li><a href="http://autoorder.biz/landing_vk/">ВК детектор - скрипт для сбора профилей посетителей сайта</a></li>
          </ul>
          
          <center hidden="true" id='loading' >  
              <img src="<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/img/loading.GIF"><BR>Я РАБОТАЮ...
          </center>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
			
            <div class="row">
            <div class="col-md-12">
                    
            
              <div  class="place_to_message" id="place_to_message"></div>
            
            
         	 <?php if (empty($object->p)) require_once 'pages/main.php'; else require_once 'pages/'.$object->p.'.php'; ?>
             
            </div>
          </div>
          
          
        </div>
      </div>
    </div>


    <div id="footer">
      <div class="container">
        <p class="text-muted text-center"><a href="http://krotovroman.ru/">Кротов Роман</a> © <?=date("Y");?> Все права защищены | <a href="http://krotovroman.ru/irobot">Инструкция по настройке I ROBOT</a></p>
      </div>
    </div>

  </body>
</html>
