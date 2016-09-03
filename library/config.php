<?php
/*
* Автор скрипта: KrotovRoman.ru
* Специально для проекта: AutoOrder- бесплатный скрипт приема заказов и партнерской программы который так же можно скачать бесплатно здесь: http://videolamp.org/courses/autoorder
* По вопросам настройки IROBOT пишите сюда внизу в комментариях: http://krotovroman.ru/irobot/
*/
//Настройки соединения с БД
define("DB_PDODRIVER", 		"mysql"); //не менять
define("DB_HOST", 			"localhost"); //можно оставить localhost
define("DB_DATABASE", 		"имя базы"); //имя базы данных
define("DB_USERNAME", 		"имя пользователя"); //имя пользователя базы данных
define("DB_PASSWORD", 		"пароль от базы данных"); //пароль от базы данных

//параметры для работы с API вконтакте
define("VK_ID", 0000000); //Standalone приложение! ваш ID Standalone приложения ВК
define("VK_KEY", "ваш секретный ключ приложения ВК"); //секретный ключ приложения

/*
Получить токен - ввести этот URL, но перед этим изменить client_id на id приложения:
В этом URL 0000000 - заменить на ваш ID Standalone приложения ВК
Вставить в браузер:
https://oauth.vk.com/authorize?client_id=0000000&scope=groups,wall,offline,photos,friends,notes,pages,status,messages,email,notifications,stats,ads&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.21&response_type=token

Получить разрешения и из выданного url вытащить access_token.
*/
define("VK_TOKEN", 'Ваш токен');
error_reporting( E_ERROR );






























function cleanInput($input) {

  $search = array(
    '@<script[^>]*?>.*?</script>@si',   // javascript
    '@<[\/\!]*?[^<>]*?>@si',            // HTML теги
    '@<style[^>]*?>.*?</style>@siU',    // теги style
    '@<![\s\S]*?--[ \t\n\r]*>@'         // многоуровневые комментарии
  );

    $output = preg_replace($search, '', $input);
    return $output;
}
  
function sanitize($input) {
    if (is_array($input)) {
        foreach($input as $var=>$val) {
            $output[$var] = sanitize($val);
        }
    }
    else {
        if (get_magic_quotes_gpc()) {
            $input = stripslashes($input);
        }
        $input  = cleanInput($input);
        $output = mysql_real_escape_string($input);
    }
    return $output;
}


