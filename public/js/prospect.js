$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function allowDrop(ev) {
    ev.preventDefault();
}

function drop(ev) {
    ev.preventDefault();
    var data = ev.dataTransfer.getData("text/html");
    var source = ev.target || ev.srcElement;
    var id = source.id;
    var num = id.replace(/^\D+/g, '');
    console.log("drop::" + id);
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
            requiredContent: 'span(h-card)',
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

            evt.data.dataValue =
                '' +
                ' ' + contact.name + ' ' +
                '';
        });
    }
});

CKEDITOR.on('instanceReady', function() {
    // When an item in the contact list is dragged, copy its data into the drag and drop data transfer.
    // This data is later read by the editor#paste listener in the hcard plugin defined above.
    CKEDITOR.document.getById('contactList').on('dragstart', function(evt) {
        // The target may be some element inside the draggable div (e.g. the image), so get the div.h-card.
        var target = evt.data.getTarget().getAscendant('div', true);

        // Initialization of the CKEditor data transfer facade is a necessary step to extend and unify native
        // browser capabilities. For instance, Internet Explorer does not support any other data type than 'text' and 'URL'.
        // Note: evt is an instance of CKEDITOR.dom.event, not a native event.
        CKEDITOR.plugins.clipboard.initDragDataTransfer(evt);

        var dataTransfer = evt.data.dataTransfer;

        // Pass an object with contact details. Based on it, the editor#paste listener in the hcard plugin
        // will create the HTML code to be inserted into the editor. You could set 'text/html' here as well, but:
        // * It is a more elegant and logical solution that this logic is kept in the hcard plugin.
        // * You do not know now where the content will be dropped and the HTML to be inserted
        // might vary depending on the drop target.
        dataTransfer.setData('contact', CONTACTS[target.data('contact')]);

        // You need to set some normal data types to backup values for two reasons:
        // * In some browsers this is necessary to enable drag and drop into text in the editor.
        // * The content may be dropped in another place than the editor.
        dataTransfer.setData('text/html', target.getText());

        // You can still access and use the native dataTransfer - e.g. to set the drag image.
        // Note: IEs do not support this method... :(.
        if (dataTransfer.$.setDragImage) {
            //dataTransfer.$.setDragImage(target.findOne('img').$, 0, 0);
        }
    });
});

CKEDITOR.disableAutoInline = true;
// Initialize the editor with the hcard plugin.
CKEDITOR.inline('email_body', {
    extraPlugins: 'hcard,sourcedialog,justify,sharedspace',
    // extraPlugins: 'sharedspace,sourcedialog',
    // removePlugins: 'floatingspace,maximize,resize',
    sharedSpaces: {
        top: 'toolbarLocation'
            //bottom: 'bottom2'
    }
});




function getProspectCommunications() {



    var data = $("#prospect-form").serialize();

    $.ajax({
        type: "get",
        url: baseurl + '/prospecting_actions',
        data: data + "&_token=" + _token,
        success: function(res) {
                if (res.success) {

                    ///*
                    var table_html_str = "";
                    $.each(res.prospecting_actions, function(k, prospecting_action) {
                        //alert(candidate.id + "::" + candidate.job_type.name);
                        table_html_str += '<tr>';

                        //1st
                        table_html_str += '<td>';
                        //table_html_str += prospecting_action.subject;
                        table_html_str += '<div class="panel-group accordion">';
                        table_html_str += '<div class="panel panel-default">';
                        table_html_str += '  <div class="panel-heading">';
                        table_html_str += '    <h4 class="panel-title">';
                        table_html_str += '      <a data-toggle="collapse" data-parent="#accordion" href="#collapse' + k + '">';
                        table_html_str += '      <span class="glyphicon glyphicon-plus"></span>';
                        table_html_str += '      <span class="glyphicon glyphicon-minus"></span>';
                        table_html_str += '      <b>Subject:</b> ' + prospecting_action.subject + '</a>';
                        table_html_str += '    </h4>';
                        table_html_str += '  </div>';
                        // table_html_str += '  <div id="collapse' + k + '" class="panel-collapse collapse">';
                        // table_html_str += '    <div class="panel-body"><b>Message:</b> ' + prospecting_action.message + '</div>';
                        //table_html_str += '  </div>';
                        table_html_str += '</div>';
                        table_html_str += '</div>';
                        table_html_str += '</td>';

                        //1st
                        /* table_html_str += '<td>';
                         table_html_str += prospecting_action.message;
                         table_html_str += '</td>';
                         */
                        //1st
                        table_html_str += '<td>';
                        table_html_str += prospecting_action.time;
                        table_html_str += '</td>';

                        //1st
                        table_html_str += '<td>';
                        table_html_str += prospecting_action.direction;
                        table_html_str += '</td>';

                        //2nd
                        table_html_str += '<td>';
                        table_html_str += '<button  style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px"   class="btn btn-xs" type="button">' + prospecting_action.event_name + '</button> </td>';
                        table_html_str += '</td>';


                        //3rd
                        table_html_str += '<td>';

                        table_html_str += '<a title="Delete" onclick="deleteProspectActivity(' + prospecting_action.id + ');" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                        if (prospect_type_id == 1)
                            table_html_str += '<a title="Edit" onclick="editProspectActivity(' + prospecting_action.id + ');"  data-toggle="modal" data-target="#hmModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                        else table_html_str += '<a title="Edit" onclick="editProspectActivity(' + prospecting_action.id + ');"  data-toggle="modal" data-target="#cdModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';

                        table_html_str += ' &nbsp;|&nbsp;<a title="Reply" onclick="$(\'.down-arrow2\').trigger(\'click\');$(\'html, body\').animate({ scrollTop: $(\'#email-section\').offset().top}, 2000);$(\'#email_subject\').val(\'' + encodeURIComponent(escape(prospecting_action.subject)) + '\'); $(\'#email_body\').html(\'' + encodeURIComponent(escape(prospecting_action.message)) + '\');"    > <i class="fa fa-reply" aria-hidden="true"></i></a>';

                        table_html_str += '</td>';

                        table_html_str += '</tr>';
                        //detail tr
                        table_html_str += ' <tr class="message-container">';
                        table_html_str += '<td colspan="5" class="message-box">';
                        table_html_str += '    <div id="collapse' + k + '" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;"> ';
                        table_html_str += '    <div class="panel-body"><b>Message:</b> <div dir="ltr">' + prospecting_action.message + '</div></div>  </div>';
                        table_html_str += '</td>';
                        table_html_str += '</tr>';


                    }); //foreach

                    $("#table_prospect_result").find("tr:gt(0)").remove();
                    $("#table_prospect_result").append(table_html_str);

                } //if seuccess
                else {
                    table_html_str += '<tr><td style="text-align:center;font-weight:bold" colspan=5> No Prospecting data Found</td></tr>';
                    $("#table_prospect_result").find("tr:gt(0)").remove();
                    $("#table_prospect_result").append(table_html_str);
                } //else

                //*/

            } //res.success

    });

}

//replyProspectEmail

function replyProspectEmail(subject, message) {

    $("#email_subject").val(subject);
    $("#email_body").html(message);

}


//edit 
function editProspectActivity(action_id) {
    console.log(action_id);

}

function editProspect(id) {

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

//upate
function editHiringManager() {


    var data = $("#hm-form").serialize();
    //var resume = new FormData(document.getElementById("resume"));
    // alert(data);
    $.ajax({
        type: "get",
        url: baseurl + '/edit-hiring-manager',
        data: data + "&candidate_id=" + candidate_or_hm_id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                alert("Successfully Updated!");

                $('#hmModal').modal('hide');
                getHiringManagers();


            }


        }

    });

}


//candidaate edit

function getCandidate(id) {

    $("#btn_candidate_add").hide();
    $("#btn_candidate_save").show();

    $.ajax({
        type: "get",
        url: baseurl + '/get-candidate',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                $("#candidate_id").val(res.candidate.id);
                $("#first_name").val(res.candidate.first_name);
                $("#last_name").val(res.candidate.last_name);
                $("#cd_job_type_id").val(res.candidate.job_type_id);
                $("#cd_location_id").val(res.candidate.location_id);
                $("#cd_email").val(res.candidate.email);
                $("#cd_linkedin_url").val(res.candidate.linkedin_url);

                var html_str = '<ul>';
                $.each(res.resumes, function(k, resume) {
                    console.log(resume.id + "." + resume.extension);
                    //onclick=" addUserFavoriteItem(' + favorite.id + ', 1, -1); favorite_search_with_opening(' + favorite.id + ')"
                    html_str += '<li class="resume_li"   ><span>' + resume.id + '.' + resume.extension + '</span> <span  id="remove-item1" onclick="deleteResume(' + resume.id + ')" >&nbsp;&nbsp;&nbsp;X</span></li>';

                });

                html_str += '</ul>';
                $("#resume_list").html(html_str);


            }


        }

    });

}
//edit
//upate
function editCandidate() {


    var data = $("#candidate-form").serialize();
    //var formData = new FormData();
    //formData.append('file', $('input[type=file]')[0].files[0]);

    // alert(data);
    $.ajax({
        type: "post",
        url: baseurl + '/edit-candidate',
        data: data + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                alert("Successfully Updated!");

                $('#cdModal').modal('hide');
                //getCandidates();


            }


        }

    });

}


//update candidate

getProspectCommunications();