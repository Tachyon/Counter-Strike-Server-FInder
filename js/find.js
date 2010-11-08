$(document).ready(function()
	{
	$.post("search.php", {}, function(data){
			if(data.length >0) 
				{
				$('#content').html(data);
				}
			});
	});