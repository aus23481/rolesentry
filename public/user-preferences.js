$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });

    //alert();


    $("#favorite_company").keyup(function(event) {
        if (event.keyCode === 13) {
            //alert($("#favorite_company").val());
            var val = $("#favorite_company").val();

            var obj = $("#companies").find("option[value='" + val + "']")
            if (obj != null && obj.length > 0) {
                //alert("valid");  // allow form submission
                var data = "name=" + $("#favorite_company").val();
                $.ajax({
                    type: "get",
                    url: baseurl + '/save-favorite-company',
                    data: data + "&_token=" + _token,
                    success: function(data) {

                        if (data.id > 0) alert("Successfully Added");
                        $("#favorite_company_list").append('<li style="text-transform:capitalize" class="list-group-item">' + data.name + ' <span style="cursor:pointer" onclick="deleteFavoriteCompany(' + data.fc_id + ')" class="badge">X</span></li>');
                        $("#favorite_company").val();
                    }
                });

            } else
                alert("Invalid Input");
        }
    });

    //requested companies

    $("#requested_company").keyup(function(event) {
        if (event.keyCode === 13) {
            //alert($("#favorite_company").val());
            var val = $("#requested_company").val();
            //alert("valid");  // allow form submission
            var data = "name=" + $("#requested_company").val();
            $.ajax({
                type: "get",
                url: baseurl + '/save-requested-company',
                data: data + "&_token=" + _token,
                success: function(data) {

                    if (data.id > 0) alert("Successfully Added");
                    $("#requested_companies").append('<li style="text-transform:capitalize" class="list-group-item">' + data.company_text_name + ' <span style="cursor:pointer" onclick="deleteRequestedCompany(' + data.id + ')" class="badge">X</span></li>');
                    $("#requested_company").val("");
                }
            });


        }
    });



    ///

});


function deleteFavoriteCompany(id) {
    //alert(id);
    var data = "id=" + id;
    $.ajax({
        type: "get",
        url: baseurl + '/delete-favorite-company',
        data: data + "&_token=" + _token,
        success: function(data) {
            // alert(data.length+"-"+data[0].name);
            $("#favorite_company_list").empty();
            if (data.length > 0) {

                $.each(data, function(index, company) {

                    $("#favorite_company_list").append('<li style="text-transform:capitalize" class="list-group-item">' + company.name + ' <span style="cursor:pointer" onclick="deleteFavoriteCompany(' + company.id + ')" class="badge">X</span></li>');

                });
            }
        }
    });

}

//delete requested companies

function deleteRequestedCompany(id) {
    //alert(id);
    var data = "id=" + id;
    $.ajax({
        type: "get",
        url: baseurl + '/delete-requested-company',
        data: data + "&_token=" + _token,
        success: function(data) {
            // alert(data.length+"-"+data[0].name);
            $("#requested_companies").empty();
            if (data.length > 0) {

                $.each(data, function(index, company) {

                    $("#requested_companies").append('<li style="text-transform:capitalize" class="list-group-item">' + company.company_text_name + ' <span style="cursor:pointer" onclick="deleteRequestedCompany(' + company.id + ')" class="badge">X</span></li>');

                });
            }
        }
    });
}

$(function() {
    $('#btn_select_all').click(function() {

    });
});

if (new_opening_report) $("#new_opening_report").prop('checked', true).change();
if (hiring_manager_report) $("#hiring_manager_report").prop('checked', true).change();
if (high_value_role_report) $("#high_value_role_report").prop('checked', true).change();





if ($("input[name*='up_']").attr("checked") === "checked") {

    var btn = $.trim($('#btn_select_all').text());

    if (btn === "Select All") {
        $('#btn_select_all').text("Unselect All");
    } else {

        $('#btn_select_all').text("Select All");
    }

}


$("#new_opening_report,#hiring_manager_report,#high_value_role_report").on("click", function(event) {

    var data = $('#' + $(this).attr("id")).is(":checked");
    if (data) data = 1;
    else data = 0;

    $.ajax({
        type: "get",
        url: baseurl + '/update-user-setting',
        data: $(this).attr("id") + "=" + data + "&_token=" + _token,
        success: function(data) {

            //if (data) window.location.reload(true);
            alert("Successfully Updated");
        }
    });
});


function sendCode(action) {

    var data = $("#account-info-form").serialize();
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/account-info',
        data: data + "&action=" + action + "&_token=" + _token,
        success: function(data) {

            if (data.code) {
                $("#code").val(data.code);
                $("#send_code").hide();
                $("#confirm_code").removeClass("hide");
                //open text for typing code 
                $("#confirmcode").removeAttr("disabled");

            }
            $("#loading").hide();
        }
    });

}

function confirmCode() {

    if ($("#code").val().trim() === $("#confirmcode").val().trim()) {
        $("#confirmcode").removeAttr("disabled");
        //$("#send_code").hide();
        $("#confirm_code").addClass("hide");
        $("#email").removeAttr("disabled");
        $("#name").removeAttr("disabled");
        $("#submit").removeAttr("disabled");
        $("#confirmcode").addAttr("disabled");

    } else alert("Code is incorrect!!!");
}


$("[name*='up_']").on("click", function() {

    // $("#loading").show();
    var data = $("#user-prefrences-form").serialize();
    $.ajax({
        type: "post",
        url: baseurl + '/save-user-preferences',
        data: data + "&_token=" + _token,
        success: function(data) {

            //$("#loading").hide();
            if (data.success) {
                /*$("input[name*='up_']").prop("checked", false);
                $.each(data.user_preferences, function(k, v) {
                    $("#up_" + v.job_type_id + "_" + v.location_id).prop("checked", true);
                });
                */
                event.preventDefault();
            }


        }
    });

});


function toggleSubTypes(parentId) {
    if ($("input[id*='" + parentId + "']").prop("checked"))
        $("input[id*='" + parentId + "_']").prop("checked", true);
    else $("input[id*='" + parentId + "_']").prop("checked", false);
}

function checkSubTypeEmpty(parentId) {

    var checked_length = parseInt($("input[id*='" + parentId + "_']:checked").length);
    console.log(checked_length);
    if (!checked_length)
        $("input[id*='" + parentId + "']").prop("checked", false);

}