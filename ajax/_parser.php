<?php  
/*
* Автор скрипта: KrotovRoman.ru
* Специально для проекта: AutoOrder- бесплатный скрипт приема заказов и партнерской программы который так же можно скачать бесплатно здесь: http://videolamp.org/courses/autoorder
* По вопросам настройки IROBOT пишите сюда внизу в комментариях: http://krotovroman.ru/irobot/
*/

set_include_path(get_include_path().PATH_SEPARATOR.realpath(__DIR__."/../library/")); /*Подгрузка классов и файлов из библиотеки*/

require_once 'config.php';
function __autoload($name_class) {require_once $name_class.'.php';} //автозагрузка классов из папки
	
$config[access_token]=VK_TOKEN;
$vkAPI = new Vkontakte($config);

$object  	= new stdClass(); // Создаём объект
foreach( $_REQUEST as $key=>$val )  $object->$key = $val; //получаем переменные

$rsrc=realpath(__DIR__."/../results/result.txt");
$db=new Database(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE);			
switch ($object->event) {
	case "liking_txt":   	$vkAPI->liking_txt($object);  break; //лайкнуть аватар
	case "liking":   		$vkAPI->liking($object);  break; //лайкнуть аватар
	case "getMembers":   	$vkAPI->getMembers($object);  break; //получить подписчиков групп
	case "searchgroups":   	$vkAPI->searchgroups($object);  break; //найти группы
	case "getgroups":   	$vkAPI->getgroups($object);  break; //скачать группы в TXT
	case "friends_get":   	$vkAPI->friends_get($object);  break; //скачать друзей пользователей
	case "unlink_log":   	$db->query_all("DELETE FROM `retarget`.`getmembers` WHERE `getmembers`.`id` >0"); break; //очистить лог
							
	case "unlink":   		if (file_exists($rsrc)) unlink($rsrc); 
							$db->query_all("DELETE FROM `retarget`.`getmembers` WHERE `getmembers`.`id` >0"); 
							break; //улдалить файл с результатами
	
	case "onlyingroup": 	$vkAPI->onlyingroup($object); break;
	case "status":   			
								$stat=$db->query_all('SELECT * FROM getmembers ORDER BY id DESC');
								
								if (count($stat)>0) {
									foreach($stat as $status){									
										$sta.=$status[status]."\n";
									}
								}
								
								$aResult = array(
									'status' => $sta
								);	
															
								if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
								
							 break;
}
