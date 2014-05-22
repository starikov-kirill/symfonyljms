$(document).ready(function(){	

	//deletion recording from the database
	$("a[href='#delete']").click(function(e){
	   //stop the default action
	   e.preventDefault();
	   //if the action is confirmed
	   if (confirm("Are you sure you want to delete this item?")){
	       var id = $(this).data("item-id");
	       var elem = $(this);
	       //sending a request to the controller
	       $.post(baseUrl+'/admin/delete_'+class_name+'/'+id, function(del) {
				if (del)
				{	
					// delete image without rebooting
					elem.parents("tr").animate({ opacity: "hide" }, "slow");
					return
				}	           
	       });
	   }
	});

	//deletion logo
	$("a[href='#delete_logo']").click(function(e){
		//stop the default action
		e.preventDefault();
		var division_id = $(this).data("item-id");
		//sending a request to the controller
		$.post(baseUrl+'/admin/divisions/delete_logo/'+division_id, function(del) {
	           if (del){
	           	   // delete image without rebooting
	               $(".logo").fadeOut();
	               return
	           }	           
	       });
	}); 
});