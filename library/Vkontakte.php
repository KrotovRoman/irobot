<?php
/*
* Автор скрипта: KrotovRoman.ru
* Специально для проекта: AutoOrder- бесплатный скрипт приема заказов и партнерской программы который так же можно скачать бесплатно здесь: http://videolamp.org/courses/autoorder
* По вопросам настройки IROBOT пишите сюда внизу в комментариях: http://krotovroman.ru/irobot/
*/

//error_reporting( E_ERROR ); //отключаем варнинги, чтобы если сервер БД упал скрипт не остановился 
class Vkontakte {
	protected $filename="result.txt";
	
	public function liking_txt($object) {	//лайкнуть аву
		$src=realpath(__DIR__."/../results/")."/".$this->filename;	
		
		if (file_exists($src)) {
				$lines_in_file = file($src);//открываем полученный файл
				
				$data[status]="Открываем файл ".$src;					
				$this->db->insert($data, "getmembers");
				
				if (count($lines_in_file)>0) {// если в файле есть записиа
					
					$data[status]="Нашли ID ".count($lines_in_file)." шт.";					
					$this->db->insert($data, "getmembers");
							
				
					foreach ($lines_in_file as $uid) {
						$data[status]="Получаем данные пользователя с http://vk.com/id".$uid;					
						$this->db->insert($data, "getmembers");
						
						$user=$this->users_get($uid); //https://vk.com/dev/execute - превышено число запросов в секунду
						
						if (is_array($user)) {
							if (!is_null($user[0])) {
								$ava=explode("_",$user[0]->photo_id);
								
								if (!is_null($ava[1])){
									$data[status]="У него есть аватар ".$ava[1].", значит ставим лайк!";					
									$this->db->insert($data, "getmembers");
									
									$mres=$this->like_ava($uid, $ava[1]);
									if (!is_null($mres->likes)) {
										$data[status]="Поставили лайк успешно";					
										$this->db->insert($data, "getmembers");
									} else {
										$data[status]=$mres->error->error_msg;					
										$this->db->insert($data, "getmembers");
									}
								}
								
							}	
						}
					}
				}//if (count($lines_in_file)>0) {
		}//if (file_exists($src)) {
		
		$data[status]="Ничего не добавлено!";	
		$aResult[itemn] = 'завершена.';
		$aResult[countsaved] = number_format(count($lines_in_file), 0, ',', ' ');
		if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
		
	}
	public function liking($object) {	//лайкнуть аву
		$m=$this->extract_id($object->urls_likes);
		if (count($m)>0)
		foreach ($m as $screen_name) 
			$screen_names.=$screen_name.",";
		
		$users=$this->users_get($screen_names);
	
		$mr=array();
		if (count($users)>0)
			foreach ($users as $user) {
				if (!is_null($user->photo_id)) {
					$ava=explode("_",$user->photo_id);
					$mres=$this->like_ava($user->uid, $ava[1]);
				}
			}
		$data[status]="Ничего не добавлено!";	
		$aResult[itemn] = 'завершена, ничего не найдено!';
		$aResult[countsaved] = number_format(count($users), 0, ',', ' ');
		if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
	}
	
	public function like_ava($uid, $photo_id){ //лайкнуть аватарку
		$params['owner_id']= trim($uid);
		$params['item_id']= trim($photo_id);
		$params['type']= 'photo';
	
		$res=$this->api('likes.add',$params);
		return $res;
	}
	
	
	public function onlyingroup($object) {//обработать файл и вытащить только участников групп
		$src=realpath(__DIR__."/../results/")."/".$this->filename;	
		
		if (file_exists($src)) {
				$lines_in_file = file($src);//открываем полученный файл
				
				$data[status]="Открываем файл и проверяем вхождения в ".$object->min_in." групп.";					
				$this->db->insert($data, "getmembers");
				
				if (count($lines_in_file)>0) {// если в файле есть записиа
					
					$data[status]="Считаем вхождения в группы";					
					$this->db->insert($data, "getmembers");
					
					$lines_in_file=array_count_values($lines_in_file);//то вытаскиваем повторяющиеся ID 					
					
					
					unset($lines); $co=0;	
					foreach ($lines_in_file as $id_user=>$count){
						$id_user=trim($id_user);
						if ($count>=$object->min_in&&!empty($id_user)&&strpos($id_user,"-")!==0)  {//если количество вхождений больше заданного числа 
							$lines.=$id_user."\n"; //то нам нужны такие пользователи
							$co++;
						}
					}	
					
					if ($co>0) {//если что то осталось после выборки
						
						$data[status]="Пишем в файл активных с учетом участия в группах - ".$co." шт.";					
						$this->db->insert($data, "getmembers");
					
						//теперь полученных юзеров пишем в файл за место старого
						if (file_exists($src)) unlink($src); //старый удаляем
						
						$file = fopen($src, "w");
						fwrite ($file,$lines); //сохраняем в файл и отдаем на скачивание
						fclose($file);	
						
						$aResult[countsaved] = number_format($co, 0, ',', ' ');
						
					} else {//if ($co>0) 
						$data[status]="Сохранять то нечего!";					
						$this->db->insert($data, "getmembers");
						$aResult[countsaved] = 0;
						$aResult[itemn] = 'завершена, ничего не найдено!';
						if (file_exists($src)) unlink($src); //старый удаляем
						
					}
				} else {//
					$data[status]="Файл с результатами пуст!";					
					$this->db->insert($data, "getmembers");
					$aResult[countsaved] = 0;
					$aResult[itemn] = 'завершена, ничего не найдено!';
					if (file_exists($src)) unlink($src); //старый удаляем
						
				}
				
			
		} else {
			$aResult[countsaved] = 0;
			$aResult[itemn] = 'завершена, ничего не найдено!';
		}		
		if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
	}
	
	public function getMembers($object) {	//получить данные группы
		if (!empty($object->urls)) {							
		$aResult[itemn] = $object->urls;
		$m=$this->extract_id($object->urls);
		if ($object->min_in<=0) $object->min_in=2;				
		$src=realpath(__DIR__."/../results/")."/".$this->filename;	
		
		$data[status]="Сканируем: ".$object->urls;
		$this->db->insert($data, "getmembers");
				
		$mres=array(); 
		$mres_gr= array();
	
		$data_group=$this->get_data($m[0]); //получаем данные группы
		
		if ($data_group==false) {
			$aResult[itemn] = 'завершена, ничего не найдено!';
			if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
			return false;
		}
		
		if ($object->withactive=="true") {		
			
			if (!empty($object->datestart)) {
				$date = new DateTime($object->datestart);			
				$params_wall[datastart]=date_timestamp_get($date); 
			}
			
			$params_wall[publicID]="-".$data_group->gid;				
			$params_wall[likes]=$object->likes;
			$params_wall[reposts]=$object->reposts;
			$params_wall[comments]=$object->comments;
			$posts=$this->wall_get($params_wall);
			
			
			if ($posts!==0){//если найдены посты за указанную дату		
			
				foreach ($posts as  $post_id){//обходим все посты
				
					$data[status]="Сканируем пост http://vk.com/wall-".$data_group->gid."_".$post_id;						
					$this->db->insert($data, "getmembers");
				
					if ($object->likes=="true") {//вытаскиваем всех кто делал лайки
						$data[status]="Считаем лайки в посте http://vk.com/wall-".$data_group->gid."_".$post_id;					
						$filter[id]=1; $this->db->update($data, $filter,"getmembers");
						$params_likes[owner_id]="-".$data_group->gid;
						$params_likes[item_id]=$post_id;
						$res= $this->likes_getList($params_likes);	
						
						if ($res!==0) {
							$data[status]="Нашли лайков: ".count($res);					
						 	$this->db->insert($data, "getmembers");						
						 	$mres = array_merge($mres, $res);	
						}
					}
					
					if ($object->reposts=="true") {//вытаскиваем всех кто делал репосты
						$data[status]="Считаем репосты в посте http://vk.com/wall-".$data_group->gid."_".$post_id;					
						$this->db->insert($data, "getmembers");
												
						$params_likes[owner_id]="-".$data_group->gid;
						$params_likes[item_id]=$post_id;
						$res= $this->copies_getList($params_likes);	
						
						if ($res!==0) {
							$data[status]="Нашли репостов: ".count($res);					
							$this->db->insert($data, "getmembers");
							$mres = array_merge($mres, $res);
						}
						
					}
					
					if ($object->comments=="true") {//вытаскиваем всех кто делал комментарии
						$data[status]="Считаем комментарии в посте http://vk.com/wall-".$data_group->gid."_".$post_id;					
						$this->db->insert($data, "getmembers");
						
						$params_comments[owner_id]="-".$data_group->gid;
						$params_comments[post_id]=$post_id;
						$res= $this->wall_getComments($params_comments);							
						if ($res!==0) $mres = array_merge($mres, $res);			
					}
					
					
				}//foreach 
				
			
			} else {//if ($posts!==0){
				if ($object->withactive=="true") {
					$data[status]="Посты за указанный период не найдены";					
					$this->db->insert($data, "getmembers");
				}
			}
						
			
		} else { //если без учета активности
			$data[status]="Получаем подписчиков группы: ".$object->urls;					
			$this->db->insert($data, "getmembers");
			
			$res=$this->getMember($data_group->gid);
			
			if ($res!==0)  {
				$data[status]="Получили подписчиков: ".count($res);					
				$this->db->insert($data, "getmembers");
				
				$mres = array_merge($mres, $res);
			}
		}
			
		
		///////////////////////////////ПИШЕМ В ФАЙЛ РЕЗУЛЬТАТЫ ЕСЛИ ЕСТЬ//////////////////////////////
		unset($lines);
		if (count($mres)>0){//если что то вытащили
		
				$data[status]="Подготавливаем для записи в файл";					
				$this->db->insert($data, "getmembers");
			
				$mres= array_unique ($mres);
				
				if ($object->withactive=="true") { //если с учетом активности								
					foreach($mres as $id_user) {
						$id_user=trim($id_user);
						if (!empty($id_user)&&strpos($id_user,"-")!==0){
							$lines.=$id_user."\n";
							$co++;
						}
					}
				} else {//если не зависимо от активности										
					foreach($mres as $id_user) {
						$id_user=trim($id_user);
						if (!empty($id_user)&&strpos($id_user,"-")!==0){
							$lines.=$id_user."\n";
							$co++;
						}
					}//forea					
				} //else {/
			
			if ($co>0){//пишем результат в файл
				$data[status]="Записываем в файл всех найденных ".$co;					
				$this->db->insert($data, "getmembers");		
				
				$aResult[countsaved] = $co;
							
				$file = fopen($src, "a+");
				fwrite ($file,$lines); 
				fclose($file);
			}	
			
		} else {//если ничего не было вытащено из группы
			$data[status]="Посты за указанный период не найдены";					
			$this->db->insert($data, "getmembers");
		}
		/////////////////////////////////////////////////////////////////////////////////////
	
		
		//////////////если это была последняя группа - значит у нас есть готовый файл и если есть проверка на вступления в 2 группы	///////////
		if ($object->finish=="1"&&$object->ingroupcheckbox=="true"){	
			
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
			
		///////////////////////////////СЧИТАЕМ ЗАПИСАННЫХ В ФАЙЛ//////////////////////////////////////////////////			
		if (file_exists($src)) {
			$lines_in_file = file($src);//открываем полученный файл
			$count=count($lines_in_file);
			if ($count>0) 
				$aResult[countsaved] = $count;
			else {
				$data[status]="Ничего не добавлено!";					
				$this->db->insert($data, "getmembers");
			}
		} else {
			$data[status]="Ничего не добавлено!";					
			$this->db->insert($data, "getmembers");
		}
		///////////////////////////////////////////////////////////////////////////////////////////////////////////				
	
		
		if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
		} else {
			$aResult[itemn] = 'завершена, ничего не найдено!';
			if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
		}
	}//public functio
	
	
	
	
	
	
	public function friends_get($object) {	//получить друзей
		$m=$this->extract_id($object->urls_friends);
		if (count($m)>0)
		foreach ($m as $screen_name) {
			$res=$this->utils_resolveScreenName($screen_name);			
			if ($i>0) $screen_names.=",";
			$screen_names.=$res->object_id;
			$i++;
		}
		
		$data[status]="Получаем данные пользователей в количестве: ".count($m);					
		$this->db->insert($data, "getmembers");
				
		$users=$this->users_get($screen_names);
		
		$mr=array();
		if (count($users)>0)
			foreach ($users as $user) {
				$data[status]="Получаем друзей пользователя: ".$user->uid;					
				$this->db->insert($data, "getmembers");
				
				$mres=$this->friends_get_all($user->uid);
				if (count($mres)>0) $mr = array_merge($mr, $mres);
			}
		
		if (count($mr)>0) {	
			$mr= array_unique ($mr);
			
			foreach ($mr as $id_user)
					$lines.=$id_user."\n";	
			
			
			//пишем результат в файл
			$filename="result.txt";
			$src=realpath(__DIR__."/../results/")."/".$filename;
			$file = fopen($src, "a+");
			fwrite ($file,$lines); //сохраняем в файл и отдаем на скачивание
			fclose($file);
			
			$arr = file($src);
			$countsaved=count($arr);
			
			$aResult = array(
				'countsaved' => $countsaved,
				'itemn'=>$object->urls_friends
			);	
			
			if (!is_null($aResult)) print ($_GET["callback"])."(".json_encode($aResult).");";
				
		} else  $this->answer('alert', "Сохранять нечего!");
	}
	public function friends_get_all($uid){ //получить друзей
		$params['user_id']= $uid;
		$params['order']= 'hints';
		$params['name_case']= 'nom';		
		$res=$this->api('friends.get',$params);		
		return $res;
	}
	
	public function utils_resolveScreenName($screen_names){ //получить ID обьекта по screen_name
		$params['screen_name']= $screen_names;		
		$res=$this->api('utils.resolveScreenName',$params);
		return $res;
	}
	
	public function users_get($screen_names){ //получить данные пользователя или группы				
		$params['user_ids']= $screen_names;
		$params['name_case']= 'Nom';
		$params['fields']= 'photo_id';
		
		$res=$this->api('users.get',$params);
		return $res;
	}
	
	public function getgroups($object) { //найти группы
		if (!empty($object->result)) {
			$object->result=explode("\\n",$object->result);
			
			foreach ($object->result as $line)
				$lines.=$line."\n";	
		
			//пишем результат в файл
			$filename="result.txt";
			$file = fopen(realpath(__DIR__."/../results/")."/".$filename, "w");
			fwrite ($file,$lines); //сохраняем в файл и отдаем на скачивание
			fclose($file);
			
			$this->answer('alert', "Файл с <strong>".count($object->result)." шт.</strong> групп сохранен: <a href='http://".$_SERVER['HTTP_HOST']."/results/".$filename."' download><u>скачать результат</u></a>");
			
		} else $this->answer('alert', "Сохранять нечего!");
	}
	
	public function searchgroups($object) { //найти группы
			if (!empty($object->query)) {
				
				$res=$this->groups_search($object->query);
				
				if (count($res)>0){
				
					foreach($res as $group)
						if (!empty($group->screen_name)) 
							$items[]="http://vk.com/".$group->screen_name;						
						
					$items= array_unique ($items);
					
					foreach ($items as $link)
						$lines.=$link."\n";				
					
					$this->answer('info', $lines);
				}
				
				
			} else $this->answer('alert', "Ничего не найдено!");
	}
	
	public function groups_search($q){ //поиск групп
		$params['q']= $q;
		$params['count']= 1000;
		$params['search_global']= 1;
		$params['filters']= 'groups';
		$m=$this->api('groups.search',$params); //http://vk.com/dev/search.getHints
			
		return $m;
	}
	
	
	public function copies_getList($params_copies){//получить репосты поста
		$params['owner_id']= $params_copies[owner_id];
		$params['item_id']= $params_copies[item_id];
		$params['type']= 'post';
		$params['filter']= 'copies';
		$params['count']= 1000;
		$params['offset']= 0;
		$params['friends_only']= 0;
		$res=$this->api('likes.getList',$params); 	
		$count=$res->count;
		
		if (count($res->users)>0)
			foreach ($res->users as $uid){
				$m[]=$uid;				
			}
				
		if ($count>1000) {
			$offset=1000;
			while($offset<=$count){
				$params['offset']= $offset;
				$res=$this->api('likes.getList',$params);
				
				foreach ($res->users as $uid) {
					$mres[]=$uid;
				}
				
				if (count($mres)>0) $m = array_merge($m, $mres);
				
				$offset+=1000;	
			}
		}
		
		if (count($m)>0)
			return array_unique ($m); //убираем дубли
		else
			return 0;
	}
	
	public function likes_getList($params_likes){//получить лайки поста
		$params['owner_id']= $params_likes[owner_id];
		$params['item_id']= $params_likes[item_id];
		$params['type']= 'post';
		$params['filter']= 'likes';
		$params['count']= 1000;
		$params['offset']= 0;
		$params['friends_only']= 0;
		$res=$this->api('likes.getList',$params); 	
		$count=$res->count;
		
		if (count($res->users)>0)
			foreach ($res->users as $uid)
				$m[]=$uid;
				
		if ($count>1000) {
			$offset=1000;
			while($offset<=$count){
				$params['offset']= $offset;
				$res=$this->api('likes.getList',$params);
				
				foreach ($res->users as $uid) $mres[]=$uid;
				
				if (count($mres)>0) $m = array_merge($m, $mres);
				
				$offset+=1000;	
			}
		}
		
		if (count($m)>0)
			return array_unique ($m); //убираем дубли
		else
			return 0;
	}
	
	public function wall_getComments($params_comments){//получить комментарии поста
		$params['owner_id']= $params_comments[owner_id];
		$params['post_id']= $params_comments[post_id];
		$params['count']= 100;
		$params['offset']= 0;
		$params['need_likes']= 1;
		$res=$this->api('wall.getComments',$params); 		
		$count=$res[0];
		
		if ($res!==0) {
			$data[status]="Нашли комментариев: ".$count;					
			$this->db->insert($data, "getmembers");
		}
		
		if ($count>0)
			foreach ($res as $comment)
				$m[]=$comment->uid;
		
		
		if (count($count)>100) {
			$offset=100;
			while($offset<=$count){
				$params['offset']= $offset;
				$res=$this->api('wall.getComments',$params); 
				
				if (count($res)>0)	
					foreach ($res as $comment)
						$mres[]=$comment->uid;
				
				if (count($mres)>0) $m = array_merge($m, $mres);
				$offset+=100;
			}
		}
		
		
		
		if (count($m)>0)
			return array_unique ($m); //убираем дубли
		else
			return 0;
	}
	
	public function wall_getReposts($params_repost){ //получить список репостов
		$params['owner_id']= $params_repost[owner_id];
		$params['post_id']= $params_repost[post_id];
		$params['count']= 1000;
		$params['offset']= 0;
		$res=$this->api('wall.getReposts',$params);
		
		if (count($res->profiles)>0)
			foreach ($res->profiles as $profile)
				$m[]=$profile->uid;
		
		$offset=1000;	
		if (count($res->items)>1000)	
		while ($count_profiles>0){
			$params['offset']= $offset;
			$res=$this->api('wall.getReposts',$params);
			$count_profiles=count($res->profiles);
			
			if ($count_profiles>0)
				foreach ($res->profiles as $profile)
					$mres[]=$profile->uid;
			
			if (count($mres)>0) $m = array_merge($m, $mres);//добавляем к текущему массиву вытащенные ID постов
			
			$offset+=1000;		
		}
		
		
		if (count($m)>0)
			return array_unique ($m); //убираем дубли
		else
			return 0;
	}
	
	
	public function wall_get($params_wall){ //получить посты группы c активностью
		if (is_null($params_wall[datastart])) $params_wall[datastart]=time()-3600*24*30; //по умолчанию за месяц
		
		$params['owner_id']= $params_wall[publicID];
		$params['count']=100;
		$params['extended']=1;
		$params['filter']='owner';	
		$params['offset']=0;		
		$res=$this->api('wall.get',$params);
		
		
		$count=$res->wall[0];
		
		$data[status]="Всего постов вытащили - ".$count." шт.";
		$this->db->insert($data, "getmembers");
				
		if ($count>0)
		foreach ($res->wall as $wall){
			
			if ($wall->comments->count>0&&$wall->date>=$params_wall[datastart]&&$params_wall[comments]=='true'){//если есть комментарии моложе указанной даты
				$m[]=$wall->id;
			}
			if ($wall->likes->count>0&&$wall->date>=$params_wall[datastart]&&$params_wall[likes]=='true') {
				$m[]=$wall->id;
			}
			
			if ($wall->reposts&&$wall->date>=$params_wall[datastart]&&$params_wall[reposts]=='true'){
				$m[]=$wall->id;			
			}
			
			$maxdate=$wall->date;
		}
		
		
		
		if ($count>100&&$maxdate>=$params_wall[datastart]){ //если количество постов больше 100 и при предыдущем обходе дата не превысила порог
			$offset=100;
			while ($date>$params_wall[datastart]){//пока посты моложе указанной даты
				$params['offset']=$offset;		
				$res=$this->api('wall.get',$params);
				$count=$res->wall[0];
				if ($count>0)
				foreach ($res->wall as $wall){
					
					if ($wall->comments->count>0&&$wall->date>=$params_wall[datastart]&&$params_wall[comments]=='true'){//если есть комментарии моложе указанной даты
						$mres[]=$wall->id;
					}
					if ($wall->likes->count>0&&$wall->date>=$params_wall[datastart]&&$params_wall[likes]=='true') {
						$mres[]=$wall->id;
					}
					
					if ($wall->reposts&&$wall->date>=$params_wall[datastart]&&$params_wall[reposts]=='true'){
						$mres[]=$wall->id;			
					}	
					
					$date=$wall->date;				
				}//foreach
				
				if (count($mres)>0) $m = array_merge($m, $mres);//добавляем к текущему массиву вытащенные ID постов
				$offset+=100;
				
			}//while
		}
		
		$data[status]="Из них нам нужно - ".count($m)." шт.";
		$this->db->insert($data, "getmembers");
		
		if (count($m)>0)
			return array_unique ($m); //убираем дубли
		else
			return 0;
	}
	
	public function getMember($publicID){ //получить всех подписчиков группы
		
		$params['group_id']= $publicID;
		$res=$this->api('groups.getMembers',$params);	
		
		$count=$res->count;	
			
		$data[status]="Получили ".count($res->users)." шт";				
		$this->db->insert($data, "getmembers");	
		
		if (is_null($res->count)||$res->count<=0) return 0;
		if ($count<=1000) return $res->users; //если количество подписчиков не превышает 1000
		
		//если их больше 1000 то нужно вытасиквать частями
		if ($count<=0) return 0;
		unset($mres);
		$mres[]=$res->users;
		
		$i=1000; $sum=$i;
		while ($i<=$count){
			$params['offset']= $i;
			$res=$this->api('groups.getMembers',$params);
			$sum+=count($res->users);
			$data[status]="Получили ".$sum." шт";					
			$this->db->insert($data, "getmembers");	
			
			if (count($res->users)>0) $mres[]=$res->users;
			$i+=1000;
		}	
		
		
		$res=array();
		
		if (count($mres)>0)
		foreach ($mres as $m) 
			if (count($m)>0)
				$res = array_merge($res, $m);	 //объединяем массивы частей	
		
		return $res;
	}
	
	public function get_data($publicID){ //получить данные группы
		$params['group_id']= $publicID;
		$res=$this->api('groups.getById',$params);
		
		if (is_array($res))
			return $res[0];
		else 
			return false;
	}
	
	private function answer($itype, $iresult){ //ответ сервера в формате JSON
		$aResult = array(
			'result' => $iresult,
			'type'=>$itype
		);
		
		print ($_GET["callback"])."(".json_encode($aResult).");";			
	}
	
	private function extract_id($urls){//извлечь ID из URL
		
		$urls=explode("\\n",$urls);
		foreach ($urls as $url){
			$url=str_replace("http://vk.com/","",$url);
			$url=str_replace("https://vk.com/","",$url);
			if (!empty($url)) $m[]=$url;
		}
		
		return $m;
	}

	private function download($text,$filename){ //отдать на скачивание данные в TXT файле
		$fp= tmpfile();
		fwrite($fp, $text);
		
		$fmeta = stream_get_meta_data($fp); //получаем путь до временного файла
		$fpath = $fmeta ['uri'];
		$res=$this->file_force_download($fpath,$filename); 
		
		fclose($fp); //после закрытия временный файл автоматически удаляется
	}
	
	//отдать файл на скачивание http://habrahabr.ru/post/151795/
	private function file_force_download($file, $filename) {
	  if (file_exists($file)) {
		// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
		// если этого не сделать файл будет читаться в память полностью!
		if (ob_get_level()) {
		  ob_end_clean();
		}
		// заставляем браузер показать окно сохранения файла
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $filename);
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file));
		// читаем файл и отправляем его пользователю
		readfile($file);
		exit;
	  } else return false;
	}
	
	
    const VERSION = '5.6';

    /**
     * The application ID
     * @var integer
     */
    private $appId;

    /**
     * The application secret code
     * @var string
     */
    private $secret;

    /**
     * The scope for login URL
     * @var array
     */
    private $scope = array();

    /**
     * The URL to which the user will be redirected
     * @var string
     */
    private $redirect_uri;

    /**
     * The response type of login URL
     * @var string
     */
    private $responceType = 'code';

    /**
     * The current access token
     * @var \StdClass
     */
    private $accessToken;


    /**
     * The Vkontakte instance constructor for quick configuration
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['access_token'])) {
			$arr['access_token']=$config['access_token'];
            $this->setAccessToken(json_encode($arr));
        }
        if (isset($config['app_id'])) {
            $this->setAppId($config['app_id']);
        }
        if (isset($config['secret'])) {
            $this->setSecret($config['secret']);
        }
        if (isset($config['scopes'])) {
            $this->setScope($config['scopes']);
        }
        if (isset($config['redirect_uri'])) {
            $this->setRedirectUri($config['redirect_uri']);
        }
        if (isset($config['response_type'])) {
            $this->setResponceType($config['response_type']);
        }
		
		$this->db = $this->conn();	
			
    }
	 protected function conn() {
		require_once 'Database.php'; 
		return new Database(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DATABASE);	
	}

    /**
     * Get the user id of current access token
     * @return integer
     */
    public function getUserId()
    {

        return $this->accessToken->user_id;
    }

    /**
     * Set the application id
     * @param integer $appId
     * @return \Vkontakte
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;

        return $this;
    }

    /**
     * Get the application id
     * @return integer
     */
    public function getAppId()
    {

        return $this->appId;
    }

    /**
     * Set the application secret key
     * @param string $secret
     * @return \Vkontakte
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;

        return $this;
    }

    /**
     * Get the application secret key
     * @return string
     */
    public function getSecret()
    {

        return $this->secret;
    }

    /**
     * Set the scope for login URL
     * @param array $scope
     * @return \Vkontakte
     */
    public function setScope(array $scope)
    {
        $this->scope = $scope;

        return $this;
    }

    /**
     * Get the scope for login URL
     * @return array
     */
    public function getScope()
    {

        return $this->scope;
    }

    /**
     * Set the URL to which the user will be redirected
     * @param string $redirect_uri
     * @return \Vkontakte
     */
    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;

        return $this;
    }

    /**
     * Get the URL to which the user will be redirected
     * @return string
     */
    public function getRedirectUri()
    {

        return $this->redirect_uri;
    }

    /**
     * Set the response type of login URL
     * @param string $responceType
     * @return \Vkontakte
     */
    public function setResponceType($responceType)
    {
        $this->responceType = $responceType;

        return $this;
    }

    /**
     * Get the response type of login URL
     * @return string
     */
    public function getResponceType()
    {

        return $this->responceType;
    }

    /**
     * Get the login URL via Vkontakte
     * @return string
     */
    public function getLoginUrl()
    {

        return 'https://oauth.vk.com/authorize'
        . '?client_id=' . urlencode($this->getAppId())
        . '&scope=' . urlencode(implode(',', $this->getScope()))
        . '&redirect_uri=' . urlencode($this->getRedirectUri())
        . '&response_type=' . urlencode($this->getResponceType())
        . '&v=' . urlencode(self::VERSION);
    }

    /**
     * Check is access token expired
     * @return boolean
     */
    public function isAccessTokenExpired()
    {

        return time() > $this->accessToken->created + $this->accessToken->expires_in;
    }

    /**
     * Authenticate user and get access token from server
     * @param string $code
     * @return \Vkontakte
     */
    public function authenticate($code = NULL)
    {
        $code = $code ? $code : $_GET['code'];

        $url = 'https://oauth.vk.com/access_token'
            . '?client_id=' . urlencode($this->getAppId())
            . '&client_secret=' . urlencode($this->getSecret())
            . '&code=' . urlencode($code)
            . '&redirect_uri=' . urlencode($this->getRedirectUri());

        $token = $this->curl($url);
        $data = json_decode($token);
        $data->created = time(); // add access token created unix timestamp
        $token = json_encode($data);

        $this->setAccessToken($token);

        return $this;
    }

    /**
     * Set the access token
     * @param string $token The access token in json format
     * @return \Vkontakte
     */
    public function setAccessToken($token)
    {
        $this->accessToken = json_decode($token);

        return $this;
    }

    /**
     * Get the access token
     * @param string $code
     * @return string The access token in json format
     */
    public function getAccessToken()
    {

        return json_encode($this->accessToken);
    }

    /**
     * Make an API call to https://api.vk.com/method/
     * @return string The response, decoded from json format
     */
    public function api($method, array $query = array())
    {
        /* Generate query string from array */
        $parameters = array();
        foreach ($query as $param => $value) {
            $q = $param . '=';
            if (is_array($value)) {
                $q .= urlencode(implode(',', $value));
            } else {
                $q .= urlencode($value);
            }

            $parameters[] = $q;
        }

        $q = implode('&', $parameters);
        if (count($query) > 0) {
            $q .= '&'; // Add "&" sign for access_token if query exists
        }
		
        $url = 'https://api.vk.com/method/' . $method . '?' . $q . 'access_token=' . $this->accessToken->access_token;
      
	    $result = json_decode($this->curl($url));

        if (isset($result->response)) {

            return $result->response;
        }

        return $result;
    }

    /**
     * Make the curl request to specified url
     * @param string $url The url for curl() function
     * @return mixed The result of curl_exec() function
     * @throws \Exception
     */
    protected function curl($url)
    {
        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // disable SSL verifying
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        // $output contains the output string
        $result = curl_exec($ch);

        if (!$result) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
        }

        // close curl resource to free up system resources
        curl_close($ch);

        if (isset($errno) && isset($error)) {
            throw new \Exception($error, $errno);
        }

        return $result;
    }


    /**
     * @param $publicID int vk group official identifier
     * @param $fullServerPathToImage string full path to the image file, ex. /var/www/site/img/pic.jpg
     * @param $text string message text
     * @param $tags array message tags
     * @return bool true if operation finished successfully and false otherwise
	
	 ПРИМЕР ИСПОЛЬЗОВАНИЯ:
	 	//параметры для работы с API вконтакте
		define("VK_ID", 5057254); //Standalone приложение!
		define("VK_KEY", "VojfdP5isyuf1kZPtmB7");
		
		Получить токен - ввести этот URL, но перед этим изменить client_id на id приложения:
		https://oauth.vk.com/authorize?client_id=5057254&scope=groups,wall,offline,photos&redirect_uri=https://oauth.vk.com/blank.html&display=page&v=5.21&response_type=token
		Из скопированного url вытащить access_token.
		
		define("VK_TOKEN", 'b49fe49b573557832e7839a422865a3114ad9d9903065606d6678ad98569689ae745fb9b367442fb373fc');
		define("VK_GROUPID", 89065550); //ID группы в которую постить новости

		require_once (realpath(__DIR__."/library/classes/Vkontakte.php"));
		$config[access_token]=VK_TOKEN;
		$vkAPI = new \BW\Vkontakte($config);
		
		$srcimg=realpath(__DIR__."/img/logo.png");
		$tags[]='хэштег';
		$text="Тест API";
		$link="http://".$_SERVER['HTTP_HOST']."/?lot=228";
		
		$res=$vkAPI->postToPublic(VK_GROUPID, $text, $srcimg, $link, $tags);
		if ($res) echo "Пост добавлен: ".$res;

	 
     */
    public function postToPublic($publicID, $text, $fullServerPathToImage=NULL, $link=NULL,$tags = array()) {
		
		
		
		//если есть картинка
		if (!is_null($fullServerPathToImage)) {
			$mas['group_id']=$publicID;
			$response = $this->api('photos.getWallUploadServer', $mas);
			
			/*
			 * public 'upload_url' => string 'http://cs618028.vk.com/upload.php?act=do_add&mid=76989657&aid=-14&gid=70941690&hash=0c9cdfa73779ea6c904c4b5326368700&rhash=ba9b60e61e258bf8fd61536e6683e3af&swfupload=1&api=1&wallphoto=1' (length=185)
				  public 'aid' => int -14
				  public 'mid' => int 76989657
			 *
			 *  */
	
			$uploadURL = $response->upload_url;
			$output = array();
			exec("curl -X POST -F 'photo=@".$fullServerPathToImage."' '".$uploadURL."'", $output);
			if (is_null($output[0])) {
				echo "Ошибка при загрузки картинки вконтакте!";
				return false;
			}
			
			$response = json_decode($output[0]);
			/*
			 *  public 'server' => int 618028
				  public 'photo' => string '[{"photo":"96df595e0b:z","sizes":[["s","618028657","c5b1","RfjznPPyhxs",75,54],["m","618028657","c5b2","dQRTijvf4tE",130,93],["x","618028657","c5b3","-zUzUi-uOkU",604,432],["y","618028657","c5b4","FAAY0vnMSWc",807,577],["z","618028657","c5b5","OBZqwGjlO9s",900,644],["o","618028657","c5b6","Ku7Q6IqN5uc",130,93],["p","618028657","c5b7","0eFhSRrjxvU",200,143],["q","618028657","c5b8","F8E6QJg51o4",320,229],["r","618028657","c5b9","-a3oiI8SVOg",510,365]],"kid":"6bba9104fa05dd017597abce3ebeb215"}]' (length=496)
				  public 'hash' => string 'd02d83e70eca1c0d756d1a5d51c2fbfb' (length=32)
			 */
	
			$params['group_id']= $publicID;
			$params['photo']= $response->photo;
			$params['server']= $response->server;
			$params['hash']= $response->hash;
			$response = $this->api('photos.saveWallPhoto',$params);
			
			if (!empty($response->error)) {
				var_dump($response->error->error_code);
				var_dump($response->error->error_msg);
				return false;
			}
			/*
			 *
			 * array (size=1)
			0 =>
			object(stdClass)[93]
			public 'pid' => int 333363577
			public 'id' => string 'photo76989657_333363577' (length=23)
			public 'aid' => int -14
			public 'owner_id' => int 76989657
			public 'src' => string 'http://cs618028.vk.me/v618028657/c5c4/CJkUGsTNMNc.jpg' (length=53)
			public 'src_big' => string 'http://cs618028.vk.me/v618028657/c5c5/6G5kG2qrd0A.jpg' (length=53)
			public 'src_small' => string 'http://cs618028.vk.me/v618028657/c5c3/NjaefgAEqFA.jpg' (length=53)
			public 'src_xbig' => string 'http://cs618028.vk.me/v618028657/c5c6/dyX4tBB3yaI.jpg' (length=53)
			public 'src_xxbig' => string 'http://cs618028.vk.me/v618028657/c5c7/r8xGBKsau9c.jpg' (length=53)
			public 'width' => int 900
			public 'height' => int 644
			public 'text' => string '' (length=0)
			public 'created' => int 1402950212
			 *
			 */
		} //if image
		
		
		
		//добавляем хэш теги
        if ($tags&&count($tags)>0) $text .= "\n\n"; 
		
		if (count($tags)>0)      
        foreach ($tags as $tag) $text .= ' #' . str_replace(' ', '_', $tag);
     
        $text = html_entity_decode($text);
		
		unset($params);
		$params['owner_id']=-$publicID;
		$params['from_group']=1;
		$params['message']=$text;
		
		if (!is_null($fullServerPathToImage))  $params['attachments']=$response[0]->id;
		if (isset($link)&&!is_null($fullServerPathToImage))  $link=", ".$link.", ";
		if (isset($link)) $params['attachments'].=$link;
		
        $response = $this->api('wall.post',$params);

		
		if (isset($response->post_id))
        	return $response->post_id;
		else {
			//var_dump($response);
			return false;
		}
    }	
	
}
