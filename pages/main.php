
<div class="page-header">
<h1 class='text-uppercase'>Сбор аудитории сообществ</h1>
</div>

   <!--http://vk.com/autoorder.script
http://vk.com/krotovromanpublic
http://vk.com/delphicomponent-->
<div class="form-horizontal" >  
<div class="form-group col-md-4">

<textarea style='overflow-y: scroll' id='urls' class="form-control" rows="20" placeholder="Введите URL групп. Одна строка=Один URL">http://vk.com/autoorder</textarea>

    
</div>
 
</div>

<div class="col-md-4">

<div class="row">



  <div class="form-horizontal" >
  
  	<div class="form-group" hidden="true">
     <label class="col-sm-4 control-label">состоит</label>
     <div class='input-group col-md-4'> 
    <input id='ingroupcheckbox' type='checkbox' class='checkbox' data-off-label='false' data-on-label='false' data-off-icon-class='glyphicon glyphicon-remove' data-on-icon-class='glyphicon glyphicon-ok'>
	</div>
     </div>
     
     
     <div class="form-group">
      <label class="col-sm-4 control-label">С активностью</label>
     <div class='input-group col-md-4'> 
    <input id='withactive' type='checkbox' class='checkbox' data-off-label='false' data-on-label='false' data-off-icon-class='glyphicon glyphicon-remove' data-on-icon-class='glyphicon glyphicon-ok'>
	 </div>     
     </div>
     
     
	<div class="form-group">
      <label class="col-sm-4 control-label">С даты</label>
     <div class='input-group col-md-4'> 
    <input id='datestart' value='<?=date("d.m.Y", time()-3600*24*30);?>' placeholder="<?=date("d.m.Y", time()-3600*24*30);?>" class='date form-control' data-off-label='false' data-on-label='false' data-off-icon-class='glyphicon glyphicon-remove' data-on-icon-class='glyphicon glyphicon-ok'>
	 </div>     
     </div>
     
     
     
 	 <div class="form-group">
      <label class="col-sm-4 control-label">Лайки</label>
     <div class='input-group col-md-4'> 
    <input  id='likes' type='checkbox' class='checkbox' data-off-label='false' data-on-label='false' data-off-icon-class='glyphicon glyphicon-remove' data-on-icon-class='glyphicon glyphicon-ok'>
	 </div>     
     </div>
     
      <div class="form-group">
      <label class="col-sm-4 control-label">Репосты</label>
     <div class='input-group col-md-4'> 
    <input id='reposts' type='checkbox' class='checkbox' data-off-label='false' data-on-label='false' data-off-icon-class='glyphicon glyphicon-remove' data-on-icon-class='glyphicon glyphicon-ok'>
	 </div>     
     </div>
     
	<div class="form-group">
      <label class="col-sm-4 control-label">Комментарии</label>
     <div class='input-group col-md-4'> 
    <input c  id='comments' type='checkbox' class='checkbox' data-off-label='false' data-on-label='false' data-off-icon-class='glyphicon glyphicon-remove' data-on-icon-class='glyphicon glyphicon-ok'>
	 </div>     
     </div>
     
     
     <div class="form-group">
      <label class="col-sm-4 control-label">Обработка:</label>
     <div class='input-group col-md-7'> 
    	 <span id='itemn'></span>
	 </div>     
     </div>
     
     <div class="form-group">
      <label class="col-sm-4 control-label"><a href="http://<?=$_SERVER['HTTP_HOST'];?>/results/result.txt" download><u>Собрано ID:</u></a></label>
     <div class='input-group col-md-7'> 
    	 <span id='countsaved'></span>
	 </div>     
     </div>
     
    <div class="form-group">
      <label class="col-sm-1 control-label"> </label>                        
      <div class='input-group col-md-8'> 
       <span class='input-group-btn'>
       <button id='onlyingroup' class="btn btn-success">состоит в</button>
       </span>
      <input 
      data-container='body' 
      data-toggle='popover' 
      data-placement='right' 
      data-content='Введите количество групп' 
      name="min_in" 
      class='form-control'
      placeholder="2"
       value="2" >
      <span class='input-group-addon'>группах</span>
      </div>                        
    </div>    
     
     
     <div class="form-group">
      <label class="col-sm-4 control-label"></label>
     <div class='input-group col-md-7'> 
    	 <button id='getMembers' class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-cloud-download"></span> скачать TXT</button>
	 </div>     
     </div>
     
     
 </div> <!--form-horizontal-->
 
 	 <input id='laststat' hidden="true">
</div>

</div> 


</div>

<div class="row text-center">

</div>

 <center><h2  id='result'></h2></center>
<div class="progress progress-striped" id='progress' hidden="true">
  <div id='prog' class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
    <span class="sr-only"></span>
  </div>
</div>

<div hidden="true" id='statusbox' >
 <textarea id='status' class="form-control" style='overflow-y: scroll' rows=10></textarea>
 </div>
 
 
<script>
$(function() {
		
		$("#onlyingroup").click(function() {
			var btn = $(this)
    		btn.button('loading');
			$.ajax({
				data: {
					event: 			"onlyingroup",
					min_in:			$('[name="min_in"]').val()	
				},
				url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?callback=?",
				dataType: 'jsonp',
				type: "POST",							
				cache: false,
				timeout: 99999999999,
				complete: function(data){
					 $("#itemn").html(data.responseJSON.itemn);
					$("#countsaved").html(data.responseJSON.countsaved);
					if (data.responseJSON.countsaved>0) {
						$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Файл сохранен: </strong><a href="http://'+window.location.host+'/results/result.txt" download><u>СКАЧАТЬ РЕЗУЛЬТАТ</u></a></div>');		
					} else {
						$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Завершено! </strong>Ничего не собрано!</div>');		
					}
					btn.button('reset');										
				}
			});
		});
		
		$("#getMembers").click(function() { //сохранить аудиторию групп - последовательные запросы
	   		var btn = $(this)
    		btn.button('loading');
			
			status=0;
			
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
			
			$.post( "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?event=unlink");//сначала удаляем старый файл с результатами и чистим БД
			
			var text = $('#urls').val();
			var mas = text.split('\n');
			var urls = new Array();
			var is = new Array();
			
			count =mas.length;
			count--;
			
			$( "#statusbox").slideDown('fast');			
			$( "#progress").slideDown('fast');
			$("#result").html('');
			$( "#prog").width(0+'%');
			$( "#prog").attr("aria-valuenow",0);
			var progress=0;
			
			$("#itemn").html(mas[0]);
			mas.forEach(function(item, i, mas) {
				urls.push(item);
				is.push(i);
				progress++;
			});
			var ch=(100)/(progress); 
			var w=0;
			 
			finishCallback = function(){				
				btn.button('reset');
				count_saved=$("#countsaved").html();
				if (count_saved>0) {
					$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Файл сохранен: </strong><a href="http://'+window.location.host+'/results/result.txt" download><u>СКАЧАТЬ РЕЗУЛЬТАТ</u></a></div>');		
				} else {
					$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Завершено! </strong>Ничего не собрано!</div>');		
				}
				$("#itemn").html('завершена.');
				setTimeout(function() { clearInterval(timerId) }, 1000);
			}
		 
			function go(){
				 
				if(urls.length) {//если массив не пустой
					itemn=is.shift();
					if (itemn==count) finish=1; else finish=0;
					url=urls.shift();
					$("#itemn").html(url);
					
					$.ajax({
						data: {
							event: 				"getMembers",
							urls:				url,
							itemn: 				itemn,
							finish:				finish,
							ingroupcheckbox: 	$( "#ingroupcheckbox").prop('checked'),
							min_in:				$('[name="min_in"]').val(),	
							withactive:			$( "#withactive").prop('checked'),
							datestart:			$('#datestart').val(),
							likes:				$( "#likes").prop('checked'),
							reposts:			$( "#reposts").prop('checked'),
							comments:			$( "#comments").prop('checked')
						},
						url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?callback=?",
						dataType: 'jsonp',
						type: "POST",							
						cache: false,
						timeout: 99999999999,
						beforeSend: function(){ $('#loading').fadeIn('fast'); },
						complete: function(data){
							$("#itemn").html(data.responseJSON.itemn);
							count_savedfrom=parseInt(data.responseJSON.countsaved);
							
							if (count_savedfrom>0) {
								
								$("#countsaved").html(count_savedfrom);
								console.log($("#countsaved").html());
							}
							$('#loading').fadeOut('fast');	
							w+=ch;
							$( "#prog").width(w+'%');
							$( "#prog").attr("aria-valuenow",w);
							if (w>=100) {
								$("#result").html('Готово!');
								$( "#progress").slideUp('fast');
							}
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

