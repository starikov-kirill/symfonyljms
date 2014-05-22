$(document).ready(function() {

var template_data = {};

//+++++++++++++++++++++++ Add user roles to page +++++++++++++++++++++++//

$.post(baseUrl+'/admin/link_teams_and_divisions', function(link_divisions_and_teams){
    // add fields if admin or guardian
    $('#user_roles input').each(function(){
        if ($(this).prop('checked')) {
            var existing_role = $(this).val();

            template_data.role_id   = existing_role;

            if ((existing_role == 1) || (existing_role == 5)) {            

                template_data.role_name = 'Admin';

                if (existing_role == 5) template_data.role_name = 'Guardian';            

                template_data.team_name = ''; 
                template_data.team_id   = '';

                template_data.division_name = ''; 
                template_data.division_id   = '';

                template_data.div_input  = '';
                template_data.team_input = '';

                template_data.role_input = '<input type="hidden" value="'+template_data.role_id+'" name="role">';

                var role_row_template = '<tr class="roles_block">' + $('.roles_block').html() + '</tr>';

                for(var placeholder_name in template_data) {
                    role_row_template = role_row_template.replace('{' + placeholder_name + '}', template_data[placeholder_name]);
                }
                $(role_row_template).appendTo($("thead + tbody"));

            }  
        }
    })

    // add fields if director
    $('#user_divisions input').each(function(){
        if ($(this).prop('checked')) {

            var existing_division_id = $(this).val();
            var existing_division_name = $("label[for=user_divisions_"+existing_division_id+"]").text();

            template_data.role_name = 'Director';
            template_data.role_id   = 2;

            template_data.team_name = ''; 
            template_data.team_id   = '';
            template_data.team_input = '';

            template_data.division_id   = existing_division_id;
            template_data.division_name = existing_division_name;

            template_data.div_input  = '<input type="hidden" value="'+template_data.division_id+'" name="div">';
            template_data.role_input = '<input type="hidden" value="'+template_data.role_id+'" name="role">';

            var role_row_template = '<tr class="roles_block">' + $('.roles_block').html() + '</tr>';

            for(var placeholder_name in template_data) {
                role_row_template = role_row_template.replace('{' + placeholder_name + '}', template_data[placeholder_name]);
            }
            $(role_row_template).appendTo($("thead + tbody"));
        }
    })

    //add field if coach
    $('#user_teamsCoachs input').each(function(){
        if ($(this).prop('checked')) {

            var existing_team_id = $(this).val();
            var existing_team_name = $("label[for=user_teamsCoachs_"+existing_team_id+"]").text();

            template_data.role_name = 'Coach';
            template_data.role_id   = 3;

            template_data.team_name = existing_team_name; 
            template_data.team_id   = existing_team_id;

            template_data.division_id   = '';
            template_data.division_name = link_divisions_and_teams[existing_team_id];

            template_data.div_input  = '';
            template_data.role_input = '<input type="hidden" value="'+template_data.role_id+'" name="role">';
            template_data.team_input = '<input type="hidden" value="'+template_data.team_id+'" name="team">';

            var role_row_template = '<tr class="roles_block">' + $('.roles_block').html() + '</tr>';

            for(var placeholder_name in template_data) {
                role_row_template = role_row_template.replace('{' + placeholder_name + '}', template_data[placeholder_name]);
            }
            $(role_row_template).appendTo($("thead + tbody"));
        }
    })

    //add field if manager
    $('#user_teamsManagers input').each(function(){
        if ($(this).prop('checked')) {

            var existing_team_id = $(this).val();
            var existing_team_name = $("label[for=user_teamsManagers_"+existing_team_id+"]").text();

            template_data.role_name = 'Manager';
            template_data.role_id   = 4;

            template_data.team_name = existing_team_name; 
            template_data.team_id   = existing_team_id;

            template_data.division_id   = '';
            template_data.division_name = link_divisions_and_teams[existing_team_id];

            template_data.div_input  = '';
            template_data.role_input = '<input type="hidden" value="'+template_data.role_id+'" name="role">';
            template_data.team_input = '<input type="hidden" value="'+template_data.team_id+'" name="team">';

            var role_row_template = '<tr class="roles_block">' + $('.roles_block').html() + '</tr>';

            for(var placeholder_name in template_data) {
                role_row_template = role_row_template.replace('{' + placeholder_name + '}', template_data[placeholder_name]);
            }
            $(role_row_template).appendTo($("thead + tbody"));
        }
    })
}) 

//+++++++++++++++++++++++ end add roles for page +++++++++++++++++++++++//


   $(".divisions_dd").change(function() {
        var division_id = $(this).val();

        var role_id = $(".roles_dd").val();

        if (division_id && (role_id == 3 || role_id == 4 )) {
            $.post(baseUrl+'/admin/teams_for_division/'+division_id, function(teams){
                if (teams) {
                    var teams_dd = $(".teams_dd");
                    teams_dd.html('');
                    teams_dd.append($("<option />").val(0).text('Select'));
                    for(var key in teams) {
                        teams_dd.append($("<option />").val(key).text(teams[key]));
                    }
                }        
            }) 
            $(".teams_dd").prop("disabled", false);

        } else if (division_id && role_id == 2) {

            $(".add_role_btn").addClass('active_btn');

        } else {

            $(".teams_dd").prop("disabled", true);
            $(".add_role_btn").removeClass('active_btn');

        } 
    });
/*
   if ($('#user_roles_1').prop('checked')){
    alert('dsg');
   }

*/
    $(".teams_dd").change(function() {
         var team_id = $(this).val();
         if (team_id) {

            $(".add_role_btn").addClass('active_btn');

         } else {

            $(".add_role_btn").removeClass('active_btn');

         }
    });

    $(".roles_dd").change(function() {
      
        $(".divisions_dd").val('0');
        $(".teams_dd").val('0');

        $(".divisions_dd").prop("disabled",true);
        $(".teams_dd").prop("disabled",true);
       // $(".teams_dd").prop("disabled", true);
        $(".add_role_btn").removeClass('active_btn');

        var role_id = parseInt($(this).val());

        switch(role_id) {
            case 1:
            case 5:
                $(".divisions_dd").prop("disabled",true);
                $(".teams_dd").prop("disabled",true);
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

        var role_row_template = '<tr class="roles_block">' + $('.roles_block').html() + '</tr>';

        template_data.role_id   = $('.roles_dd').val();
        template_data.role_name = $('.roles_dd option:selected').html();

        template_data.division_id   = $('.divisions_dd').val();
        template_data.division_name = $('.divisions_dd option:selected').html();

        template_data.team_id   = $('.teams_dd').val();
        template_data.team_name = $('.teams_dd option:selected').html();

        // if dd team not selected
        if ($(".teams_dd").prop("disabled")) {
            template_data.team_name = ''; 
            template_data.team_id   = '';
        }
        // if dd division not selected
        if ($(".divisions_dd").prop("disabled")) {
            template_data.division_name = ''; 
            template_data.division_id   = '';
        }
        // fields for the template
        template_data.role_input = '<input type="hidden" value="'+template_data.role_id+'" name="role">';
        template_data.div_input  = '<input type="hidden" value="'+template_data.division_id+'" name="div">';
        template_data.team_input = '<input type="hidden" value="'+template_data.team_id+'" name="team">';

       // $('#roles_block').find('p').find("input[name=role]").val();
        var rolecheck =  $('.roles_block').find('p input[name=role][value = '+template_data.role_id+']').val();

        // if admin or guardian
        if ((rolecheck == 1) || (rolecheck == 5)) {
            alert("Role already exists");

            return

        // if girector
        } else if (rolecheck == 2) {
            var divisioncheck =  $('.roles_block').find('p input[name=div][value = '+template_data.division_id+']').val();

            if (divisioncheck) {

                alert("Role already exists");

                return 
            }

        } else if ((rolecheck == 3) || (rolecheck == 4)) {

            var current_roles_str = template_data.role_id + template_data.team_id;

            var is_unique = true;
            $('.quarter').each(function(){
               var existing_role = $(this).find('input[name=role]').val() + $(this).find('input[name=team]').val();
               if(existing_role == current_roles_str) {
                   is_unique = false;
                   return;
               }
            });

            if(!is_unique) {

                alert("Role already exists");
                            
                return  
            }

        }
        
        // verify the existence of the role
       /* $.post(base_url+'admin/system_users/role_check', {
            div_id:  template_data.division_id,
            role_id: template_data.role_id, 
            team_id: template_data.team_id,
            user_id: user_id,

             }, function(roles){
                // if role exists
                if (roles=="TRUE") {
                    alert("Role already exists")
                // add if the role free
                } else {*/

                    //++++++++++++++++++++++++++++ add template ++++++++++++++++++++++++++++//

                    template_data.role_to_user_id = '';

                    for(var placeholder_name in template_data) {
                        role_row_template = role_row_template.replace('{' + placeholder_name + '}', template_data[placeholder_name]);
                    }
                    $(role_row_template).appendTo($("thead + tbody"));

                    //++++++++++++++++++++++++++++ work this checkbox ++++++++++++++++++++++++++++//

                    $("#user_roles_"+template_data.role_id).prop('checked', true);

                    // if role = director - checked checkbox this divisions
                    if (template_data.role_id == 2) {

                        $("#user_divisions_"+template_data.division_id).prop('checked', true);
                    } else if (template_data.role_id == 3) {

                        $("#user_teamsCoachs_"+template_data.team_id).prop('checked', true);

                    } else if (template_data.role_id == 4){

                        $("#user_teamsManagers_"+template_data.team_id).prop('checked', true);

                    }
       /*         }
        });*/

    }); 

    // delete role from db
    $(document).on("click", "a[href='#delete_role']", function(e){
        e.preventDefault();
        if(confirm('Are you sure want to delete this role?')) {
            var role_id = $(this).data("item-id");
            // if role savein bd
    /*        if (role_id) {
                $.post(base_url+'admin/system_users/delete_role', {id: role_id});
            }*/

            // get delete role
            var deleterole = $(this).parents("tr").find("input[name=role]").val();

            // if delete role admin or guardian
            if ((deleterole == 1) || (deleterole == 5)) {

              $("#user_roles_"+deleterole).prop('checked', false);
            // if delete role director
            } else if (deleterole == 2) {

                var deletediv = $(this).parents("tr").find("input[name=div]").val();
                $("#user_divisions_"+deletediv).prop('checked', false);

                // number of role this id = 2
                var director_number = $('.user_roles input[value="2"]').length;

                // if number = 1 checkbox this role = director ckecked false
                if (director_number == 1 ) {

                    $("#user_roles_"+deleterole).prop('checked', false);
                }
            } else if (deleterole == 3) {
                var deleteteam = $(this).parents("tr").find("input[name=team]").val();

                $("#user_teamsCoachs_"+deleteteam).prop('checked', false);

                // number of role this id = 3
                var coach_number = $('.user_roles input[value="3"]').length;

                if (coach_number == 1 ) {

                    $("#user_roles_"+deleterole).prop('checked', false);
                }
            } else if (deleterole == 4) {
                var deleteteam = $(this).parents("tr").find("input[name=team]").val();

                $("#user_teamsManagers_"+deleteteam).prop('checked', false);

                 // number of role this id = 4
                 var coach_number = $('.user_roles input[value="4"]').length;
                 if (coach_number == 1 ) {

                    $("#user_roles_"+deleterole).prop('checked', false);
                }
            }   
            $(this).parents("tr").remove();
        }
    });
}); 