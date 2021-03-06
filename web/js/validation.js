$(document).ready(function(){
//division validation
var required1 = true;
if (typeof userId == "undefined"){
   userId = '0';
}


//if in url edit
if(location.href.indexOf('edit') + 1) {
    required1 = false;
}
    $("#add_division").validate({

       rules:{
            'division[name]': {
                required: true,
            },
            'division[base_fee]': {
                number : true,
            },
            'division[addon_fee]': {
                number : true,
            },
            'division[age_to]': {
                age_validation: true,
            },
           /*userfile:{
                 accept: "image/jpg,image/png,image/jpeg,image/gif",
            },*/
       },
        messages: {
            userfile:{
                accept: 'Incorrect image format! Select jpg, png or gif'
            }
        }
    });
    //team validation
    $("#add_team").validate({ 
        rules:{
            'team[name]':{
                required: true,
            },
            'team[division_id]':{
                required: true,
            },
            'team[status]':{
                required: true,
            },
            'team[league_type_id]':{
                required: true,
            },
       },

    });
    //user validation
    $("#add_user").validate({ 
        rules:{
            'user[username]':{
                required: true,
                maxlength: 30,
            },
            'user[last_name]':{
                required: true,
                maxlength: 30,
            },
            'user[address]':{
                required: true,
            },
            'user[city]':{
                required: true,
                maxlength: 30,
            },
            'user[states_id]':{
                required: true,
            },
            'user[zipcode]':{
                required: true,
                digits : true,
            },
            'user[email][first]':{
                required: true,
                my_email_validation: true,
                remote : {
                    url: baseUrl+'/admin/users/email_jq_check/'+userId,
                    type: "post",
                },
            },
            'user[email][second]':{
                 equalTo: "#user_email_first",
            },
            'user[home_phone]':{
                required: true,
                my_phone_validation: true,
            },
            'user[cell_phone]':{
                my_phone_validation: true,
            },
            'user[alt_phone]':{
                my_phone_validation: true,
            },
        /*    'user[password][first]':{
                required: required1,
            },
            'user[password][second]':{
                required: required1,
                equalTo: "#user_password_first",
            },*/
            'user[alt_email]':{
                 my_email_validation: true,
            },
            'form[alt_phone_2]':{
                my_phone_validation: true,
            }
       },
        messages:{
            'user[email][first]':{
                remote: "Email is busy",
            },  
        }, 
    });
    // game result validation
    $("#add_results").validate({ 
           rules:{
            home_team_result:{
                required: true,
                digits : true,
            },
            visitor_team_result:{
                required: true,
                digits : true,
            },
        },
    });

    //game validation
    $("#add_game").validate({
         rules:{
            date:{
                required: true,
                date_validation: true,
            },
            time:{
                required: true,
            },
            division_id:{
                required: true,
            },
            home_team_id:{
                 home_dd_team: true,
            },
            visitor_team_id:{
                visitor_dd_team: true,
            },
            location_id:{
                required: true,
            },
       },

    });

    //own validation rule age
    $.validator.addMethod('age_validation',
    function() {
        var from = parseInt($("#division_age_from").val());
        var to   = parseInt($("#division_age_to").val());
        if (to<=from) {
                return  false;
            } else {
                return  true;
            }
    },"Incorrect years interval!");

    //own validation rule email
    $.validator.addMethod('my_email_validation',
        function(val,el)
    {
        var reg = /^[A-z0-9._-]+@[A-z0-9.-]+\.[A-z]{2,4}$/;
        if (!reg.test(val)&&(val)) {
                return  false;
            } else {
                return  true;
            }
        },"");
    //own validation rule phone
    $.validator.addMethod('my_phone_validation',
        function(val,el)
    {
        var reg = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;
        if (!reg.test(val)&&(val)) {
                return  false;
            } else {
                return  true;
            }
        },"Enter a valid phone!");

     //own validation rule age
    $.validator.addMethod('home_dd_team',
    function() {
        var home_team = parseInt($("#home_team").val());
        if (home_team) {
                return  true;
            } else {
                return  false;
            }
    },"This field is required.");

    $.validator.addMethod('visitor_dd_team',
    function() {
        var visitor_team = parseInt($("#visitor_team").val());
        if (visitor_team) {
                return  true;
            } else {
                return  false;
            }
    },"This field is required.");




    $.validator.addMethod('date_validation',
    function() {

        var myDate = $("#date").val();
        myDate=myDate.split("/");
        var newDate=myDate[0]+"/"+myDate[1]+"/"+myDate[2];
        var time = (new Date(newDate).getTime());

        var now = new Date();
        var today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

        if (time && (time >= today)) {
                return  true;
            } else {
                return  false;
            }
    },"Incorrect date.");
});