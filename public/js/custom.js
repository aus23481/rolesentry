/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

//if (!localStorage.status_mode) localStorage.status_mode = 0;

$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    $(".save_manual").on("click", function() {
        var data = $("#robot-company-form").serialize();
        //alert(data);
        $.ajax({
            type: "post",
            url: baseurl + '/edit-robot-company',
            data: data + "&_token=" + _token,
            success: function(data) {
                //alert(data);
                if (data.success) {
                    alert("Successfully Edited!!!");
                    console.log(data)
                }
            }
        });
    });

    $(".save_manual_approval").on("click", function() {
        var data = $("#robot-company-approval").serialize();
        //alert(data);
        $.ajax({
            type: "post",
            url: baseurl + '/edit-robot-company-approval',
            data: data + "&_token=" + _token,
            success: function(data) {
                //alert(data);
                if (data.success) {
                    //alert("Successfully Edited!!!");
                    console.log(data);
                    window.location = "/robot-company-approval";
                }
            }
        });
    });


});

function editCMSEmailAlert(id) {
    for (var instanceName in CKEDITOR.instances)
        CKEDITOR.instances[instanceName].updateElement();

    var data = $("#cms-email-alert-form-" + id).serialize();

    $.ajax({
        type: "post",
        url: baseurl + '/edit-cms-email-alert',
        data: data + "&_token=" + _token,
        success: function(data) {
            if (data.success) {
                alert("Successfully Edited!!!");
                console.log(data)
            }
        }
    });


}


function findAutoInput(action_name) {
    var data = $("#robot-company-form").serialize();
    //alert(data);
    $.ajax({
        type: "post",
        url: baseurl + '/find-auto-input',
        data: data + "&_token=" + _token + "&auto_input_name=" + action_name,
        success: function(data) {
            // alert(data);
            if (data.success) {
                //alert("Successfully Edited!!!");
                if (action_name == "website") {
                    $("#website").val(data.website);
                    $("#span_website").text(data.website);
                }
                if (action_name == "career_page") {
                    $("#career_page").val(data.career_page);
                    $("#span_career_page").text(data.career_page);
                }
                if (action_name == "key_selector") {
                    $("#key_selector").val(data.key_selector);
                    $("#span_key_selector").text(data.key_selector);
                }
                //console.log(data)
            }
        }
    });
}

//robot company approval
function setNewMode(valueId) {
    return updateQueryStringParameter(window.location.href, 'mode', valueId);
}

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
    var separator = uri.indexOf('?') !== -1 ? "&" : "?";
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        return uri + separator + key + "=" + value;
    }
}

function approveRobotCompany(actionid) {
    var data = $("#robot-company-approval").serialize();
    //if (actionid) localStorage.status_mode = actionid;
    //alert(data + "-" + actionid);
    ///*
    $.ajax({
        type: "post",
        url: baseurl + '/update-robot-company-approval-status',
        data: data + "&_token=" + _token + "&actionid=" + actionid,
        success: function(data) {
            //alert(data);
            if (data.success) {
                window.location = "/robot-company-approval";
            }
        }
    });
    //*/
}

//if (localStorage.status_mode) $("#status_mode").val(localStorage.status_mode);
$("input[name*='subtype_']").on("click", function() {

    var name = $(this).attr("name");
    var job_subtype_id = $(this).attr("value");
    var word_id = $(this).attr("word-id");

    //alert(name + "-" + id_value + "-" + word_id);

    //$("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/add-jobsubtype-word',
        data: "job_subtype_id=" + job_subtype_id + "&word_id=" + word_id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                alert("Successfully Saved!!!");
            } else {
                console.log("Error!!!");
            }
            //$("#loading").hide();
        }
    });
})

//send interaction mail


function sendMailToInteractionUser(user_id) {

    //alert(data);
    $.ajax({
        type: "get",
        url: baseurl + '/send-mail-to-interaction-user',
        data: "user_id=" + user_id + "&_token=" + _token,
        success: function(data) {
            //alert(data);
            if (data.success) {
                alert("Successfully Mailed!!!");
                //console.log(data)
            }
        }
    });
}

function RunManualScrapeLaction(location_id) {

    $.ajax({
        type: "get",
        url: baseurl + '/run-manual-scrape-location',
        data: "location_id=" + location_id + "&_token=" + _token,
        success: function(data) {
            //alert(data);
            if (data.success) {
                alert("Manual Command Run Successfully !!!");
                //console.log(data)
            }
        }
    });

}