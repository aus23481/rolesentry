$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
        }
    });


});

localStorage.current_page = 1;
localStorage.current_page_loc = 1;
localStorage.current_page_ss = 1;
localStorage.current_page_ssoeh = 1;
localStorage.current_page_approvals = 1;
selected_values = [];

/**********Platform************* */

$("[name*='job_type'], [name*='subtype'], [name*='location'], [name*='has_hiring_manager'], [name*='editor_mode'], [name*='needs_approval'], [name*='next_email'], [name*='banned'], [name*='favorites_only']").on("click", function() {
    clear_search();
    if (_platform == "opening") searchPlatform(1);
    if (_platform == "hiring-manager") getHiringManagers();
    //alert("hi");

});

//ensure delegate location event trigger

function checkPlatform() {

    if (_platform == "opening") searchPlatform(1);
    if (_platform == "hiring-manager") getHiringManagers();

}

function getCheckBoxSelectedValues() {

    $("#selected_values").val("");
    //location
    /*$('input[name*="location"]:checked').each(function(index) {
        console.log(this.value);
        //alert(this.value);
        if (index == 0)
            $("#selected_values").val(this.value);
        else $("#selected_values").val($("#selected_values").val() + "," + this.value);

    }); */


}


$(document).on("click", 'input[name*="location"]', function() {
    console.log(this.value);
    if ($(this).is(":checked")) {
        var index = selected_values.indexOf(parseInt(this.value));
        if (index < 0)
            selected_values.push(parseInt(this.value));
    } else {
        var index = selected_values.indexOf(parseInt(this.value));
        if (index > -1) selected_values.splice(index, 1);
    }
    console.log(selected_values);
});


function restoreCheckBoxSelectedValues(locations) {

    ///*var array = $("#selected_values").val().split('","');
    for (var i = 0; i < selected_values.length; i++) {
        //alert(array[i]);
        triggerLocation(parseInt(selected_values[i]));
    } //*/
    //alert(locations.length);
    /*$.each(locations, function(indexKey, location) {
        console.log(location);
        triggerLocation(parseInt(location));
    }); */

}

function triggerJobType(job_type_id) {

    if ($("[name*='job_type'][value='" + job_type_id + "']").prop("checked")) return false;
    else $("[name*='job_type'][value='" + job_type_id + "']").trigger("click");
}

function triggerLocation(location_id) {

    if ($("[name*='location'][value='" + location_id + "']").prop("checked")) return false;
    else $("[name*='location'][value='" + location_id + "']").trigger("click");
}

$(document).on("click", "[name*='is_']", function() {
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/saved-search-update',
        data: "checked=" + $(this).is(":checked") + "&name=" + $(this).attr("name") + "&id=" + $(this).attr("value") + "&_token=" + _token,
        success: function(data) {

            //if (data.success) console.log("Successfully Saved!!!");
            $("#loading").hide();
        }
    });
});

var xhrCount = 0;
var ajaxRequest;
var timer;

var xhrCountLoc = 0;
var ajaxRequestLoc;
var timerLoc;



function searchPlatform(page) {

    if (_platform !== "opening") return false;

    var data = $("#platform-form").serialize();
    if (!page || page === undefined) page = 1;

    var in_editor_mode = $("#editor_mode").is(':checked') == true ? true : false;
    var in_needs_approval_mode = $("#needs_approval").is(':checked') == true ? true : false;
    var in_next_email_mode = $("#next_email").is(':checked') == true ? true : false;
    var seqNumber = ++xhrCount;
    console.log(page, data);


    //$("#loading").show();
    loadingShow();

    if (ajaxRequest) {
        ajaxRequest.abort();
    }

    clearTimeout(timer);
    timer = setTimeout(function() {

            ajaxRequest = $.ajax({
                type: "get",
                url: baseurl + '/platform-api?alert=' + page,
                data: data,
                success: function(res) {


                    if (seqNumber == xhrCount) {
                        // loc count 
                        if (res.opening_location_counts.length > 0) {
                            $("[id^='span_location_count']").html("");
                            $.each(res.opening_location_counts, function(location, count) {
                                if (!isNaN(count)) $("#span_location_count-" + location).html(count);
                            });
                        }
                        // loc count 
                        //alert(res);
                        var table_html_str = "";
                        if (res.alerts.total >= 1) {
                            var table_html_str = "";
                            $favorite_bg_color = "";
                            $favorite_icon_class = "fa fa-star-o"; //for action column
                            $favorite_icon_class_company = "fa fa-star-o";
                            $favorite_icon_class_hiring_manager = "fa  fa-star-o";

                            $.each(res.alerts.data, function(k, opening) {

                                //favorite bg and icon control
                                var fid = parseInt(opening.sl);
                                var hiring_manager_id = parseInt(opening.hiring_manager_id);
                                var rolesentry_company_id = parseInt(opening.rolesentry_company_id);

                                if (res.favorites[fid] && res.favorites[fid] === opening.sl) {
                                    $favorite_bg_color = "#fdedd7";
                                    $favorite_icon_class = "fa fa-star";
                                } else {
                                    $favorite_bg_color = "";
                                    $favorite_icon_class = "fa fa-star-o";
                                }
                                //favorite bg and icon control
                                //company favorite icon class

                                if (res.favorites_company[rolesentry_company_id] && res.favorites_company[rolesentry_company_id] === opening.rolesentry_company_id)
                                    $favorite_icon_class_company = "fa fa-star";
                                else $favorite_icon_class_company = "fa fa-star-o";
                                //hiring manager favorite icon control

                                if (res.favorites_hiring_manager[hiring_manager_id] && res.favorites_hiring_manager[hiring_manager_id] === opening.hiring_manager_id)
                                    $favorite_icon_class_hiring_manager = "fa fa-star";
                                else $favorite_icon_class_hiring_manager = "fa fa-star-o";



                                table_html_str += '<tr id="ps-tr-' + opening.sl + '" style="background-color:' + $favorite_bg_color + '">';
                                if (opening.job_description_url) {
                                    //                                    table_html_str += '<td style="width:500px;"> <button onclick="triggerJobType(' + opening.job_type_id + ')"   class="btn btn-primary" type="button">' + opening.job_type + '</button> &nbsp; <a class="platform_search_result_title" target="_blank" href="' + opening.job_description_url + '">' + opening.title + '</a>';
                                    table_html_str += '<td style="width:350px;"><a class="platform_search_result_title" target="_blank" href="' + opening.job_description_url + '">' + opening.title + '</a>';
                                    if (res.user_type == 1 || res.user_type == 3) {
                                        table_html_str += '<br> ' + opening.job_description_url.substring(0, 50);
                                    }
                                    table_html_str += '</td>';
                                } else {
                                    table_html_str += '<td style="width:350px;"> <a class="platform_search_result_title" target="_blank" href="' + opening.job_description_url_on_job_board + '">' + opening.title + '</a></td>';
                                }
                                if (opening.logo_url == '0')
                                    table_html_str += '<td style="width:300px; text-transform:capitalize;"> <img style="padding-right:10px;width:25px" src="https://logo.clearbit.com/hpe.com"> ' + opening.company + ' <a onclick="addUserFavoriteItem(' + opening.rolesentry_company_id + ', 1, ' + opening.sl + ')" style="cursor:pointer"> <i id="ic-' + opening.sl + '" onclick="$(this).toggleClass(\'fa-star fa-star-o\');" class="' + $favorite_icon_class_company + '"></i> </a> </td>';
                                else
                                    table_html_str += '<td style="width:300px; text-transform:capitalize;"> <img style="padding-right:10px;width:25px" src="' + opening.logo_url + '"> ' + opening.company + ' <a onclick="addUserFavoriteItem(' + opening.rolesentry_company_id + ', 1, ' + opening.sl + ')" style="cursor:pointer"> <i id="ic-' + opening.sl + '" onclick="$(this).toggleClass(\'fa-star fa-star-o\');" class="' + $favorite_icon_class_company + '"></i> </a></td>';

                                table_html_str += '<td style="width:250px" > ';
                                table_html_str += '<button onclick="triggerJobType(' + opening.job_type_id + ')" style="background-color:#c2c9d4;color:white;margin-top:2px;margin-bottom:2px"  class="btn btn-xs" type="button">' + opening.job_type + '</button> &nbsp;';


                                table_html_str += '<button onclick="triggerLocation(' + opening.location_id + ')" style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px"   class="btn btn-xs" type="button">' + opening.location + '</button> </td>';
                                if (opening.hiring_manager_name) {
                                    table_html_str += '<td style="width:200px" ><a target="_blank" href="' + opening.hiring_manager_linkedin + '"><img class="img-linkedin" style="margin-right: 10px;margin-bottom:4px;width: 20px;height: 20px;" src="http://recruiterintel.com/Linkedin.png"></a><a target="_blank" href="' + opening.hiring_manager_linkedin + '">' + opening.hiring_manager_name + '</a> <a onclick="addUserFavoriteItem(' + opening.hiring_manager_id + ', 2, ' + opening.sl + ')" style="cursor:pointer" > <i onclick="$(this).toggleClass(\'fa-star fa-star-o\');" class="' + $favorite_icon_class_hiring_manager + '"></i> </a><br><span style="margin-top:2px;font-size:xx-small;color:#6d7a92">' + opening.hiring_manager_position + '</span>  </td>';
                                } else {
                                    if (in_editor_mode || in_needs_approval_mode) {
                                        table_html_str += '<td style="width:300px" >' + opening.manager_auto_detect + '</td>';
                                    } else {
                                        table_html_str += '<td style="width:300px" >...</td>';
                                    }
                                }
                                table_html_str += '<td >' + opening.time_ago + '</td>';


                                if (res.user_type == 1 || res.user_type == 3) {

                                    table_html_str += "<td>";

                                    if (!in_next_email_mode) {
                                        table_html_str += '<a title="Delete" onclick="deleteOpeningItem(' + opening.sl + ');" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                                    }


                                    table_html_str += '<a title="Edit" onclick="loadOpeningItem(' + opening.sl + ');"  data-toggle="modal" data-target="#platformEditModal" href="#platformEditModal" > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> | ';

                                    if (!in_next_email_mode && !in_needs_approval_mode) {
                                        table_html_str += '<a title="Hide" onclick="hideOpeningItem(' + opening.sl + ');" style="cursor:pointer"> <i class="fa fa-eye-slash" aria-hidden="true"></i> </a>';
                                    }

                                    if (!in_next_email_mode && !in_needs_approval_mode) {
                                        table_html_str += ' | <a title="Ban" onclick="loadOpeningBanItem(' + opening.sl + ');"  data-toggle="modal" data-target="#platformEditModalBan" href="#platformEditModalBan"> <i class="fa fa-hand-grab-o" aria-hidden="true"></i> </a>   ';
                                    }

                                    if (in_next_email_mode) {
                                        table_html_str += ' | <a title="Delete From Next Email" onclick="deleteFromNextEmail(' + opening.sl + ');" style="cursor:pointer"><i class="fa fa-minus-square"></i></a>';
                                    }

                                    if (res.user_type == 1) {

                                        if (in_needs_approval_mode) {
                                            table_html_str += ' | <a title="Approve" onclick="approveOpeningItem(' + opening.sl + ', 1);" style="cursor:pointer"><i class="fa fa-check-circle" aria-hidden="true"></i></a>';
                                            table_html_str += ' | <a title="Reject" onclick="approveOpeningItem(' + opening.sl + ', 2);" style="cursor:pointer"><i class="fa fa-times-circle" aria-hidden="true"></i></a>';
                                        }
                                    }
                                    //admin 1 or 3
                                    table_html_str += '|<a onclick="$(\'#opening_id_email\').val(' + opening.sl + ');"  data-toggle="modal" data-target="#platformEmailModal" href="#platformEmailModal" style="cursor:pointer"> <i class="fa fa-envelope"></i> </a>  |<a onclick="addUserFavoriteItem(' + opening.sl + ', 4, ' + opening.sl + ')" style="cursor:pointer"> <i onclick="$(this).toggleClass(\'fa-star fa-star-o\'); if($(this).hasClass(\'fa-star\')) $(\'#ps-tr-' + opening.sl + '\').css(\'background-color\',\'#fdedd7\'); else $(\'#ps-tr-' + opening.sl + '\').css(\'background-color\',\'\'); "  onclick="$(this).toggleClass(\'fa-star fa-star-o\');" class="' + $favorite_icon_class + '"></i> </a> ';
                                    table_html_str += '</td>';
                                } else {
                                    //non admin 2
                                    table_html_str += '<td >';
                                    //table_html_str += ' <a onclick="$(\'#opening_id_email\').val(' + opening.sl + ');"  data-toggle="modal" data-target="#platformEmailModal" href="#platformEmailModal" style="cursor:pointer"> <i class="fa fa-envelope"></i> </a> ';
                                    table_html_str += '<a title="Hide" onclick="hideOpeningItem(' + opening.sl + ');" style="cursor:pointer"> <i class="fa fa-eye-slash" aria-hidden="true"></i> </a> |<a onclick="addUserFavoriteItem(' + opening.sl + ', 4, ' + opening.sl + ')" style="cursor:pointer"> <i onclick="$(this).toggleClass(\'fa-star fa-star-o\'); if($(this).hasClass(\'fa-star\')) $(\'#ps-tr-' + opening.sl + '\').css(\'background-color\',\'#fdedd7\'); else $(\'#ps-tr-' + opening.sl + '\').css(\'background-color\',\'\'); " class="' + $favorite_icon_class + '"></i> </a>';
                                    table_html_str += '</td>';
                                }
                                table_html_str += '</tr>';
                            });

                            $("#table_platform_search_result").find("tr:gt(0)").remove();
                            $("#table_platform_search_result").append(table_html_str);

                            //$("#loading").hide();

                            //making bold
                            makeSearchTextBold();
                            //paginate
                            //setPagination(res.alerts);

                            track_user_action(1);
                            setPagination(res.alerts);
                            loadSavedSearches(1);

                        } else {
                            //No results found, please make your search more general
                            table_html_str += '<tr> <td colspan=6 style="text-align:center">No results found, please make your search more general</td></tr>';
                            $("#table_platform_search_result").find("tr:gt(0)").remove();
                            $("#loading").hide();
                            $("#table_platform_search_result").append(table_html_str);

                            // $("#loading").hide();

                            //paginate
                            //setPagination(res.alerts);
                            //
                            loadSavedSearches(1);
                        }

                    }

                }
            });
        },
        1
    );
}
//toggle favorites icons
function toggleFavoriteIcons(sl) {

    //var id = "ic-" + sl;
    // $("#" + id).toggleClass('fa-star fa-star-o');
}

function deleteOpeningItem(id) {
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-delete',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) searchPlatform(1);
            $("#loading").hide();
        }
    });

}

function hideOpeningItem(id) {
    // $("#loading").show();
    $("#ps-tr-" + id).hide();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-hide',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) $("#ps-tr-" + id).remove();
            //searchPlatform(localStorage.current_page);
            // $("#loading").hide();
        }
    });
}



function deleteFromNextEmail(id) {
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-remove-next-email',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) searchPlatform(localStorage.current_page);
            $("#loading").hide();
        }
    });
}


function banOpeningUrl(id) {
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-ban',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) searchPlatform(localStorage.current_page);
            $("#loading").hide();
        }
    });

}

function deleteOpeningItem(id) {
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-delete',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) searchPlatform(localStorage.current_page);
            $("#loading").hide();
        }
    });
}


function approveOpeningItem(id, action) {
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-approve',
        data: "action=" + action + "&id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) searchPlatform(localStorage.current_page);
            $("#loading").hide();
        }
    });
}



function editOpeningItem(id) {

    $("#loading").show();
    var data = $("#platform-edit-form").serialize();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-edit',
        data: data + "&id=" + id + "&_token=" + _token,
        success: function(res) {
            $('#platformEditModal').modal('hide');
            if (res.success) searchPlatform(localStorage.current_page);
            $("#loading").hide();
        }
    });

}

function resetOpeningEditModal() {

    $("#opening_id").val("");
    $("#showopening_id").val("");
    $("#title").val("");

    $("#human_readable_job_title").val("");
    $("#human_readable_company_name").val("");

    $("#rolesentry_company_id").val("");
    $("#location_id").val("");
    $("#job_type_id").val("");
    $("#hiring_manager_hint").html("");
    $("#intel").val("");
    for (var hm_num = 1; hm_num <= 10; hm_num++) {
        $("#hiring_manager_id" + hm_num).val("");
        $("#hiring_manager_name" + hm_num).val("");
        $("#hiring_manager_phone" + hm_num).val("");
        $("#hiring_manager_certainty" + hm_num).val("");
        $("#hiring_manager_email" + hm_num).val("");

        $("#hiring_manager_position" + hm_num).val("");
        $("#hiring_manager_linkedin" + hm_num).val("");
    }

}

function loadOpeningItem(id) {

    //alert(id);
    // /* 
    resetOpeningEditModal();
    $("#loading").show();
    $("#hm_names").hide();
    var data = $("#platform-form").serialize();
    $.ajax({
        type: "get",
        url: baseurl + '/load-platform-edit',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            //if (res.success) searchPlatform(1)n;
            $("#loading").hide();
            //alert(res.id);
            $("#opening_id").val(res.opening.id);
            $("#showopening_id").val(res.opening.id);
            $("#title").val(res.opening.title);

            $("#human_readable_job_title").val(res.opening.human_readable_job_title);
            $("#human_readable_company_name").val(res.opening.human_readable_company_name);

            $("#rolesentry_company_id").val(res.opening.rolesentry_company_id);
            $("#location_id").val(res.opening.location_id);
            $("#job_type_id").val(res.opening.job_type_id);
            $("#hiring_manager_hint").html(res.opening.manager_auto_detect);
            $("#intel").val(res.opening.intel);

            // $("#hiring_manager_name").val(res.opening.hiring_manager_name);
            // $("#hiring_manager_phone").val(res.opening.hiring_manager_phone);
            // $("#hiring_manager_email").val(res.opening.hiring_manager_email);
            // $("#hiring_manager_percent").val(res.opening.hiring_manager_percent);
            // $("#hiring_manager_position").val(res.opening.hiring_manager_position);
            // $("#hiring_manager_linkedin").val(res.opening.hiring_manager_linkedin);
            loadJobSubTypes();

            //load hms into modal
            //console.log('more than one hm, loading');


            $('#sections').empty();

            console.log('empty');



            if (res.opening.hiring_manager_openings.length > 0) {
                var hm_num = 1;
                console.log(res.opening.hiring_manager_openings.length);
                //hm form generation
                //                if (res.opening.hiring_manager_openings.length > 1) {
                //alert(res.opening.hiring_manager_openings.length);

                sectionsCount = 0;
                for (var f = 0; f < res.opening.hiring_manager_openings.length;) {
                    //alert(f);

                    $(".addsection").trigger("click");
                    console.log("section-created::" + f);
                    f++;
                }
                //              }
                var count = 0;
                console.log(res.opening.hiring_manager_openings);
                $.each(res.opening.hiring_manager_openings, function(k, hm) {
                    //alert(k + ":" + hm.hiring_manager.name);
                    if (k > 0) hm_num = k + 1;


                    console.log('doing section' + hm_num);

                    if (hm.hiring_manager.id) {

                        //setTimeout(function() {


                        hm_certainty = hm.certainty;

                        console.log(hm_certainty)
                        console.log('hmnum:' + hm_num + "--" + hm.hiring_manager.id + " certin: " + hm_certainty);

                        $("#hiring_manager_id" + hm_num).val(hm.hiring_manager.id);
                        $("#hiring_manager_name" + hm_num).val(hm.hiring_manager.name);
                        $("#hiring_manager_phone" + hm_num).val(hm.hiring_manager.phone);
                        $("#hiring_manager_email" + hm_num).val(hm.hiring_manager.email);
                        $("#hiring_manager_certainty" + hm_num).val(hm_certainty);
                        $("#hiring_manager_position" + hm_num).val(hm.hiring_manager.title);
                        $("#hiring_manager_linkedin" + hm_num).val(hm.hiring_manager.linkedin_url);
                        //alert(hm_num + "::" + hm.hiring_manager.name + ":" + $("#hiring_manager_id" + hm_num).val());

                        //}, 500);

                    } //else $("#delete" + hm_num).trigger("click");
                    //if (!$("#hiring_manager_id" + hm_num).val()) $("#delete" + hm_num).trigger("click");
                    count++;

                });

                $("input[id*='hiring_manager_id']").each(function() {
                    var id = $(this).attr("id");
                    console.log('triggering deleting ' + id)
                    var num = id.replace(/^\D+/g, '');
                    var val = $(this).val();
                    if (!val && !$("#hiring_manager_id" + num).val()) {
                        //     $("#delete" + num).trigger("click");
                        console.log("Deleted::" + id + ":" + count);
                    }
                    if (val != $("#hiring_manager_id" + num).val()) {
                        //  $("#delete" + num + ":eq(1)").trigger("click");
                        //$("[dataindex='delete" + num + "']:eq(1)").trigger("click");
                        console.log("Deleted(eq1)::" + id + ":" + count);
                    }
                    // alert(id + "::" + val);
                    console.log(id + "::" + val + "::" + $("#hiring_manager_id" + num).val() + ":" + count);
                });
            } //end hms loading into modal

            //disabled if company has more hiring managers

            if (res.openings) {
                if (res.openings.length > 1) {
                    $hm_names = "";
                    // $("#hm_names").show();
                    $.each(res.openings, function(k, opening) {

                        if ($.trim(opening.hiring_manager_name)) $hm_names += '<div style="padding-bottom:10px"><button  onclick="$(\'#hiring_manager_linkedin\').val(\'' + opening.hiring_manager_linkedin + '\');$(\'#hiring_manager_name\').val(\'' + opening.hiring_manager_name + '\');$(\'#hiring_manager_position\').val(\'' + opening.hiring_manager_position + '\')"    type="button" class="btn btn-primary">' + opening.hiring_manager_name + " - " + opening.hiring_manager_position + '</button > &nbsp; &nbsp; <a target="_blank" href=\'' + opening.hiring_manager_linkedin + '\'><img style="width:35px;height:32px" src="http://recruiterintel.com/Linkedin.png"></a>  </div>';
                    });

                    // $("#other_hiring_managers").html($hm_names);
                }
            }
            //
        }
    });

}


function loadOpeningBanItem(id) {

    //alert(id);
    // /* 
    $("#loading").show();
    var data = $("#platform-form").serialize();
    $.ajax({
        type: "get",
        url: baseurl + '/load-platform-edit',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            //if (res.success) searchPlatform(1);
            $("#loading").hide();
            //alert(res.id);
            $("#opening_id_ban").val(res.id);
            $("#title_ban").val(res.title);
            $("#company_ban").val(res.company.name);
            $("#url_ban").val(res.url_for_ban);

        }
    });
}

function banOpeningItem(action) {

    $("#loading").show();
    var data = $("#platform-edit-form-ban").serialize();
    $.ajax({
        type: "get",
        url: baseurl + '/platform-ban',
        data: data + "&action=" + action + "&_token=" + _token,
        success: function(res) {

            if (res.success) alert("Successfully Banned");
            else alert(res.error_message);


            searchPlatform(localStorage.current_page);

        }
    });
}


function saveSearchItem() {

    $("#loading").show();
    var jobtype_length = parseInt($("input[name*='job_type']:checked").length);
    var location_length = parseInt($("input[name*='location']:checked").length);

    if (!jobtype_length) {
        alert("Please select at least one job type.");
        return false;
    }

    if (!location_length) {
        alert("Please select at least one location.");
        return false;
    }

    if ($("#search").val() !== "") {
        var data = $("#platform-form").serialize();
        $.ajax({
            type: "get",
            url: baseurl + '/save-search',
            data: data,
            success: function(res) {


                // if (res.success) alert("Successfully Saved");
                //getSavedSearchItems();
                loadSavedSearches(1);
                $("#loading").hide();

            }

        });
    } else alert("Please enter text in the \"Search Openings\" textbox.  This will be the text in newly opened job titles which will trigger automation.  Ex: \"Software Engineer\"");

}


function deleteSavedSearchItem(id) {
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

function getSavedSearchItems() {
    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/get-saved-search-items',
        data: "_token=" + _token,
        success: function(res) {
            if (res.success) {
                table_html_str = '';

                var $favorite_html = '';
                $("#ul_saved_searches").html("");
                $.each(res.saved_search_terms, function(k, saved_search_item) {
                    $favorite_html += '<li><span class="glyphicon glyphicon-star"></span>' + saved_search_item.term + '<span onclick="deleteSavedSearchItem(' + saved_search_item.id + ')"    id="remove-item"><img src="images/remove.png"/></span></li>';
                });

                $("#ul_saved_searches").html($favorite_html);

                /*
                    $.each(res.saved_search_terms, function(k, saved_search_item) {
                        //alert(saved_search_item.term);

                        var fid = parseInt(saved_search_item.id);

                        if (res.favorites[fid] && res.favorites[fid] === saved_search_item.id) {
                            //$favorite_bg_color = "#fdedd7";
                            $favorite_icon_class = "fa fa-star-o";
                        } else {
                            //$favorite_bg_color = "";
                            $favorite_icon_class = "fa fa-star";
                        }


                        table_html_str += '<tr id="sstr-' + saved_search_item.id + '" >';
                        //1
                        table_html_str += '<td> <a onclick="deleteSavedSearchItem(' + saved_search_item.id + ')"  style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp; ' + saved_search_item.term + '  <a onclick="addUserFavoriteItem(' + saved_search_item.id + ', 3, ' + opening.sl + ')" style="cursor:pointer"> <i class="' + $favorite_icon_class + '"></i> </a> </td>';

                        //2
                        table_html_str += '<td><label class="check-container">';
                        if (saved_search_item.is_daily == 1)
                            table_html_str += '<input  checked  class="job-type-btn" name="is_daily" type="checkbox" value="' + saved_search_item.id + '">';

                        else table_html_str += '<input    class="job-type-btn" name="is_daily" type="checkbox" value="' + saved_search_item.id + '">';

                        table_html_str += ' <span class = "checkmark" > </span>';
                        table_html_str += ' </label> </td > ';

                        //3

                        table_html_str += '<td><label class="check-container">';
                        if (saved_search_item.is_instant == 1)
                            table_html_str += '<input  checked  class="job-type-btn" name="is_instant" type="checkbox" value="' + saved_search_item.id + '">';

                        else table_html_str += '<input    class="job-type-btn" name="is_instant" type="checkbox" value="' + saved_search_item.id + '">';

                        table_html_str += ' <span class = "checkmark" > </span>';
                        table_html_str += ' </label> </td > ';


                        table_html_str += '</tr>';
                        
                });
            //alert(table_html_str);
            $("#table_saved_search").find("tr:gt(0)").remove();
            $("#table_saved_search").append(table_html_str);
            */

                $("#loading").hide();
            }
        }
    });

}

if (_platform == "opening")
    searchPlatform(1);

function setPagination(data) {
    //data.total
    //data.to
    //data.prev_page_url
    //data.per_page
    //data.path
    //data.next_page_url
    //data.last_page_url
    //data.last_page
    //data.from
    //data.first_page_url
    //data.current_page	
    var page_html = "";
    var disabled = "";
    var active = "";
    localStorage.current_page = data.current_page;
    // alert(data.total);
    if (data.total < 50) {
        $("#pagination").hide();
        return false;
    } else $("#pagination").show();

    if (data.current_page === 1)
        page_html += '<li id="prev" class="disabled"><span>Previous</span></li>';
    else page_html += '<li id="prev" ><a onclick="searchPlatform(' + (data.current_page - 1) + ')" style="cursor:pointer"><span>Previous</span></a></li>';

    if (data.last_page > 30) {
        data.last_page = 30;
    }

    for (var page = 1; page <= data.last_page; page++) {
        if (data.current_page === page)
            page_html += '<li class="active"><span>' + page + '</span></li>';
        else
            page_html += '<li><a onclick="searchPlatform(' + page + ')" style="cursor:pointer">' + page + '</a></li>';
    } //end for

    if (data.current_page === data.last_page)
        page_html += '<li class="disabled" id="next"><a  style="cursor:pointer" rel="next">Next</a></li>';
    else page_html += '<li  id="next"><a onclick="searchPlatform(' + (data.current_page + 1) + ')" style="cursor:pointer" rel="next">Next</a></li>';

    //alert(page_html);
    //$("#loading").hide();
    $("#pagination").html(page_html);


}


function setLocationPagination(data) {
    //data.total
    //data.to
    //data.prev_page_url
    //data.per_page
    //data.path
    //data.next_page_url
    //data.last_page_url
    //data.last_page
    //data.from
    //data.first_page_url
    //data.current_page	
    var page_html = "";
    var disabled = "";
    var active = "";
    //alert(data.total);
    localStorage.current_page_loc = data.current_page;
    // alert(data.total);
    if (data.total < 10) {
        $("#loc-pagination").hide();
        return false;
    } else $("#loc-pagination").show();

    if (data.current_page === 1)
        page_html += '<li id="prev" class="disabled"><span>Previous</span></li>';
    else page_html += '<li id="prev" ><a onclick="searchLocation(' + (data.current_page - 1) + ')" style="cursor:pointer"><span>Previous</span></a></li>';

    if (data.last_page > 10) {
        data.last_page = 10;
    }

    for (var page = 1; page <= data.last_page; page++) {
        if (data.current_page === page)
            page_html += '<li class="active"><span>' + page + '</span></li>';
        else
            page_html += '<li><a onclick="searchLocation(' + page + ')" style="cursor:pointer">' + page + '</a></li>';
    } //end for

    if (data.current_page === data.last_page)
        page_html += '<li class="disabled" id="next"><a  style="cursor:pointer" rel="next">Next</a></li>';
    else page_html += '<li  id="next"><a onclick="searchLocation(' + (data.current_page + 1) + ')" style="cursor:pointer" rel="next">Next</a></li>';

    //alert(page_html);
    //$("#loading").hide();
    $("#loc-pagination").html(page_html);
    // getCheckBoxSelectedValues();

}

//saved search prospecting automation template pagination

function setSavedSearchPagination(data) {
    //data.total
    //data.to
    //data.prev_page_url
    //data.per_page
    //data.path
    //data.next_page_url
    //data.last_page_url
    //data.last_page
    //data.from
    //data.first_page_url
    //data.current_page	
    var page_html = "";
    var disabled = "";
    var active = "";
    //alert(data.total);
    localStorage.current_page_ss = data.current_page;
    //alert(data.total);
    if (data.total < 5) {
        $("#ss-pagination").hide();
        return false;
    } else $("#ss-pagination").show();

    if (data.current_page === 1)
        page_html += '<li id="prev" class="disabled"><span>Previous</span></li>';
    else page_html += '<li id="prev" ><a onclick="loadSavedSearches(' + (data.current_page - 1) + ')" style="cursor:pointer"><span>Previous</span></a></li>';

    if (data.last_page > 10) {
        data.last_page = 10;
    }

    for (var page = 1; page <= data.last_page; page++) {
        if (data.current_page === page)
            page_html += '<li class="active"><span>' + page + '</span></li>';
        else
            page_html += '<li><a onclick="loadSavedSearches(' + page + ')" style="cursor:pointer">' + page + '</a></li>';
    } //end for

    if (data.current_page === data.last_page)
        page_html += '<li class="disabled" id="next"><a  style="cursor:pointer" rel="next">Next</a></li>';
    else page_html += '<li  id="next"><a onclick="loadSavedSearches(' + (data.current_page + 1) + ')" style="cursor:pointer" rel="next">Next</a></li>';

    //alert(page_html);
    //$("#loading").hide();
    $("#ss-pagination").html(page_html);

}


//saved search prospecting automation template pagination

function setApprovalsPagination(id, data) {

    var page_html = "";
    var disabled = "";
    var active = "";
    //alert(data.total);
    console.log(id, data);
    localStorage.current_page_approvals = data.current_page;


    //alert(data.total);
    if (data.total < 5) {
        $("#approvals-pagination").hide();
        return false;
    } else $("#approvals-pagination").show();

    if (data.current_page === 1)
        page_html += '<li id="prev" class="disabled"><span>Previous</span></li>';
    else page_html += '<li id="prev" ><a onclick="loadApprovalModal(' + id + ',' + (data.current_page - 1) + ')" style="cursor:pointer"><span>Previous</span></a></li>';

    if (data.last_page > 10) {
        data.last_page = 10;
    }

    for (var page = 1; page <= data.last_page; page++) {
        if (data.current_page === page)
            page_html += '<li class="active"><span>' + page + '</span></li>';
        else
            page_html += '<li><a onclick="loadApprovalModal(' + id + ',' + page + ')" style="cursor:pointer">' + page + '</a></li>';
    } //end for

    if (data.current_page === data.last_page)
        page_html += '<li class="disabled" id="next"><a  style="cursor:pointer" rel="next">Next</a></li>';
    else page_html += '<li  id="next"><a onclick="loadApprovalModal(' + id + ',' + (data.current_page + 1) + ')" style="cursor:pointer" rel="next">Next</a></li>';

    //alert(page_html);
    //$("#loading").hide();
    $("#approvals-pagination").html(page_html);

}


function setSavedSearchEditHistoryPagination(id, data) {

    var page_html = "";
    var disabled = "";
    var active = "";
    //alert(data.total);
    console.log(data.total);
    localStorage.current_page_ssoeh = data.current_page;
    //alert(data.total);
    if (data.total < 5) {
        $("#ssoeh-pagination").hide();
        return false;
    } else $("#ssoeh-pagination").show();

    if (data.current_page === 1)
        page_html += '<li id="prev" class="disabled"><span>Previous</span></li>';
    else page_html += '<li id="prev" ><a onclick="loadSavedSearchEditHistory(' + id + ',' + (data.current_page - 1) + ')" style="cursor:pointer"><span>Previous</span></a></li>';

    if (data.last_page > 10) {
        data.last_page = 10;
    }

    for (var page = 1; page <= data.last_page; page++) {
        if (data.current_page === page)
            page_html += '<li class="active"><span>' + page + '</span></li>';
        else
            page_html += '<li><a onclick="loadSavedSearchEditHistory(' + id + ',' + page + ')" style="cursor:pointer">' + page + '</a></li>';
    } //end for

    if (data.current_page === data.last_page)
        page_html += '<li class="disabled" id="next"><a  style="cursor:pointer" rel="next">Next</a></li>';
    else page_html += '<li  id="next"><a onclick="loadSavedSearchEditHistory(' + id + ',' + (data.current_page + 1) + ')" style="cursor:pointer" rel="next">Next</a></li>';

    //alert(page_html);
    //$("#loading").hide();
    $("#ssoeh-pagination").html(page_html);

}

function makeSearchTextBold() {
    var search = $("#search").val();
    if (search != "") {
        $(".platform_search_result_title:contains('" + search + "')").each(function() {
            $(this).html(
                $(this).html().replace($("#search").val(), '<strong>$&</strong>')
            );
        });
    }
}






function addUserFavoriteItem(favoriteable_item_id, table_id, opening_id = null) {

    setTimeout(function() {
        clear_search();
    }, 100);

    $("#loading").show();
    $.ajax({
        type: "get",
        url: baseurl + '/add-user-favorite-item',
        data: "favoriteable_item_id=" + favoriteable_item_id + "&table_id=" + table_id + "&opening_id=" + opening_id + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                getUserFavorites(table_id);
                //if (table_id == 3) {
                //getSavedSearchItems();
                //} else 

                // searchPlatform(1);
            }
            $("#loading").hide();
        }
    });
}

function addToEmail() {

    var data = $("#platform-email-form").serialize();
    $("#loading").show();

    $.ajax({
        type: "get",
        url: baseurl + '/add-platform-item-email',
        data: data + "&_token=" + _token,
        success: function(res) {
            $("#platformEmailModal").modal("hide");
            $(window).scrollTop(0);
            if (res.success) {
                alert(res.message);
            } else alert(res.message);
            $("#loading").hide();
        }

    });
}




$('#btn_job_type_select_all').click(function() {

    //$.colorbox.resize({ width: "300px", height: "300px" });
    var btn = $.trim($('#btn_job_type_select_all').text());

    if (btn == "Check All") {
        $("input[name*='job_type']").prop("checked", true);
        $('#btn_job_type_select_all').text("Uncheck All");
    } else {
        $("input[name*='job_type']").prop("checked", false);
        $('#btn_job_type_select_all').text("Check All");
    }
    //*/
});

$('#btn_location_select_all').click(function() {

    var btn = $.trim($('#btn_location_select_all').text());

    if (btn == "Check All") {
        $("input[name*='location']").prop("checked", true);
        $('#btn_location_select_all').text("Uncheck All");
    } else {
        $("input[name*='location']").prop("checked", false);
        //$("input[name*='location']").removeAttr("checked");
        $('#btn_location_select_all').text("Check All");
    }
    //*/
});


$('.filter_check_all_btn').click(function() {

    var btn = $.trim($('.filter_check_all_btn').text());

    if (btn == "Check All") {
        $("[name*='has_hiring_manager'], [name*='editor_mode'], [name*='needs_approval'], [name*='next_email'], [name*='banned'], [name*='favorites_only']").prop("checked", true);
        $('.filter_check_all_btn').text("Uncheck All");
        event.preventDefault();
    } else {
        $("[name*='has_hiring_manager'], [name*='editor_mode'], [name*='needs_approval'], [name*='next_email'], [name*='banned'], [name*='favorites_only']").prop("checked", false);
        $('.filter_check_all_btn').text("Check All");
        event.preventDefault();
    }
    //*/
});


function clear_search() {
    $("#search").val('');
}

function track_user_action(interaction_id) {

    $("#loading").show();

    $.ajax({
        type: "get",
        url: baseurl + '/track-user-action',
        data: "user_interaction_id=" + interaction_id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                console.log("successfully loaded");
                //if (res.count < 2) introJs().start();
                //$("#platformInvoiceModal").modal("show");
            } else {
                //console.log("Error!!!");
                $("#platformInvoiceModal").modal("show");
            }
            $("#loading").hide();
        }
    });
}


$("#searchbox_hiring_manager").on("keyup", function() {
    if ($(this).val().length > 2) getUserFavoritesSearch(2);
});

$("#searchbox_company").on("keyup", function() {
    if ($(this).val().length > 2) getUserFavoritesSearch(1);
});

$("#searchbox_hiring_manager").on("keyup", function() {
    if ($(this).val().length == 0) getUserFavoritesSearch(2);
});

$("#searchbox_company").on("keyup", function() {
    if ($(this).val().length == 0) getUserFavoritesSearch(1);
});


function loadingShow() {

    var table_html_str = "";
    table_html_str += '<tr class="list-loader"> <td id="loading-td" colspan=6 style="padding: 0 !important">';
    table_html_str += '<div class="loading-img" style="text-align: center"><img width="130"  src="images/loading.gif"></div>';
    //table_html_str += '<span style="color:#FB8B14;font-weight:bold;text-align:center;clear:both"><br />Loading Results</span>';
    table_html_str += '</td></tr>';
    $("#table_platform_search_result").find("tr:gt(0)").remove();
    //$("#loading").hide();
    $("#table_platform_search_result").append(table_html_str);

}

function alertFrequency(frequency) {

    $.ajax({
        type: "get",
        url: baseurl + '/alert-frequency',
        data: "frequency=" + frequency + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                //console.log("successfully loaded");
            }
        }
    });
}

//alert frequency
$("#instant, #daily, #never").removeClass("active");
if (alert_frequency == 1) $("#instant").addClass("active");
if (alert_frequency == 2) $("#daily").addClass("active");
if (alert_frequency == 0) $("#never").addClass("active");

//search location

function searchLocation(page) {

    var search_location = $.trim($("#search_location").val());
    var seqNumber = ++xhrCountLoc;
    var data = $("#platform-form").serialize();
    if (ajaxRequestLoc) {
        ajaxRequestLoc.abort();
    }

    clearTimeout(timerLoc);
    timerLoc = setTimeout(function() {

        ajaxRequestLoc = $.ajax({
            type: "get",
            url: baseurl + '/search-location',
            data: data + "&search_location=" + search_location + "&_token=" + _token + "&locationpage=" + page,
            success: function(res) {

                if (seqNumber == xhrCountLoc) {
                    if (res.success) {

                        loc_html = "";
                        $.each(res.locations.data, function(indexKey, location) {

                            //alert(indexKey + "-" + location.name);
                            if (indexKey == 0) loc_html += '<div class="col-md-3">';
                            else if (indexKey == 3 || indexKey == 6 || indexKey == 9)
                                loc_html += '<div class = "col-md-3 hidden-xs hidden-sm" >';
                            loc_html += '<div class = "checkbox" >';
                            loc_html += '<label class = "check-container" >';
                            loc_html += '<input onClick="checkPlatform();searchPlatform(1);"  class = "job-type-btn" name = "location[]" type = "checkbox" value = "' + location.id + '">';
                            loc_html += '<span class = "checkmark" > </span>  ' + location.name + ' </label> </div >';

                            if (indexKey == 2 || indexKey == 5 || indexKey == 8 || indexKey == 11)
                                loc_html += '</div>';
                        });

                        loc_html += '</div>';


                        $("#location_container_row").html(loc_html);
                        //backup selected values for location
                        // getCheckBoxSelectedValues();
                        setLocationPagination(res.locations);
                        //restore selected values for location
                        restoreCheckBoxSelectedValues(res.locations_selected);

                    } // end of res. success
                } //seqn
            }
        });

    }); //timer
}

//default location pagination
searchLocation(1);


function loadJobSubTypes() {

    $.ajax({
        type: "get",
        url: baseurl + '/load-job-subtypes',
        data: "job_type_id=" + $("#job_type_id").val() + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                //console.log("successfully loaded");
                //alert("Successfully Upated!!!");
                $('#job_subtype_id').empty();

                $.each(res.job_subtypes, function(k, sub_type) {
                    $('#job_subtype_id')
                        .append($("<option></option>")
                            .attr("value", sub_type.id)
                            .text(sub_type.name));
                });


            }
        }
    });
}

//toggle subtypes

function toggleSubTypes(parentId) {
    //alert(parentId);
    if ($("input[id*='jobtype_" + parentId + "_']").prop("checked"))
        $("input[id*='subtype_" + parentId + "_']").prop("checked", true);
    else $("input[id*='subtype_" + parentId + "_']").prop("checked", false);
}

function checkSubTypeEmpty(parentId) {

    var checked_length = parseInt($("input[id*='subtype_" + parentId + "_']:checked").length);
    console.log(checked_length);
    if (!checked_length)
        $("input[id*='jobtype_" + parentId + "_']").prop("checked", false);
    else $("input[id*='jobtype_" + parentId + "_']").prop("checked", true);

}

toggleSubTypes(1);

//Loading saved search results

function loadSavedSearches(page) {

    var data = $("#platform-form").serialize();
    var location_length = parseInt($("input[name*='location']:checked").length);
    if (!location_length) triggerLocation(1);

    $.ajax({
        type: "get",
        url: baseurl + '/load-saved-searches',
        data: data + "&ss=" + page + "&_token=" + _token,
        success: function(res) {
            if (res.success) {

                var table_html_str = "";
                $.each(res.saved_searches.data, function(k, ss) {
                    //alert(saved_search.id);
                    table_html_str += '<tr>';

                    //1st
                    table_html_str += '<td>';
                    table_html_str += '<a style="cursor:pointer" onclick="triggerSavedSearch(\'' + ss.term + '\',' + ss.job_type_id + ', ' + ss.location_id + ')">';
                    table_html_str += ss.term; // + '(Opening Id:' + ss.opening_id + ')';
                    table_html_str += '</a>';
                    table_html_str += '</td>';

                    //2nd
                    table_html_str += '<td>';
                    table_html_str += '<button  style="background-color:#c2c9d4;color:white;margin-top:2px;margin-bottom:2px"  class="btn btn-xs" type="button">' + ss.job_type + '</button> &nbsp;';
                    table_html_str += '<button  style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px"   class="btn btn-xs" type="button">' + ss.location + '</button> </td>';
                    table_html_str += '</td>';

                    //3rd
                    table_html_str += '<td>' + (ss.last_email_sent ? ss.last_email_sent : 'Never');
                    table_html_str += '</td>';

                    //4th
                    table_html_str += '<td>' + (ss.emails_sent ? ss.emails_sent : 0);
                    table_html_str += '</td>';

                    //5th
                    table_html_str += '<td>';

                    table_html_str += '<a title="Delete" onclick="deleteSavedSearchItem(' + ss.id + ');" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                    table_html_str += '<a title="Edit" onclick="loadSavedSearchItem(' + ss.id + ', ' + ss.job_type_id + ', ' + ss.location_id + ');"  data-toggle="modal" data-target="#savedSearchEditModal" href="#savedSearchEditModal" > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                    table_html_str += '&nbsp;|&nbsp;<a title="Edit" onclick="loadSavedSearchEditHistory(' + ss.id + ', 1);"  data-toggle="modal" data-target="#savedSearchEditHistoryModal" href="#savedSearchEditHistoryModal" > <i class="fa fa-history" aria-hidden="true"></i></a>';
                    table_html_str += '&nbsp;|&nbsp;<a title="Edit" onclick="loadApprovalModal(' + ss.id + ', 1);"  data-toggle="modal" data-target="#approvalsModal" href="#approvalsModal" > <i class="fa fa-clipboard" aria-hidden="true"></i></a>';

                    table_html_str += '</td>';
                    table_html_str += '</tr>';

                }); //foreach

                $("#table_saved_search_result").find("tr:gt(0)").remove();
                $("#table_saved_search_result").append(table_html_str);
                setSavedSearchPagination(res.saved_searches);

            } //if seuccess
            else {
                table_html_str += '<tr><td style="text-align:center;font-weight:bold" colspan=5> You have not set up any automations for the above criteria yet.</td></tr>';
                $("#table_saved_search_result").find("tr:gt(0)").remove();
                $("#table_saved_search_result").append(table_html_str);
            } //else
        }
    });
}

//trigger search when term of saved search is clicked
function triggerSavedSearch(term, job_type_id, location_id) {
    console.log(term + ":" + job_type_id + ":" + location_id);
    $("#search").val(term);
    triggerJobType(job_type_id);
    triggerLocation(location_id);

    searchPlatform(1);
}

function deleteSavedSearchItem(id) {
    $("#loading").show();

    if (window.confirm("Are you sure to delete")) {
        $.ajax({
            type: "get",
            url: baseurl + '/delete-saved-search',
            data: "id=" + id + "&_token=" + _token,
            success: function(res) {
                if (res.success) loadSavedSearches(1);
                $("#loading").hide();
            }
        });
    }

}


function selectScheme(id, saved_search_id) {

    if (window.confirm("Are you sure you want to set default template, and erase current email text?")) {
        $.ajax({
            type: "get",
            url: baseurl + '/get-scheme',
            data: "id=" + id + "&saved_search_id=" + saved_search_id + "&_token=" + _token,
            success: function(res) {
                setSavedSearchSteps(res.scheme_steps);
            }
        });
    }

}

function setSavedSearchSteps(scheme_steps) {

    $('#scheme_steps').empty();
    console.log('123');

    try {
        if (scheme_steps.length && scheme_steps.length > 0) {
            schemeStepCount = 0;
            var scheme_step_num = 1;
            console.log('223');
            console.log(scheme_steps.length);
            //hm form generation
            //                if (res.opening.hiring_manager_openings.length > 1) {
            //alert(res.opening.hiring_manager_openings.length);

            schemeStepsCount = 0;
            for (var f = 0; f < scheme_steps.length;) {
                //alert(f);

                $(".addschemestep").trigger("click");
                console.log("scheme-step-section-created::" + f);
                f++;
            }
            //              }
            var count = 0;
            console.log(scheme_steps.length);
            $.each(scheme_steps, function(k, ss) {

                console.log('2342342343');
                //alert(k + ":" + hm.hiring_manager.name);
                if (k > 0) scheme_step_num = k + 1;

                console.log('doing scheme step' + scheme_step_num);

                if (ss.step.id) {

                    //setTimeout(function() {

                    console.log('ssnum:' + scheme_step_num + "--" + ss.step.id);

                    $("#scheme_step_id" + scheme_step_num).val(ss.step.id);

                    $("#scheme_step_email_body" + scheme_step_num).html(ss.step.email_body);
                    console.log('step email body to ' + ss.step.email_body);
                    $("#scheme_step_email_subject" + scheme_step_num).html(ss.step.email_subject);
                    $("#scheme_step_wait" + scheme_step_num).val(ss.step.wait);
                    console.log("((()))" + ss.locks[0].scheme_step_lock_type_detail_value + "  ((())))");
                    $("#lock_type_1" + scheme_step_num).val(ss.locks[0].scheme_step_lock_type_detail_value);
                    $("#lock_type_2" + scheme_step_num).val(ss.locks[1].scheme_step_lock_type_detail_value);
                    // $(document).find("br").parent("p").remove();

                    triggerDragObjects(scheme_step_num);


                } //else $("#delete" + hm_num).trigger("click");
                count++;

            });


        } //end hms loading into modal
    } catch (e) {
        if (e) {
            // console.log("error caught from try catch::" + e.message);
        }
    }
}

function sleep(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
}

function triggerDragObjects(scheme_step_num) {
    //noc = 0;
    console.log("contactlist obj created-" + scheme_step_num);
    if (!CKEDITOR.document.getById('contactList' + scheme_step_num)) {
        return true;
    }
    CKEDITOR.document.getById('contactList' + scheme_step_num).on('dragstart', function(evt) {
        //console.log('dragstarted- ' + scheme_step_num);
        var target = evt.data.getTarget().getAscendant('div', true);

        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);

        var dataTransfer = evt.data.dataTransfer;

        dataTransfer.setData('contact', CONTACTS[target.data('contact')]);
        dataTransfer.setData('text/html', target.getText());
        if (dataTransfer.$.setDragImage) {}
    });


    //editor initialize

    console.log("scheme_step_email_body-" + scheme_step_num);
    if (typeof CKEDITOR.instances['scheme_step_email_body' + scheme_step_num] != 'undefined')
        CKEDITOR.instances['scheme_step_email_body' + scheme_step_num].destroy();

    if (typeof CKEDITOR.instances['scheme_step_email_subject' + scheme_step_num] != 'undefined')
        CKEDITOR.instances['scheme_step_email_subject' + scheme_step_num].destroy();

    CKEDITOR.inline('scheme_step_email_body' + scheme_step_num, {
        extraPlugins: 'hcard,sourcedialog,justify,sharedspace',
        // extraPlugins: 'sharedspace,sourcedialog',
        // removePlugins: 'floatingspace,maximize,resize',
        sharedSpaces: {
            top: 'toolbarLocation' + scheme_step_num
                //bottom: 'bottom2'
        }
    });
    //subject
    CKEDITOR.inline('scheme_step_email_subject' + scheme_step_num, {
        extraPlugins: 'hcard,sourcedialog,justify,sharedspace',
        // extraPlugins: 'sharedspace,sourcedialog',
        // removePlugins: 'floatingspace,maximize,resize',
        sharedSpaces: {
            top: 'toolbarLocation_subject' + scheme_step_num
                //bottom: 'bottom2'
        }
    });

    if (scheme_step_num > 1) {


        setTimeout(function() {
            //$("#toolbarLocation2").html($("#toolbarLocation1").html());

            $("#toolbarLocation" + scheme_step_num).html($("#toolbarLocation1").html());
            $("#toolbarLocation_subject" + scheme_step_num).html($("#toolbarLocation1").html());
            if (!$("#toolbar").html().length) $("#toolbar").html($("#toolbarLocation1").html());
        }, 500);
    }

    //addeventlistener on drop to sub email
    /*
    setTimeout(function() {
        $("#scheme_step_email_subject" + scheme_step_num).on("drop", function(ev) {
            ev.stopPropagation();
            ev.preventDefault();
            var source = ev.target || ev.srcElement;
            var id = source.id;
            var num = id.replace(/^\D+/g, '');

            ev.dataTransfer = ev.originalEvent.dataTransfer;
            var data = ev.dataTransfer.getData("text/html");
            //
            //alert(data);
            var revised_data = data.replace('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">', '');
            revised_data = revised_data.replace('<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">', '');

            if ($("#scheme_step_email_subject" + scheme_step_num).html().indexOf(revised_data) < 0)
                $("#scheme_step_email_subject" + scheme_step_num).html($("#scheme_step_email_subject" + scheme_step_num).html() + " {%" + revised_data + "%} ");
            //noc++;
            console.log("drop::" + id + "::" + num);
        });

    }, 500);
    */

}



function saveOpeingHiringManager(id) {
    //var source = event.target || event.srcElement;

    console.log('THIS IS ID: ' + id);

    var num = id.replace(/^\D+/g, '');
    var data = "hiring_manager_name=" + $("#hiring_manager_name" + num).val();
    data += "&hiring_manager_phone=" + $("#hiring_manager_phone" + num).val();
    data += "&hiring_manager_certainty=" + $("#hiring_manager_certainty" + num).val();
    data += "&hiring_manager_email=" + $("#hiring_manager_email" + num).val();
    data += "&hiring_manager_position=" + $("#hiring_manager_position" + num).val();
    data += "&hiring_manager_linkedin=" + $("#hiring_manager_linkedin" + num).val();
    data += "&hiring_manager_id=" + $("#hiring_manager_id" + num).val();

    $.ajax({
        type: "get",
        url: baseurl + '/saved-opening-hiring-manager',
        data: data + "&opening_id=" + $("#opening_id").val() + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                $("#hiring_manager_id" + num).val(res.hmo.hiring_manager_id);
                if (res.hmo.hiring_manager_id > 1) alert("Successfully Saved");
            }
        }
    });

}


function deleteSchemeStep(id) {
    //var source = event.target || event.srcElement;
    var num = id.replace(/^\D+/g, '');

    $.ajax({
        type: "get",
        url: baseurl + '/delete-scheme-step',
        data: "scheme_step_id=" + $("#scheme_step_id" + num).val() + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                //if(res.hmo.hiring_manager_id>1) alert("Successfully Saved");
            }
        }
    });

}



function deleteOpeingHiringManager(id) {
    //var source = event.target || event.srcElement;
    var num = id.replace(/^\D+/g, '');

    $.ajax({
        type: "get",
        url: baseurl + '/delete-opening-hiring-manager',
        data: "hiring_manager_id=" + $("#hiring_manager_id" + num).val() + "&opening_id=" + $("#opening_id").val() + "&_token=" + _token,
        success: function(res) {
            if (res.success) {

                //if(res.hmo.hiring_manager_id>1) alert("Successfully Saved");
            }
        }
    });

}

//define template
var template = $('#sections .section:first').clone();
var scheme_step_template = $('#scheme_steps .scheme_step:first').clone();
//define counter
var sectionsCount = 0;
var schemeStepCount = 0;

$('body').on('click', '.addschemestep', function() {

    //increment
    schemeStepCount++;

    //loop through each input
    var schemeStep = scheme_step_template.clone().find(':input, #scheme_step_email_body, #contactList, #toolbarLocation, #divDrop, #scheme_step_email_subject, #toolbarLocation_subject').each(function() {

        //set id to store the updated section number
        var newId = this.id + schemeStepCount;
        console.log(newId);
        //update for label
        $(this).prev().attr('for', newId);
        $(this).attr('dataindex', newId);

        //update id
        this.id = newId;

    }).end()

    //inject new section
    .appendTo('#scheme_steps');

    setTimeout(function() {
        triggerDragObjects(schemeStepCount);
    }, 1000);

    return false;
});

//add new section
$('body').on('click', '.addsection', function() {

    //increment
    sectionsCount++;


    //loop through each input
    var section = template.clone().find(':input').each(function() {

        //set id to store the updated section number
        var newId = this.id + sectionsCount;

        //update for label
        $(this).prev().attr('for', newId);
        $(this).attr('dataindex', newId);

        //update id
        this.id = newId;

    }).end()

    //inject new section
    .appendTo('#sections');
    //editor dragobjects
    triggerDragObjects(sectionsCount);
    return false;
});

//remove section
$('#sections').on('click', '.remove', function() {
    //fade out section

    $(this).parent().fadeOut(300, function() {
        //remove parent element (main section)
        $(this).parent().parent().empty();
        $(this).parent().parent().remove();
        $('.section:empty').remove();
        return false;
    });
    return false;
});
//
setTimeout(function() {
    triggerLocation(1);
}, 1000);
