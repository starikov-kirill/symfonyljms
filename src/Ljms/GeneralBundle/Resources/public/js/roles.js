$(document).ready(function() {
    
    $(".divisions_dd").change(function() {
        var division_id = $(this).val();

        var role_id = $(".roles_dd").val();

        if (division_id && (role_id == 3 || role_id == 4 )) {
            $(".teams_dd").prop("disabled", false);
        } else if (division_id && role_id == 2){
            $(".add_role_btn").addClass('active_btn');
        } else {
            $(".teams_dd").prop("disabled", true);
            $(".add_role_btn").removeClass('active_btn');
        } 

        $.post(base_url+'admin/system_users/get_teams_for_division_id', {div_id: division_id}, function(teams){
            if (teams){
                teams = JSON.parse(teams);
                var teams_dd = $(".teams_dd");
                teams_dd.html('');
                teams_dd.append($("<option />").val(0).text('Select'));
                for(var key in teams) {
                    teams_dd.append($("<option />").val(teams[key]['id']).text(teams[key]['name']));
                }
            }        
        }) 
    });

    $(".teams_dd").change(function() {
         var team_id = $(this).val();
         if (team_id) {
            $(".add_role_btn").addClass('active_btn');
         } else {
            $(".add_role_btn").removeClass('active_btn');
         }
    });

    $(".roles_dd").change(function() {
      
        $(".divisions_dd").prop("disabled",true);
        $(".teams_dd").prop("disabled", true);
        $(".add_role_btn").removeClass('active_btn');

        var role_id = parseInt($(this).val());

        switch(role_id) {
            case 1:
            case 5:
                $(".divisions_dd").prop("disabled",true);
                $(".add_role_btn").addClass('active_btn');
                break;
            case 2:
                $(".divisions_dd").prop("disabled", false);
                $(".add_role_btn").removeClass('active_btn'); 
                break;
            case 3:
            case 4:
                $(".divisions_dd").prop("disabled",false);
                break;
        }          
    }); 
    
    // Adds new role to the roles table. Also adds hidden inputs with roles ids
    $(".add_role_btn").click(function(){
        if( ! $(this).hasClass('active_btn') ) return;

        var role_row_template = '<tr id="roles_block">' + $('#roles_block').html() + '</tr>';

        var template_data = {};

        template_data.role_id   = $('.roles_dd').val();
        template_data.role_name = $('.roles_dd option:selected').html();

        template_data.division_id   = $('.divisions_dd').val();
        template_data.division_name = $('.divisions_dd option:selected').html();

        template_data.team_id   = $('.teams_dd').val();
        template_data.team_name = $('.teams_dd option:selected').html();
        //if dd team not selected
        if ($(".teams_dd").prop("disabled")){
            template_data.team_name = ''; 
            template_data.team_id   = '';
        }
        //if dd division not selected
        if ($(".divisions_dd").prop("disabled")){
            template_data.division_name = ''; 
            template_data.division_id   = '';
        }
        //fields for the template
        template_data.role_input = '<input type="hidden" value="'+template_data.role_id+'" name="role[]">';
        template_data.div_input  = '<input type="hidden" value="'+template_data.division_id+'" name="div[]">';
        template_data.team_input = '<input type="hidden" value="'+ template_data.team_id+'" name="team[]">';

        //verify the existence of the role
        $.post(base_url+'admin/system_users/role_check', {
            div_id:  template_data.division_id,
            role_id: template_data.role_id, 
            team_id: template_data.team_id,
            user_id: user_id,

             }, function(roles){
                //if role exists
                if (roles=="TRUE") {
                    alert("Role already exists")
                // add if the role free
                } else {
                    template_data.role_to_user_id = '';

                    for(var placeholder_name in template_data) {
                        role_row_template = role_row_template.replace('{' + placeholder_name + '}', template_data[placeholder_name]);
                    }
                    $(role_row_template).appendTo($("thead + tbody"));
                }
        });
    }); 
    //delete role from db
    $(document).on("click", "a[href='#delete_role']", function(e){
        e.preventDefault();
        if(confirm('Are you sure want to delete this role?')){
            var role_id = $(this).data("item-id");
            if (role_id) {
                $.post(base_url+'admin/system_users/delete_role', {id: role_id});
            }

            $(this).parents("tr").remove();
        }
    });     
}); 