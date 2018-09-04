$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function getCandidateActivities() {

    $.ajax({
        type: "get",
        url: baseurl + '/candidateActivities',
        data: "_token=" + _token,
        success: function(res) {
                if (res.success) {

                    ///*
                    var table_html_str = "";
                    $.each(res.candidateActivities, function(k, candidateActivity) {
                        //alert(candidate.id + "::" + candidate.job_type.name);
                        table_html_str += '<tr>';

                        //1st
                        table_html_str += '<td>';
                        table_html_str += candidateActivity.candidate.first_name + " " + candidateActivity.candidate.last_name;
                        table_html_str += ' &nbsp;&nbsp;&nbsp;<a onclick="addUserFavoriteItem(' + candidateActivity.candidate.id + ', 5, \'\')" ><i id="iac-' + candidateActivity.id + '" onclick="$(this).toggleClass(\'fa-star fa-star-o\');" class="fa fa-star"></i></a>';
                        table_html_str += ' </td>';

                        //2nd
                        table_html_str += '<td>';
                        table_html_str += '<button  style="background-color:#c2c9d4;color:white;margin-top:2px;margin-bottom:2px"  class="btn btn-xs" type="button">' + candidateActivity.candidate.job_type.name + '</button> &nbsp;';
                        table_html_str += '<button  style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px"   class="btn btn-xs" type="button">' + candidateActivity.candidate.location.name + '</button> </td>';
                        table_html_str += '</td>';

                        console.log(candidateActivity);

                        //3rd
                        table_html_str += '<td>' + candidateActivity.candidate_activity_type.name + '</td>';
                        table_html_str += '<td>';

                        table_html_str += '<a title="Delete" onclick="deleteCandidateActivity(' + candidateActivity.id + ');" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                        table_html_str += '<a title="Edit" onclick="getCandidateActivity(' + candidateActivity.id + ');"  data-toggle="modal" data-target="#candidateActivityModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                        if (candidateActivity.candidate.resume.length > 0)
                            table_html_str += ' &nbsp;|&nbsp;<a title="Resume Download" href="' + baseurl + '/public/resumes/' + candidateActivity.candidate.resume[0].candidate_id + '.' + candidateActivity.candidate.resume[0].extension + '"    > <i class="fa fa-file" aria-hidden="true"></i></a>';

                        table_html_str += '</td>';

                        table_html_str += '</tr>';

                    }); //foreach

                    $("#table_candidate_activities_result").find("tr:gt(0)").remove();
                    $("#table_candidate_activities_result").append(table_html_str);

                    console.log('4444' + table_html_str);
                    $.each(res.favoriteCandidates, function(k, fc) {
                        $("#iac-" + fc.id).toggleClass("fa-star fa-star-o");
                        console.log("#iac-" + fc.id);
                    });


                } //if seuccess
                else {
                    table_html_str += '<tr><td style="text-align:center;font-weight:bold" colspan=5> No Data Found</td></tr>';
                    $("#table_candidate_activities_result").find("tr:gt(0)").remove();
                    $("#table_candidate_activities_result").append(table_html_str);

                    console.log('123123' + table_html_str);
                } //else

                //*/

            } //res.success

    });

}


function getCandidateActivity(id) {

    $("#btn_candidate_activity_add").hide();
    $("#btn_candidate_activity_save").show();

    $.ajax({
        type: "get",
        url: baseurl + '/get-candidate-activity',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                //candidate = res.candidateActivity.candidate;
                //candidate_activity = res.candidateActivity;
                console.log(res.candidateActivity[0].id);
                $("#candidate_activity_type_id").val(res.candidateActivity[0].candidate_activity_type_id);
                $("#candidate_activity_id").val(res.candidateActivity[0].id);
                $("#cda_first_name").val(res.candidateActivity[0].candidate.first_name);
                $("#cda_last_name").val(res.candidateActivity[0].candidate.last_name);
                $("#cda_job_type_id").val(res.candidateActivity[0].candidate.job_type_id);
                $("#cda_location_id").val(res.candidateActivity[0].candidate.location_id);
                $("#cda_email").val(res.candidateActivity[0].candidate.email);
                $("#cda_linkedin_url").val(res.candidateActivity[0].candidate.linkedin_url);

                var html_str = '<ul>';
                $.each(res.resumes, function(k, resume) {
                    console.log(resume.id + "." + resume.extension);
                    //onclick=" addUserFavoriteItem(' + favorite.id + ', 1, -1); favorite_search_with_opening(' + favorite.id + ')"
                    html_str += '<li class="resume_li"   ><span>' + resume.id + '.' + resume.extension + '</span> <span  id="remove-item1" onclick="deleteResume(' + resume.id + ')" >&nbsp;&nbsp;&nbsp;X</span></li>';

                });

                html_str += '</ul>';
                $("#resume_list").html(html_str);

                var subtypes = [];
                $.each(res.subtypes, function(k, subtype) {
                    subtypes.push(subtype.job_subtype_id);
                });

                $("#cda_job_subtype_id").val(subtypes).trigger('change');


            }
        }

    });

}
//update candidate

//delete

function deleteCandidateActivity(id) {

    $.ajax({
        type: "get",
        url: baseurl + '/delete-candidate-activity',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                if (res.success) {

                    alert("Successfully Updated!");

                    $('#candidateActivityModal').modal('hide');
                    getCandidateActivities();


                }


            }


        }

    });

}

//upate
function editCandidateActivity() {


    var data = $("#candidate-activity-form").serialize();
    //var formData = new FormData();
    //formData.append('file', $('input[type=file]')[0].files[0]);

    // alert(data);
    $.ajax({
        type: "post",
        url: baseurl + '/edit-candidate-activity',
        data: data + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                alert("Successfully Updated!");

                $('#candidateActivityModal').modal('hide');
                getCandidateActivities();


            }


        }

    });

}

//upload file with ajax

$(".file").on('change', function() {
    console.log(_token);
    var formData = new FormData();
    formData.append('file', $('input[type=file]')[0].files[0]);
    formData.append('_token', _token);
    formData.append('candidate_id', $("#candidate_id").val());


    $.ajax({
        url: baseurl + '/upload-file', // Url to which the request is send
        type: "post", // Type of request to be send, called as method
        data: formData, // Data sent to server, a set of key/value pairs (i.e. form fields and values)
        contentType: false, // The content type used when sending data to the server.
        cache: false, // To unable request pages to be cached
        processData: false, // To send DOMDocument or non processed data file it is set to false
        success: function(res) // A function to be called if request succeeds
            {
                if (res.success) {

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



});



//add candidate
function addCandidateActivity() {

    var data = $("#candidate-activity-form").serialize();
    // var resume = new FormData(document.getElementById("resume"));
    $.ajax({
        type: "post",
        url: baseurl + '/add-candidate-activity',
        data: data + "&resume=" + resume + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                alert("Successfully Updated!");

                $('#candidateActivityModal').modal('hide');
                addUserFavoriteItem(res.candidate.id, 5, '');
                setTimeout(function() {
                    getCandidateActivities();
                }, 1000);



            }



        }

    });

}
//deleteresume

function deleteResume(id) {

    $.ajax({
        type: "get",
        url: baseurl + '/delete-resume',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                if (res.success) {

                    alert("Successfully deleted!");
                    getResumes(id);

                }


            }


        }

    });

}

//getresume

function getResumes(id) {

    $.ajax({
        type: "get",
        url: baseurl + '/get-resumes',
        data: "candidate_id=" + $("#candidate_id").val() + "&id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                if (res.success) {

                    // alert("Successfully deleted!");
                    //getResumes();
                    html_str = "";
                    $.each(res.resumes, function(k, resume) {
                        console.log(resume.id + "." + resume.extension);
                        //onclick=" addUserFavoriteItem(' + favorite.id + ', 1, -1); favorite_search_with_opening(' + favorite.id + ')"
                        html_str += '<li class="resume_li"   ><span>' + resume.id + '.' + resume.extension + '</span> <span  id="remove-item1" onclick="deleteResume(' + resume.id + ')" >&nbsp;&nbsp;&nbsp;X</span></li>';
                    });

                    html_str += '</ul>';
                    $("#resume_list").html(html_str);

                }


            }


        }

    });

}
//load job subtypes


function loadJobSubTypesCda() {

    $.ajax({
        type: "get",
        url: baseurl + '/load-job-subtypes',
        data: "job_type_id=" + $("#cda_job_type_id").val() + "&_token=" + _token,
        success: function(res) {

            $('#cda_job_subtype_id').empty();
            if (res.success) {
                //console.log("successfully loaded");
                //alert("Successfully Upated!!!");
                $.each(res.job_subtypes, function(k, sub_type) {
                    $('#cda_job_subtype_id')
                        .append($("<option></option>")
                            .attr("value", sub_type.id)
                            .text(sub_type.name));
                });


            }
        }
    });
}



getCandidateActivities();