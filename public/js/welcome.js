$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    var input = document.getElementById("user_email");

    // Execute a function when the user releases a key on the keyboard
    input.addEventListener("keyup", function(event) {
        // Cancel the default action, if needed
        event.preventDefault();
        // Number 13 is the "Enter" key on the keyboard
        if (event.keyCode === 13) {
            // Trigger the button element with a click
            document.getElementById("btn_start_trial").click();
        }
    });
});



function addSubscriberinfo() {

    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/delete-saved-search',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                //alert("Successfully Delete");
                $("#sstr-" + id).remove();
                getSavedSearchItems();
            }
            $("#loading").hide();
        }

    });
}

$('#subsform input').keypress(function(e) {
    if (e.which == 13) {
        jQuery(this).blur();
        jQuery('#btn_request_trial').focus().click();
    }
});

$("#btn_request_trial").on("click", function() {

    var data = $("#request_trial_form").serialize();

    //if ($("#step").val() == 1) {
    //    if (!$("#name").val()) { alert("Please enter your first name."); return false; }
    //    if (!$("#last_name").val()) { alert("Please enter your last name."); return false; }
    //    if (!$("#phone").val()) { alert("Please enter a phone number."); return false; }
    if (!$("#trial_email").val()) { alert("Please enter an email"); return false; }
    if (!validateEmailBetter($("#trial_email").val())) { alert("Please enter a valid email"); return false; }

    // if (!$("#describe").val()) { alert("Describe is invalid!"); return false; }
    //}

    $("#loading").show();
    $.ajax({
        type: "post",
        url: baseurl + '/add-home-data',
        data: data + "&_token=" + _token,
        //data: data + "&email=" + $("#trial_email").val() + "&_token=" + _token,
        success: function(res) {


            if (res.success) {

                window.location.replace(res.follow_url);

            }
            /* if ($("#step").val() == 1) {
                 if (!res.success) {
                     alert(res.status);
                 } else {
                     //success

                     $("input[name*='location'][value=1]").prop("checked", true);
                     $("input[name*='location'][value=5]").prop("checked", true);

                     $("input[name*='job_type'][value=1]").prop("checked", true);
                     $("input[name*='job_type'][value=2]").prop("checked", true);
                     $("input[name*='job_type'][value=6]").prop("checked", true);

                     $('#headline').html("<div style='margin-left:55px; margin-right:55px;font-size:19px'>Please select the types of roles and locations you want to receive our <b>hiring manager reports</b> for.<br>You can change these settings at any time under the <i>Preferences</i> tab upon login<div>");
                     $('#btn_request_trial').html('Confirm Settings')
                     setTimeout(function() {
                         $.colorbox.resize();
                     }, 500);

                     $("#request_trial_step1").addClass("hide");
                     $("#request_trial_step2").removeClass("hide");
                     $("#step").val("2");
                 }
             } else if ($("#step").val() == 2) {
                 document.body.scrollTop = document.documentElement.scrollTop = 0;
                 window.location.reload(true);
             }
                 */
            $("#loading").hide();
        }

    });

});


$('#btn_job_type_select_all').click(function() {

    //$.colorbox.resize({ width: "300px", height: "300px" });
    var btn = $.trim($('#btn_job_type_select_all').text());

    if (btn == "Select All Job Types") {
        $("input[name*='job_type']").prop("checked", true);
        $('#btn_job_type_select_all').text("Unselect All");
    } else {
        $("input[name*='job_type']").prop("checked", false);
        $('#btn_job_type_select_all').text("Select All Job Types");
    }
    //*/
});

$('#btn_location_select_all').click(function() {

    var btn = $.trim($('#btn_location_select_all').text());

    if (btn == "Select All Locations") {
        $("input[name*='location']").prop("checked", true);
        $('#btn_location_select_all').text("Unselect All");
    } else {
        $("input[name*='location']").prop("checked", false);
        //$("input[name*='location']").removeAttr("checked");
        $('#btn_location_select_all').text("Select All Locations");
    }
    //*/
});

$("#li_location_tab").on("click", function() {
    setTimeout(function() {
        $.colorbox.resize();
    }, 500);
});


$("#li_job_type_tab").on("click", function() {
    setTimeout(function() {
        $.colorbox.resize();
    }, 500);
});


$("#btn_start_trial").on("click", function() {

    if (!$("#user_email").val()) {
        alert("Please enter your email to continue.");
        return false;
    }

    if (!validateEmail($("#user_email").val())) {

        alert("Please enter valid email to continue.");
        return false;

    }

    //return false;
    $.ajax({
        type: "post",
        url: baseurl + '/request-trial',
        data: "email=" + $("#user_email").val() + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                $("#trial_email").val(res.user.email);
            }

        }
    });
});

function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

function validateEmailBetter(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function updateRequestedAlert() {
    //window.location = baseurl + "/" + $("#location_id").val() + "/" + $("#job_type_id").val();

    $("#location_name_span").html($('#location_id').find('option:selected').text());

    $.ajax({
        type: "get",
        url: baseurl + '/carousel',
        data: "location_id=" + $("#location_id").val() + "&job_type_id=" + $("#job_type_id").val() + "&_token=" + _token,
        success: function(res) {

            //console.log(res.alerts[0].sl);
            var box = 1;
            var html = "";
            $.each(res.alerts, function(k, alrt) {
                //alert(k + "-" + alrt.title);

                if (box == 1)
                    html += '<div class = "active item" >';
                else if ((box - 1) % 3 == 0)
                    html += '<div class = "item" >';

                html += '<div class = "box" >';
                html += '<span class = "role-type" > ' + alrt.job_type + ' </span>';
                html += '<h3 class = "timehead" > Opened - ' + alrt.created_at + '</h3>';
                html += '<div class = "title-city" >';
                html += '<a class = "rtitle" href = "' + alrt.job_description_link + '" >' + alrt.title + '</a><br> <span class = "city" > ' + alrt.location + '</span> </div>';
                html += '<div class = "recruiter-info" ><div class = "row" ><div class = "col-md-4 col-xs-4 col-sm-4" >';
                html += '<a href = "' + alrt.hiring_manager_linkedin + '" class = "linkedin-icon">';
                html += '<img src = "http://recruiterintel.com/Linkedin.png" class = "pop" /> </a> </div> <div class = "col-md-8 col-xs-8 col-xs-8" >';
                html += '<span class = "hr-title" > Hiring Manager </span><br>';
                html += ' <span class = "hr-name" > ' + alrt.hiring_manager_name + '</span><br>';
                html += '<span class = "hr-position" > ' + alrt.hiring_manager_position + ' </span><br>';
                html += '</div ></div> <span class = "company-name" > ' + alrt.company + ' </span> </div >';
                html += '</div>'; //<!--box -->

                box++;
                if ((box - 1) % 3 == 0) html += '</div> '; //<!--item -->
            }); //foreach

            $(".carousel-inner").html(html);


        }
    });
}



/**********welcome**************/