$(document).ready(function() {
    
    $( ".divisions_dd" ).change(function() {
        var division_id = $(this).val();

        if (division_id) {

        } else {
            $(".home_teams_dd").prop("disabled", true);
            $(".visitor_teams_dd").prop("disabled", true);
        } 
        $.post(base_url+'admin/system_users/get_teams_for_division_id', {div_id: division_id}, function(teams){

            if (teams){
                var home_teams_dd = $(".home_teams_dd");
                var visitor_teams_dd = $(".visitor_teams_dd");
                home_teams_dd.html('');
                visitor_teams_dd.html('');

                teams = JSON.parse(teams);
                // if there is a team in the division
                if (teams!=0){
                    $(".home_teams_dd").prop("disabled", false);
                    $(".visitor_teams_dd").prop("disabled", false);
                    home_teams_dd.append($("<option />").val('').text('Select'));
                    visitor_teams_dd.append($("<option />").val('').text('Select'));
                    for(var key in teams) {
                        home_teams_dd.append($("<option />").val(teams[key]['id']).text(teams[key]['name']));
                        visitor_teams_dd.append($("<option />").val(teams[key]['id']).text(teams[key]['name']));
                    }
                } else {
                    $(".home_teams_dd").prop("disabled", false);
                    $(".visitor_teams_dd").prop("disabled", false);
                    home_teams_dd.append($("<option />").val('').text('No teams'));
                    visitor_teams_dd.append($("<option />").val('').text('No teams'));
                }      
            }        
        })
    });
}); 