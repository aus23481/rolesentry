function getHiringManagers() {
    //alert("hi");
    var data = $("#platform-form").serialize();
    var location_length = parseInt($("input[name*='location']:checked").length);
    if (!location_length) triggerLocation(1);
    $.ajax({
        type: "get",
        url: baseurl + '/hiring-managers',
        data: data + "&_token=" + _token,
        success: function(res) {
                if (res.success) {

                    ///*
                    var table_html_str = "";
                    var reachable_class = "fa-envelope-open";
                    $.each(res.hiringmanagers, function(k, hm) {
                        //alert(candidate.id + "::" + candidate.job_type.name);

                        if (hm.reachable == 1) reachable_class = "fa-envelope-open-o";
                        else reachable_class = "fa-envelope-open";

                        table_html_str += '<tr>';

                        //1st
                        table_html_str += '<td>';
                        table_html_str += '<a target="_blank" href="' + baseurl + "/prospect?id=" + hm.prospect_id + "&_token=" + _token + '">';
                        table_html_str += hm.name;
                        table_html_str += '</a>';
                        table_html_str += '</td>';

                        //2nd
                        table_html_str += '<td>';
                        table_html_str += '<button onclick="triggerJobType(' + hm.job_type_id + ')" style="background-color:#c2c9d4;color:white;margin-top:2px;margin-bottom:2px"  class="btn btn-xs" type="button">' + hm.job_type + '</button> &nbsp;';
                        table_html_str += '<button onclick="triggerLocation(' + hm.location_id + ')"  style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px"   class="btn btn-xs" type="button">' + hm.location + '</button>';

                        table_html_str += '</td>';

                        table_html_str += '<td id="hms-' + hm.prospect_id + '">';
                        table_html_str += res.prospect[hm.prospect_id] ? res.prospect[hm.prospect_id] : 0;
                        table_html_str += '</td>';

                        //3rd
                        table_html_str += '<td>';
                        if (hm.company)
                            table_html_str += ' &nbsp;' + hm.company + '&nbsp;';
                        else table_html_str += ' &nbsp;...&nbsp;';

                        table_html_str += '</td>';

                        //4th
                        table_html_str += '<td>';

                        table_html_str += '<a title="Delete" onclick="deleteHiringManager(' + hm.id + ');" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                        table_html_str += '<a title="Edit" onclick="getHiringManager(' + hm.id + ');"  data-toggle="modal" data-target="#hmModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> &nbsp;|&nbsp;';
                        table_html_str += '&nbsp;|&nbsp;<a title="Edit" onclick="loadHiringManagerHistory(' + hm.prospect_id + ', 1);"  data-toggle="modal" data-target="#hmHistoryModal" href="#hmHistoryModal" > <i class="fa fa-history" aria-hidden="true"></i></a>';
                        table_html_str += '&nbsp;|&nbsp;<a title="Set Reachable" onclick="toggleProspectReachable(' + hm.prospect_id + ', ' + hm.id + ');"    > <i id="ir-' + hm.id + '" class="fa ' + reachable_class + '" aria-hidden="true"></i></a>';
                        table_html_str += '</td>';

                        table_html_str += '</tr>';

                    }); //foreach

                    $("#table_hiring_managers_result").find("tr:gt(0)").remove();
                    $("#table_hiring_managers_result").append(table_html_str);


                } //if seuccess
                else {
                    table_html_str += '<tr><td style="text-align:center;font-weight:bold" colspan=5> No Data Found</td></tr>';
                    $("#table_hiring_managers_result").find("tr:gt(0)").remove();
                    $("#table_hiring_managers_result").append(table_html_str);
                } //else

                //*/

            } //res.success

    });

}

//set prospect reachable toggle

function toggleProspectReachable(prospect_id, id) {

    $.ajax({
        type: "get",
        url: baseurl + '/change-prospect-reachable',
        data: "prospect_id=" + prospect_id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                alert(res.prospect.reachable);
                $("#ir-" + id).toggleClass("fa-envelope-open-o fa-envelope-open");
            }
        }

    });
}


function getHiringManager(id) {

    $("#btn_hm_add").hide();
    $("#btn_hm_save").show();

    $.ajax({
        type: "get",
        url: baseurl + '/get-hiring-manager',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {
                $("#hm-form")[0].reset()
                $("#hiring_manager_id").val(res.hm[0].id);
                $("#name").val(res.hm[0].name);
                $("#phone").val(res.hm[0].phone);
                $("#email").val(res.hm[0].email);
                $("#title").val(res.hm[0].title);
                $("#company").val(res.hm[0].company.name);
                $("#linkedin_url").val(res.hm[0].linkedin_url);
                $("#job_type_id").val(res.hm[0].location_id);
                $("#location_id").val(res.hm[0].job_type_id);

            }


        }

    });

}
//update candidate

//delete

function deleteHiringManager(id) {

    $.ajax({
        type: "get",
        url: baseurl + '/delete-hiring-manager',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                if (res.success) {

                    alert("Successfully Updated!");

                    $('#hmModal').modal('hide');
                    getHiringManagers();


                }


            }


        }

    });

}

//upate
function editHiringManager() {


    var data = $("#hm-form").serialize();
    //var resume = new FormData(document.getElementById("resume"));
    // alert(data);
    $.ajax({
        type: "get",
        url: baseurl + '/edit-hiring-manager',
        data: data + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                alert("Successfully Updated!");

                $('#hmModal').modal('hide');
                getHiringManagers();


            }


        }

    });

}

//add candidate
function addHm() {

    var data = $("#hm-form").serialize();
    // var resume = new FormData(document.getElementById("resume"));
    $.ajax({
        type: "get",
        url: baseurl + '/add-hiring-manager',
        data: data + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                alert("Successfully Updated!");

                $('#hmModal').modal('hide');
                addUserFavoriteItem(res.hiringManager.id, 2, '');
                getHiringManagers();


            }



        }

    });

}


function importHiringManagersCSV() {

    var stat_automation = $("#start_automation").is(':checked') == true ? 1 : 0;
    var formData = new FormData();
    //formData.append('file', $('input[type=file]')[0].files[0]);
    formData.append('file', $('#csv_hm')[0].files[0]);

    formData.append('_token', _token);
    formData.append('start_automation', stat_automation);
    formData.append('import_csv', 1);
    //console.log($("#start_automation").is(':checked') + "-" + stat_automation);
    $.ajax({
        url: baseurl + '/import-hiring-managers',
        type: "post",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(res) {
            if (res.success) {

                alert("successfully uploaded");
                $("#importHmModal").modal("hide");
            }
        }
    });


}

getHiringManagers();

//saved search history load

function loadHiringManagerHistory(id, page) {
    if (!page) page = 1;
    var context = $("#prospecting_type_id").val();
    $("#table_hiring_manager_history_list").find("tr:gt(0)").remove();
    // $("#ssoeh-pagination").html("");
    $.ajax({
        type: "get",
        url: baseurl + '/load-hiring-manager-history',
        data: "id=" + id + "&prospecting_type_id=" + context + "&hmh=" + page + "&_token=" + _token,
        success: function(res) {
            if (res.success) {

                if (res.past_emails.data.length > 0) {

                    var table_html_str = "";
                    $.each(res.past_emails.data, function(k, ssoeh) {
                        //1st tr
                        console.log("ssoeh id::" + ssoeh.id);
                        table_html_str += '<tr onclick="$(\'.collapse\').collapse(\'hide\'); $(\'#sseh' + k + '\').collapse(\'show\');$(\'.message_container\').show();$(\'.btn-edit-approval, .cke_container\').hide();" data-toggle="collapse" data-target="#sseh' + k + '" class="accordion-toggle">';

                        //1st
                        table_html_str += '<td class="subject-panel">';
                        table_html_str += '<span class="glyphicon glyphicon-chevron-down"></span>';

                        table_html_str += '	<a data-toggle="collapse" onclick="$(\'.message_container\').show();$(\'.btn-edit-approval, .cke_container\').hide();$(\'.collapse\').collapse(\'hide\'); $(\'#sseh' + k + '\').collapse(\'show\');" data-parent="#accordion" href="#sseh' + k + '">';

                        table_html_str += ssoeh.subject + '</a>' + '&nbsp;';

                        table_html_str += '</td>';

                        //3rd
                        table_html_str += '<td>' + ssoeh.name + '&nbsp;';
                        table_html_str += '</td>';

                        //4th
                        table_html_str += '<td>' + ssoeh.email + '&nbsp;';
                        table_html_str += '</td>';

                        //5th
                        table_html_str += '<td><img style="padding-right:10px;width:25px" src="' + ssoeh.logo_url + '">' + ssoeh.company + '&nbsp;';
                        table_html_str += '</td>';

                        //5th
                        table_html_str += '<td>' + ssoeh.opens + '&nbsp;';
                        table_html_str += '</td>';


                        //2nd
                        table_html_str += '<td>';
                        table_html_str += ssoeh.time_sent ? ssoeh.time_sent : '...' + '&nbsp;';

                        table_html_str += '</td>';


                        //6th
                        table_html_str += '<td>';

                        table_html_str += '<a title="Delete" onclick="deleteSavedSearchHistoryItem(1);" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                        //table_html_str += '<a title="Edit" onclick="loadSavedSearchHistoryItem(1);"  data-toggle="modal" data-target="#savedSearchEditModal" href="#savedSearchEditModal" > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                        table_html_str += '<a onclick="$(\'.collapse\').collapse(\'hide\');$(\'#sseh' + k + '\').collapse(\'show\');setTimeout(function(){ $(\'.message_container\').hide(); $(\'.btn-edit-approval, .cke_container\').show();CKEDITOR.replace(\'cke_sseh' + k + '\'); },500);"  data-toggle="collapse" data-parent="#accordion" href="#sseh' + k + '" > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';


                        table_html_str += '</td>';
                        table_html_str += '</tr>';



                        //2nd tr
                        table_html_str += ' <tr>';
                        table_html_str += ' <td colspan="6" class="hiddenRow">';
                        table_html_str += '    <div id="sseh' + k + '" class="accordian-body collapse">';
                        table_html_str += '     <div class="panel-body">';

                        table_html_str += '<div class="message_container">'
                        table_html_str += ssoeh.message;
                        table_html_str += '</div>';

                        table_html_str += '<div class="cke_container"><textarea name="cke_sseh' + k + '" id="cke_sseh' + k + '" rows="10" cols="80" >';
                        table_html_str += ssoeh.message;
                        table_html_str += '   </textarea></div><a  onclick="editApprovalItem( ' + ssoeh.saved_search_id + ',' + ssoeh.id + ')" class="btn btn-primary btn-edit-approval hide">Save</a> </div>';


                        table_html_str += '</div>';
                        table_html_str += ' </div>';
                        table_html_str += '</td>';
                        table_html_str += '</tr>';


                    });


                    //$("#saved_search_history_list").html($history);

                    $("#table_hiring_manager_history_list").find("tr:gt(0)").remove();
                    $("#table_hiring_manager_history_list").append(table_html_str);
                    setSavedSearchEditHistoryPagination(id, res.past_emails);


                } else $("#table_hiring_manager_history_list").append("<tr><td colspan=6><span style='text-align:center'>No History Found</span></td></tr>");
            }



        }
    });



}