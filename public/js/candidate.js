$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


function getCandidates() {

    $.ajax({
        type: "get",
        url: baseurl + '/candidates',
        data: "_token=" + _token,
        success: function(res) {
                if (res.success) {

                    ///*
                    var table_html_str = "";
                    $.each(res.candidates, function(k, candidate) {
                        //alert(candidate.id + "::" + candidate.job_type.name);
                        table_html_str += '<tr>';

                        //1st
                        table_html_str += '<td>';
                        table_html_str += candidate.first_name + " " + candidate.last_name;

                        table_html_str += '</td>';

                        //2nd
                        table_html_str += '<td>';
                        table_html_str += '<button  style="background-color:#c2c9d4;color:white;margin-top:2px;margin-bottom:2px"  class="btn btn-xs" type="button">' + candidate.job_type.name + '</button> &nbsp;';
                        table_html_str += '<button  style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px"   class="btn btn-xs" type="button">' + candidate.location.name + '</button> </td>';
                        table_html_str += '</td>';


                        //3rd
                        table_html_str += '<td>';

                        table_html_str += '<a title="Delete" onclick="deleteCandidate(' + candidate.id + ');" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                        table_html_str += '<a title="Edit" onclick="getCandidate(' + candidate.id + ');"  data-toggle="modal" data-target="#candidateModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>';
                        table_html_str += '</td>';

                        table_html_str += '</tr>';

                    }); //foreach

                    $("#table_candidates_result").find("tr:gt(0)").remove();
                    $("#table_candidates_result").append(table_html_str);

                } //if seuccess
                else {
                    table_html_str += '<tr><td style="text-align:center;font-weight:bold" colspan=5> No Data Found</td></tr>';
                    $("#table_candidates_result").find("tr:gt(0)").remove();
                    $("#table_candidates_result").append(table_html_str);
                } //else

                //*/

            } //res.success

    });

}


//get favorite candidates

function getFavoriteCandidates() {

    $.ajax({
        type: "get",
        url: baseurl + '/favorite-candidates',
        data: "_token=" + _token,
        success: function(res) {
                if (res.success) {

                    ///*
                    var table_html_str = "";
                    var reachable_class = "fa-envelope-open";

                    $.each(res.favoritecandidates, function(k, candidate) {
                        //alert(candidate.id + "::" + candidate.job_type.name);
                        if (candidate.reachable == 1) reachable_class = "fa-envelope-open-o";
                        else reachable_class = "fa-envelope-open";

                        table_html_str += '<tr>';

                        //1st
                        table_html_str += '<td>';
                        table_html_str += '<a target="_blank" href="' + baseurl + "/prospect?id=" + candidate.prospect_id + "&_token=" + _token + '">';
                        table_html_str += candidate.first_name + " " + candidate.last_name;
                        table_html_str += '</a>';

                        table_html_str += '</td>';

                        //2nd
                        table_html_str += '<td>';
                        table_html_str += '<button  style="background-color:#c2c9d4;color:white;margin-top:2px;margin-bottom:2px"  class="btn btn-xs" type="button">' + candidate.job_type + '</button> &nbsp;';
                        table_html_str += '<button  style="background-color:#404e6a;color:white;margin-top:2px;margin-bottom:2px"   class="btn btn-xs" type="button">' + candidate.location + '</button>';

                        table_html_str += '</td>';


                        //3rd
                        table_html_str += '<td>';

                        table_html_str += '<a title="Delete" onclick="deleteCandidate(' + candidate.id + ');" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;';
                        table_html_str += '<a title="Edit" onclick="getCandidate(' + candidate.id + ');"  data-toggle="modal" data-target="#candidateModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  &nbsp;|&nbsp;';
                        table_html_str += '<a title="Set Reachable" onclick="toggleProspectReachable(' + candidate.prospect_id + ', ' + candidate.id + ');"    > <i id="ir-' + candidate.id + '" class="fa ' + reachable_class + '" aria-hidden="true"></i></a>';
                        if (candidate.candidate_id)
                            table_html_str += ' &nbsp;|&nbsp;<a title="Resume Download" href="' + baseurl + '/public/resumes/' + candidate.id + '.' + candidate.extension + '"    > <i class="fa fa-file" aria-hidden="true"></i></a>';
                        table_html_str += '</td>';

                        table_html_str += '</tr>';

                    }); //foreach

                    $("#table_candidates_result").find("tr:gt(0)").remove();
                    $("#table_candidates_result").append(table_html_str);

                } //if seuccess
                else {
                    table_html_str += '<tr><td style="text-align:center;font-weight:bold" colspan=5> No Data Found</td></tr>';
                    $("#table_candidates_result").find("tr:gt(0)").remove();
                    $("#table_candidates_result").append(table_html_str);
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


//endof favorite  candidates
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
                var subtypes = [];
                $.each(res.subtypes, function(k, subtype) {
                    subtypes.push(subtype.job_subtype_id);
                });

                $("#cd_job_subtype_id").val(subtypes).trigger('change');

            }


        }

    });

}
//update candidate

//delete

function deleteCandidate(id) {

    $.ajax({
        type: "post",
        url: baseurl + '/delete-candidate',
        data: "id=" + id + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                if (res.success) {

                    alert("Successfully Updated!");

                    $('#candidateModal').modal('hide');
                    getCandidates();


                }


            }


        }

    });

}

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

                $('#candidateModal').modal('hide');
                getCandidates();


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


function importCandidateCSV() {

    var stat_automation = $("#start_automation").is(':checked') == true ? 1 : 0;
    var formData = new FormData();
    formData.append('file', $('input[type=file]')[0].files[0]);
    formData.append('_token', _token);
    formData.append('start_automation', stat_automation);
    formData.append('import_csv', 1);
    //console.log($("#start_automation").is(':checked') + "-" + stat_automation);
    $.ajax({
        url: baseurl + '/import-candidates',
        type: "post",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function(res) {
            if (res.success) {

                alert("successfully uploaded");
                $("#importCandidateModal").modal("hide");
            }
        }
    });


}


//add candidate
function addCandidate() {

    var data = $("#candidate-form").serialize();
    //var resume = new FormData(document.getElementById("resume"));
    $.ajax({
        type: "post",
        url: baseurl + '/add-candidate',
        data: data + "&_token=" + _token,
        success: function(res) {

            if (res.success) {

                alert("Successfully Updated!");
                addUserFavoriteItem(res.candidate.id, 5, '');
                $('#candidateModal').modal('hide');
                setTimeout(function() {

                    //getCandidates();
                    getFavoriteCandidates();

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


function loadJobSubTypesCd() {

    $.ajax({
        type: "get",
        url: baseurl + '/load-job-subtypes',
        data: "job_type_id=" + $("#cd_job_type_id").val() + "&_token=" + _token,
        success: function(res) {

            $('#cd_job_subtype_id').empty();
            if (res.success) {
                //console.log("successfully loaded");
                //alert("Successfully Upated!!!");
                $.each(res.job_subtypes, function(k, sub_type) {
                    $('#cd_job_subtype_id')
                        .append($("<option></option>")
                            .attr("value", sub_type.id)
                            .text(sub_type.name));
                });


            }
        }
    });
}


getFavoriteCandidates();
//getCandidates();