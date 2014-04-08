$(document).ready(function(){	

	//deletion recording from the database
	$("a[href='#delete']").click(function(e){
		//stop the default action
		e.preventDefault();
		//if the action is confirmed
	 	if (confirm("Are you sure you want to delete this item?")){
		 	var id = $(this).data("item-id");
		 	//sending a request to the controller
		 	$.post(base_url+'admin/'+current_method+'/delete', {id: id});
		 	//delete the item without rebooting
		    $(this).parents("tr").animate({ opacity: "hide" }, "slow");
	 	}
	});

	//deletion logo
	$("a[href='#delete_logo']").click(function(e){
		//stop the default action
		e.preventDefault();
		var division_id = $(this).data("item-id");
		//sending a request to the controller
		$.post(base_url+'admin/divisions/delete_logo', {division_id: division_id});
		//delete image without rebooting
		$(".logo").fadeOut();
	}); 
});