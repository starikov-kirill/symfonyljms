$(document).ready(function() {
    //fills dd divisions from the database    
    $.post(base_url+'calendar/return_names_list', function(divisions){

        if (divisions){
            var division_list_schedule = $("#division_list_schedule");

            divisions = JSON.parse(divisions);

            if (divisions!=0){

                division_list_schedule.append($("<option />").val('').text('-- Select one --'));

                for(var key in divisions) {
                    division_list_schedule.append($("<option />").val(divisions[key]['id']).text(divisions[key]['name']));
                }
            }     
        }        
    });
    //reference when choosing a division
    $( "#division_list_schedule" ).change(function() {
        var division_id = $(this).val();

        if (division_id) {
           document.location.href = base_url+'calendar/division?id='+division_id;
        } 
    })
});