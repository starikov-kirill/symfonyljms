$(document).ready(function() {
    $(".check_all").change(function () {
        //performed for all checkbox
    	﻿$(".check_one").prop('checked', this.checked);
    	process_ids();
    });

    $(".check_one").change(function(){
    	process_ids();
    });
    function process_ids() {  
        model_name = current_method.substring(0, current_method.length - 1); 
        // removal of all existing INPUT this information about selected fields 
    	$('input[name="'+model_name+'_ids[]"]').remove();
        // create a hidden field with the data from the selected field for all active checkbox
    	$(".check_one").each(function(){
    		if(this.checked){
    			$('form#action_select').prepend('<input type="hidden" value="'+$(this).data('item-id')+'" name="'+model_name+'_ids[]">');
    		}
    	});
    }
    $( ".action_dropdown" ).change(function() {
        var value_action = $(this).val();
        // make a button active if the selected action
        if (value_action) {
             $("#mass_action_button").attr({"class":"button"}).removeAttr("disabled"); ;
        } else {
             $("#mass_action_button").attr({"class":"inactiv", "disabled":"true"});
        }
    });
});   