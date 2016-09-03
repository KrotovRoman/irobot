<div class="page-header">
<h1 class="text-uppercase">Лайкинг</h1>
</div>

<div class="row">


<div class="form-horizontal" >  
<div class="form-group col-md-12">
<textarea style='overflow-y: scroll' id='urls_likes' class="form-control" rows="20" placeholder="URL пользователей"></textarea>
</div>   
</div>

</div> 

</div>

<div class="row text-center">
<button id='liking' class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-heart"></span> Лайкнуть введенных</button> <button id='liking_txt' class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-heart"></span> Лайкнуть всех из result.TXT</button>
</div>
Обработка: <span id='itemn'></span>
<br><span id='countsaved'>0</span> шт.

<div id='statusbox' >
 <textarea id='status' class="form-control" style='overflow-y: scroll' rows=10></textarea>
 </div>
<script>
$(function() {
	
		$("#liking_txt").click(function() { //сохранить аудиторию групп
	   		var btn = $(this)
    		btn.button('loading');
			
			$.post( "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?event=unlink_log");//сначала удаляем старый файл с результатами
			
			
			$.ajax({
				data: {
					event: 				"liking_txt"
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
				}
			});
			
			
	   });
	   
	  
	   $("#liking").click(function() { //сохранить аудиторию групп
	   		var btn = $(this)
    		btn.button('loading');
			
			$.post( "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?event=unlink");//сначала удаляем старый файл с результатами
			
			var text = $('#urls_likes').val();
			var mas = text.split('\n');
			var urls = new Array();
			var is = new Array();
			
			count =urls.length;
			
			
			mas.forEach(function(item, i, mas) {
				urls.push(item);
				is.push(i);
			});
			
			
			status=0;
			$( "#statusbox").slideDown('fast');
			var timerId = setInterval(function() { //ajax запросы по таймеру
				
					$.ajax({
						data: {
							event: 	"status"
						},
						url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?callback=?",
						dataType: 'jsonp',
						type: "POST",							
						cache: false,
						timeout: 99999999999,
						complete: function(data){
							
							 if (typeof (data.responseJSON) !=='undefined') status=data.responseJSON.status;
							 $('#status').val(status);			
						}
					});
					
			}, 500);
			 
			finishCallback = function(){
				btn.button('reset');
				$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Аватарки пролайканы!</u></a></div>');		
				$("#itemn").html('завершена!');
				setTimeout(function() { clearInterval(timerId) }, 1000);
			}
		 
			function go(){
				 
				if(urls.length) {//если массив не пустой
					
					$.ajax({
						data: {
							event: 				"liking",
							urls_likes:		urls.shift(),
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

