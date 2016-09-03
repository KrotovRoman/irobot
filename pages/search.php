<div class="page-header">
<h1 class='text-uppercase'>Поиск сообществ</h1>
</div>

<div class="row">
<div class='input-group form-horizontal form-group col-md-11' >
    <input id='query' type='text' class='form-control' placeholder='введите тему группы' value=''>
      
      <span class='input-group-btn'>
        <button id='searchgroups' type='button' class='btn btn-primary'><span class='glyphicon glyphicon-search'></span> ПОИСК</button>
      </span>
  </div>
 


<div class="form-horizontal" >  
<div class="form-group col-md-12">
<textarea style='overflow-y: scroll' id='result' class="form-control" rows="20" placeholder="URL групп"></textarea>
</div>   
</div>


</div> 


</div>

<div class="row text-center">
<button id='getgroups' class="btn btn-primary btn-lg"><span class="glyphicon glyphicon-cloud-download"></span> скачать TXT</button>
</div>


<script>
$(function() {
	   
	   
	    $("#searchgroups").click(function() { //сохранить аудиторию групп
		   
		    var btn = $(this)
    			btn.button('loading');
				
			
			var	data={
				event: 				"searchgroups",
				query:				$('#query').val()
			}
			
            $.ajax({      
                    type: "POST",
                    url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?callback=?",		      
                    data: data,	
					dataType: 'jsonp',
                    cache: false,
					timeout: 99999999999,
					beforeSend: function(){ $('#loading').fadeIn('fast'); },
                    success:function(data){
						console.log();
							if (data.type=="alert") {
                            	$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Внимание: </strong>'+data.result+'</div>');		
							}
							
							if (data.type=="info") {
								$("#result").html(data.result);
							}
							
                            btn.button('reset');
							$('#loading').fadeOut('fast');
					},
				    error: function(data){
						$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>ОШИБКА: </strong>При выполнении AJAX запроса произошла ошибка!</div>');		
						 btn.button('reset');
						$('#loading').fadeOut('fast');
					}
            }); 
			                  
       });
	   
	   $("#getgroups").click(function() { //сохранить аудиторию групп
		   
		    var btn = $(this)
    			btn.button('loading');
				
			
			var	data={
				event: 				"getgroups",
				result:				$('#result').val()
			}
			
            $.ajax({      
                    type: "POST",
                    url: "<?php echo 'http://'.$_SERVER['HTTP_HOST'];?>/ajax/_parser.php?callback=?",		      
                    data: data,	
					dataType: 'jsonp',
                    cache: false,
					timeout: 99999999999,
					beforeSend: function(){ $('#loading').fadeIn('fast'); },
                    success:function(data){
						console.log();
							if (data.type=="alert") {
                            	$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>Внимание: </strong>'+data.result+'</div>');		
							}
							
							if (data.type=="info") {
								$("#result").html(data.result);
							}
							
                            btn.button('reset');
							$('#loading').fadeOut('fast');
					},
				    error: function(data){
						$("#place_to_message").html('<div class="alert alert-warning fade in"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button><strong>ОШИБКА: </strong>При выполнении AJAX запроса произошла ошибка!</div>');		
						 btn.button('reset');
						$('#loading').fadeOut('fast');
					}
            }); 
			                  
       });
	   
});					
</script>
