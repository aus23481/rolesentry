function loadSavedSearchItem(id, job_type_id, location_id) {
    //alert(id);
    // /* 
    //CKEDITOR.instances['email_body'].container.focus();



    $("#ss_needs_approval").prop("checked", false);
    var context = $("#prospecting_type_id").val();
    var data = $("#platform-form").serialize();
    $.ajax({
        type: "get",
        url: baseurl + '/get-schemes',
        data: "id=" + id + "&prospecting_type_id=" + context + "&_token=" + _token,
        success: function(res) {
            $('#scheme_selection').empty();

            var html_str = '<h3>Default Templates</h3><div class="switch-field">';
            $.each(res.schemes, function(s, scheme) {
                html_str += '<label onClick="selectScheme(' + scheme.id + ',' + id + ')">' + scheme.name + '</label>';
                //$('#scheme_selection').append('<button style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px" class="btn btn-xs" type="button" onClick=selectScheme(' + scheme.id + ',' + id + ')>' + scheme.name + '</button>&nbsp;');
            });
            html_str += '</div>';

            $('#scheme_selection').html(html_str);

        }
    });

    //$("#loading").show();
    var data = $("#platform-form").serialize();
    $.ajax({
        type: "get",
        url: baseurl + '/load-saved-search-edit',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            //if (res.success) searchPlatform(1);
            $("#loading").hide();
            //alert(res.id);

            $("#saved_search_id").val(res.saved_search.id);
            $("#ss_id").val(res.saved_search.id);
            $("#ss_term").val(res.saved_search.term);
            $("#ss_name").val(res.saved_search.name);
            // alert(res.saved_search.needs_approval);
            if (res.saved_search.needs_approval) {

                $("#ss_needs_approval").prop("checked", true);
            }
            //$("#ss_location_id").val(location_id);
            //$('#ss_location_id').val(location_id).trigger('change');
            //$("#ss_job_type_id").val(job_type_id).trigger('change');
            var locs = [];
            var jt = [];
            $.each(res.locations, function(l, location) {
                console.log("location::" + location.location_id);
                locs.push(location.location_id);
                // $('#ss_location_id').val(location.location_id).trigger('change');
            });
            console.log(locs);

            $('#ss_location_id').val(locs).trigger('change');
            $.each(res.job_types, function(l, job_type) {
                console.log("job type::" + job_type.job_type_id);
                // $('#ss_job_type_id').val(job_type.job_type_id).trigger('change');
                jt.push(job_type.job_type_id);
            });
            console.log(jt);
            $('#ss_job_type_id').val(jt).trigger('change');


            setSavedSearchSteps(res.scheme_steps);




        }
    });
}



function editSavedSearchItem(id) {

    $("#loading").show();
    var data = $("#saved-search-edit-form").serialize();
    //var needs_approval = $("#needs_approval").is(':checked') == true ? 1 : 0;
    // "&needs_approval=" + needs_approval +
    $.ajax({
        type: "get",
        url: baseurl + '/saved-search-update',
        data: data + "&email_body=" + $("#email_body").html() + "&id=" + id + "&_token=" + _token,
        success: function(res) {
            $('#savedSearchEditModal').modal('hide');
            if (res.success) loadSavedSearches(1);
            $("#loading").hide();
        }
    });

}


//
loadSavedSearches(1);
//


function createOrUpdateSchemeStep(id) {
    //var source = event.target || event.srcElement;

    console.log('THIS IS SCHEME STEP ID: ' + id);

    var num = id.replace(/^\D+/g, '');
    var data = "scstid=" + $("#scheme_step_id" + num).val();
    data += "&ssid=" + $("#saved_search_id").val();
    data += "&sses=" + encodeURIComponent($("#scheme_step_email_subject" + num).html());
    data += "&sseb=" + encodeURIComponent($("#scheme_step_email_body" + num).html());
    data += "&sswi=" + $("#scheme_step_wait_id" + num).val();
    data += "&ssl=" + $("#lock" + num).val();
    data += "&lock_type_1=" + $('#lock_type_1' + num).val();
    data += "&lock_type_2=" + $('#lock_type_2' + num).val();

    console.log('****' + $('#lock_type_1' + num).val() + '*****');

    $.ajax({
        type: "post",
        url: baseurl + '/createOrUpdateSS',
        data: data + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                $("#scheme_step_id" + num).val(res.id);
                if (res.id > 1) alert("Successfully Saved");
            }
        }
    });

}






/*	
$("#email_subject").droppable({
  drop: function(event, ui) {
	  //alert("hi");
    $(this).val( $(this).val() + ui.draggable.text());
  }
});

$("span.cv").draggable({revert:true});
*/
///*
function allowDrop(ev) {
    ev.preventDefault();
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text/html");
    //
    //alert(data);
    var revised_data = data.replace('<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">', '');
    revised_data = revised_data.replace('<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">', '');

    $("#email_subject").val($("#email_subject").val() + " {%" + revised_data + "%} ");
}
//*/	




'use strict';

var CONTACTS = [
    { name: '{%Hiring Manager First Name%}' },
    { name: '{%Hiring Manager Last Name%}' },
    { name: '{%Hiring Manager Company%}' },
    { name: '{%Hiring Manager Phone%}' },
    { name: '{%Job Title%}' },
];


// Implements a simple widget that represents contact details (see http://microformats.org/wiki/h-card).
CKEDITOR.plugins.add('hcard', {
    requires: 'widget',

    init: function(editor) {
        editor.widgets.add('hcard', {
            allowedContent: 'span(!h-card); a[href](!u-email,!p-name); span(!p-tel)',
            //allowedContent: 'span(!h-card); a{text-transform,padding, margin}(scheme_step,contacts,contactList,contact); span{padding-left}(glyphicon,glyphicon-plus)',
            requiredContent: 'span(h-card)',
            allowedContent: 'p[*] h1[*] h2[*] h3[*] h4[*] span[*] div[*] img[*] a[*] ul[*] li[*]',
            pathName: 'hcard',

            upcast: function(el) {
                return el.name == 'span' && el.hasClass('h-card');
            }
        });

        // This feature does not have a button, so it needs to be registered manually.
        editor.addFeature(editor.widgets.registered.hcard);

        // Handle dropping a contact by transforming the contact object into HTML.
        // Note: All pasted and dropped content is handled in one event - editor#paste.
        editor.on('paste', function(evt) {
            var contact = evt.data.dataTransfer.getData('contact');
            if (!contact) {
                return;
            }

            /* evt.data.dataValue =
                 '' +
                 '<span class="cv_button"> ' + contact.name + '</span> ' +
                 '';
                 */
            // '<a href="mailto:' + contact.name + '" class="p-name u-email">' + contact.name + '</a>' +
            evt.data.dataValue =
                '<span class="h-card">' +
                '<a class="scheme_step contacts contactList contact" style="text-transform: uppercase;padding:2px 8px 4px 8px !important;margin:1px 1px 1px 1px !important">' +
                '<span class="glyphicon glyphicon-plus" style="padding-left:1px"> ' +
                contact.name + '</span>' +
                '</a>' +
                '</span>';
        });
    }
});

CKEDITOR.on('instanceReady', function() {

    /*CKEDITOR.document.getById('contactList').on('dragstart', function(evt) {
        var target = evt.data.getTarget().getAscendant('div', true);
        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);
        var dataTransfer = evt.data.dataTransfer;


        dataTransfer.setData('contact', CONTACTS[target.data('contact')]);

        dataTransfer.setData('text/html', target.getText());

        if (dataTransfer.$.setDragImage) {
            //dataTransfer.$.setDragImage(target.findOne('img').$, 0, 0);
        }
    });*/
});


CKEDITOR.config.fillEmptyBlocks = function(element) {
    return true; // DON'T DO ANYTHING!!!!!
};
CKEDITOR.config.ForceSimpleAmpersand = true;
CKEDITOR.config.entities = false;
CKEDITOR.config.basicEntities = false;
CKEDITOR.disableAutoInline = true;
CKEDITOR.config.basicEntities = false;
CKEDITOR.config.autoParagraph = false;

// Initialize the editor with the hcard plugin.
CKEDITOR.inline('scheme_step_email_body', {
    extraPlugins: 'hcard,sourcedialog,justify,sharedspace',
    // extraPlugins: 'sharedspace,sourcedialog',
    // removePlugins: 'floatingspace,maximize,resize',
    sharedSpaces: {
        top: 'toolbarLocation'
            //bottom: 'bottom2'
    }
});



//saved search history load

function loadSavedSearchEditHistory(id, page) {
    if (!page) page = 1;
    var context = $("#prospecting_type_id").val();
    $("#table_saved_search_history_list").find("tr:gt(0)").remove();
    $("#ssoeh-pagination").html("");
    $.ajax({
        type: "get",
        url: baseurl + '/load-saved-search-history',
        data: "id=" + id + "&prospecting_type_id=" + context + "&ssoeh=" + page + "&_token=" + _token,
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

                    $("#table_saved_search_history_list").find("tr:gt(0)").remove();
                    $("#table_saved_search_history_list").append(table_html_str);
                    setSavedSearchEditHistoryPagination(id, res.past_emails);


                } else $("#table_saved_search_history_list").append("<tr><td colspan=6><span style='text-align:center'>No History Found</span></td></tr>");
            }



        }
    });



}

function loadApprovalModal(id, page) {
    if (!page) page = 1;
    var context = $("#prospecting_type_id").val();
    $("#table_approvals_list").find("tr:gt(0)").remove();
    $("#approvals-pagination").html("");
    $.ajax({
        type: "get",
        url: baseurl + '/load-approvals',
        data: "id=" + id + "&prospecting_type_id=" + context + "&ssoeh=" + page + "&_token=" + _token,
        success: function(res) {
            if (res.success) {

                if (res.needed_approvals.data.length > 0) {

                    var table_html_str = "";
                    $.each(res.needed_approvals.data, function(k, approval) {
                        console.log("approval id::" + approval);
                        table_html_str += '<tr onclick="$(\'.collapse\').collapse(\'hide\'); $(\'#approve-tab-' + approval.id + '\').collapse(\'show\');$(\'.message_container\').show();$(\'.btn-edit-approval, .cke_container\').hide();" data-toggle="collapse" data-target="#approve-tab-' + approval.id + '" class="accordion-toggle">';

                        //1st
                        table_html_str += '<td class="subject-panel">';
                        table_html_str += '<span class="glyphicon glyphicon-chevron-down"></span>';

                        table_html_str += '	<a data-toggle="collapse" onclick="$(\'.message_container\').show();$(\'.btn-edit-approval, .cke_container\').hide();$(\'.collapse\').collapse(\'hide\'); $(\'#approve-tab-' + approval.id + '\').collapse(\'show\');" data-parent="#accordion" href="#approval' + approval.id + '">';
                        table_html_str += approval.subject + '</a>' + '&nbsp;';

                        table_html_str += '</td>';

                        //3rd
                        table_html_str += '<td>' + approval.name + '&nbsp;'
                        table_html_str += '</td>';

                        table_html_str += '<td>' + approval.email + '&nbsp;';
                        table_html_str += '</td>';


                        //4th
                        table_html_str += '<td><img style="padding-right:10px;width:25px" src="' + approval.logo_url + '">' + approval.company + '&nbsp;';
                        table_html_str += '</td>';

                        //6th
                        table_html_str += '<td>';

                        table_html_str += '<a title="Approve" onclick="approveApprovalItem(' + approval.saved_search_id + ',' + approval.id + ');" style="cursor:pointer"><i class="fa fa-check"></i></a> &nbsp;|&nbsp;';
                        table_html_str += '<a title="Reject" onclick="rejectApprovalItem(' + approval.saved_search_id + ',' + approval.id + ');" <i class="fa fa-times" aria-hidden="true"></i></a>';
                        table_html_str += '&nbsp;|&nbsp;<a onclick="$(\'.collapse\').collapse(\'hide\');$(\'#approve-tab-' + approval.id + '\').collapse(\'show\');setTimeout(function(){ $(\'.message_container\').hide(); $(\'.btn-edit-approval, .cke_container\').show();CKEDITOR.replace(\'cke_approval' + approval.id + '\'); },500);"  data-toggle="collapse" data-parent="#accordion" href="#approval' + approval.id + '" > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';

                        table_html_str += '</td>';
                        table_html_str += '</tr>';



                        //2nd tr
                        table_html_str += ' <tr>';
                        table_html_str += ' <td colspan="6" class="hiddenRow">';
                        table_html_str += '    <div id="approve-tab-' + approval.id + '" class="accordian-body collapse">';
                        table_html_str += '         <div class="panel-body">';
                        table_html_str += '<div class="message_container">';
                        table_html_str += approval.message;
                        table_html_str += '</div>';
                        table_html_str += '<div class="cke_container"><textarea name="cke_approval' + approval.id + '" id="cke_approval' + approval.id + '" rows="10" cols="80" >';
                        table_html_str += approval.message;
                        table_html_str += '   </textarea></div><a  onclick="editApprovalItem( ' + approval.saved_search_id + ',' + approval.id + ')" class="btn btn-primary btn-edit-approval">Save</a> </div>';
                        table_html_str += '    </div>';
                        table_html_str += '</td>';
                        table_html_str += '</tr>';
                        //CKEDITOR.replace('cke_approval' + k);


                    });


                    //$("#saved_search_history_list").html($history);

                    $("#table_approvals_list").find("tr:gt(0)").remove();

                    console.log(table_html_str);

                    $("#table_approvals_list").append(table_html_str);
                    setApprovalsPagination(id, res.needed_approvals);


                } else $("#table_approvals_list").append("<tr><td colspan=6><span style='text-align:center'>No Automated Emails to Approve</span></td></tr>");
            }



        }
    });
}

function rejectApprovalItem(saved_search_id, id) {
    $.ajax({
        type: "get",
        url: baseurl + '/reject-approval',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                loadApprovalModal(saved_search_id, localStorage.current_page_approvals);
            } else {
                loadApprovalModal(saved_search_id, localStorage.current_page_approvals);
            }
        }
    });
}


function approveApprovalItem(saved_search_id, id) {
    $.ajax({
        type: "get",
        url: baseurl + '/approve-approval',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                loadApprovalModal(saved_search_id, localStorage.current_page_approvals);
            } else {
                loadApprovalModal(saved_search_id, localStorage.current_page_approvals);
            }
        }
    });
}

function editApprovalItem(saved_search_id, id) {

    $.ajax({
        type: "get",
        url: baseurl + '/edit-approval',
        data: "email_message=" + encodeURIComponent(CKEDITOR.instances['cke_approval' + id].getData()) + "&id=" + id + "&_token=" + _token,
        success: function(res) {
            if (res.success) {
                loadApprovalModal(saved_search_id, localStorage.current_page_approvals);
            } else {
                loadApprovalModal(saved_search_id, localStorage.current_page_approvals);
            }
        }
    });

}

$('.accordian-body').on('show.bs.collapse', function() {
    $(this).closest("table")
        .find(".collapse.in")
        .not(this)
        .collapse('toggle')
})