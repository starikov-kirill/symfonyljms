$(document).ready(function(){
//division validation
var required1 = true;
if (get_id) {
    required1 = false;
}
    $("#add_division").validate({

       rules:{
            status: {
                required: true,
            },
            name:{
                required: true,
                maxlength: 30,
            },
            base_fee:{
                number : true,
            },
            addon_fee:{
                number : true,
            },
            age_to:{
               age_validation: true,
            },
           userfile:{
                 accept: "image/jpg,image/png,image/jpeg,image/gif",
            },
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
            name:{
                required: true,
                maxlength: 30,
            },
            division_id:{
                required: true,
            },
            status:{
                required: true,
            },
            league_type_id:{
                required: true,
            },
       },

    });
    //user validation
    $("#add_user").validate({ 
        rules:{
            first_name:{
                required: true,
                maxlength: 30,
            },
            last_name:{
                required: true,
                maxlength: 30,
            },
            address:{
                required: true,
            },
            city:{
                required: true,
                maxlength: 30,
            },
            state_id:{
                required: true,
            },
            zipcode:{
                required: true,
                digits : true,
            },
            email:{
                required: true,
                 my_email_validation: true,
                 remote : {
                    url: base_url+"admin/system_users/email_jq_check"+get_id,
                    type: "post",
                },
            },
            repeat_email:{
                 equalTo: "#email",
            },
            home_phone:{
                required: true,
                my_phone_validation: true,
            },
            cell_phone:{
                my_phone_validation: true,
            },
            alt_phone:{
                my_phone_validation: true,
            },
            password:{
                required: required1,
            },
            repassword:{
                required: required1,
                equalTo: "#password",
            },
            alt_email:{
                 my_email_validation: true,
            },
            alt_phone_2:{
                my_phone_validation: true,
            }
       },
        messages:{
            email:{
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
        var from = parseInt($("#age_from").val());
        var to   = parseInt($("#age_to").val());
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
        },"Enter a valid email address!");
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