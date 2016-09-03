<div class="page-header">
<h1 class="text-uppercase">Друзья пользователей</h1>
</div>

<div class="row">


<div class="form-horizontal" >  
<div class="form-group col-md-12">
<textarea style='overflow-y: scroll' id='urls_friends' class="form-control" rows="20" placeholder="URL пользователей"></textarea>
</div>   
</div>


</div> 


</div>

<div class="row text-center">
<button id='friends_get' class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-cloud-download"></span> скачать TXT</button>
</div>
Обработка: <span id='itemn'></span>
<br>Добавлено ID: <span id='countsaved'>0</span>

<script>
$(function() {
	 $("#friends_get").click(function() { //сохранить аудиторию групп
	   		var btn = $(this)
    		btn.button('loading');
			
			$.post( "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?event=unlink");//сначала удаляем старый файл с результатами
			
			var text = $('#urls_friends').val();
			var mas = text.split('\n');
			var urls = new Array();
			var is = new Array();
			
			count =urls.length;
			
			
			mas.forEach(function(item, i, mas) {
				urls.push(item);
				is.push(i);
			});
			
			 
			finishCallback = function(){
				btn.button('reset');
				$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Файл сохранен: </strong><a href="http://'+window.location.host+'/results/result.txt" download><u>СКАЧАТЬ РЕЗУЛЬТАТ</u></a></div>');		
				$("#itemn").html('завершена!');
			}
		 
			function go(){
				 
				if(urls.length) {//если массив не пустой
					
					urlss=urls.shift();
					$("#itemn").html(urlss);
					
					$.ajax({
						data: {
							event: 				"friends_get",
							urls_friends:		urlss,
							itemn: 				is.shift()
						},
						url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?callback=?",
						dataType: 'jsonp',
						type: "POST",							
						cache: false,
						timeout: 99999999999,
						beforeSend: function(){ $('#loading').fadeIn('fast'); },
						complete: function(data){
							$("#itemn").html(data.responseJSON.itemn);
							$("#countsaved").html(data.responseJSON.countsaved);
							$('#loading').fadeOut('fast');								
							go();
						}
					});
					
				} else {//иначе вызываем последнюю функцию
					finishCallback()
				}
			}   
					 
			go();	
	   });
});					
</script>

