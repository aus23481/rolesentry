
					


<!-- new design -->

<!-- Saved Search Edit Modal -->


						 <form  action="{{ URL::to('saved-search-edit')}}"    id="saved-search-edit-form"  method="post">                        
						   {{ csrf_field() }} 
						   <input type="hidden" name="ss_id" id="ss_id" value="">
						   <input type="hidden" name="prospecting_type_id" id="ss_prospecting_type_id" value="{{$prospecting_type_id}}">
						   <input type="hidden" name="saved_search_id" id="saved_search_id" value="">
						   <div class="hide" style="min-width:515px" id="toolbar"></div> 
    <div class="modal fade" id="savedSearchEditModal" role="dialog">

        <div class="modal-dialog modal-lg" style="width: 976px;
    left: 140px;">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title">Edit Email Automation Template</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <b>Search Text</b>
                        <input class="form-control" type="text" required="" id="ss_term" name="ss_term" placeholder="Search Text">
                    </div>

                    <div class="form-group">
                        <b>Name</b>
                        <input class="form-control" type="text" required="" id="ss_name" name="ss_name" placeholder="Name">
                    </div>


                    <div class="form-group">
                        <b>Needs Approval</b>
                        <input class="form-control" style="margin-top: -30px;margin-left: -35%;" type="checkbox" required="" id="ss_needs_approval" name="ss_needs_approval" >
                    </div>

                    <div class="form-group">
						<b>Job Type</b>
						<select  style="width:100%" name="ss_job_type_id[]" multiple="multiple" id="ss_job_type_id">
						   @foreach($job_types_all as $job_type)	
							<option value="{{$job_type->id}}">{{$job_type->name}}</option>
							@endforeach
						</select>		
					</div>

					<script>
					$("#ss_job_type_id").select2();
					</script>


					<div class="form-group">
						<b>Location</b>
						<select  style="width:100%" multiple="multiple" name="ss_location_id[]" id="ss_location_id">
						   @foreach($locations_all_for_dropdown as $location)	
							<option value="{{$location->id}}">{{$location->name}}</option>
							@endforeach
						</select>

					</div>

					<script>
						$("#ss_location_id").select2();
					</script>		

		<div id="scheme_selection" class="default-templates">
			<h3>Default Templates</h3>
			<div class="switch-field">
				<input type="radio"  name="switch_3" value="2" onclick="selectScheme(9,49)" />
				<label for="switch_3_center">Passive prospecting on new opening</label>
			</div>
		</div>

		<div id="scheme_steps">
			<div class="scheme_step" style="margin:5px 0 5px 0px">
	
												<input type="hidden" name="scheme_step_id" id="scheme_step_id" >
	
                            <div class="form-group">
                                <b>Email Subject</b>
                                <!--<div id="divDrop" style="width:100%; height:40px;" ondrop="drop(event)" ondragover="allowDrop(event)">
                                    <input ondrop="drop(event)"  class="form-control" type="text" required="" id="scheme_step_email_subject" name="scheme_step_email_subject" placeholder="Email Subject" dataindex="scheme_step_email_subject">
                                </div> -->
                                <div class="columns">
                                    <div style="min-width:515px;margin-bottom:20px" id="toolbarLocation_subject"></div> 
                                <div class="editor email_subject_editor">
                                    <div cols="30" id="scheme_step_email_subject" placeholder="Email Subject" name="scheme_step_email_subject" rows="7" contenteditable="true" dataindex="scheme_step_email_subject1">
                                    </div>
                                </div>
                               </div> 


                            </div>

                            <div class="form-group" for="scheme_step_wait_id">
                                <b>Email Body</b>

                                <div class="columns">
                                    <div style="min-width:515px;margin-bottom:20px" id="toolbarLocation"></div>
                                    <div class="editor">
                                        <div cols="10" id="scheme_step_email_body" placeholder="Email Body" name="scheme_step_email_body" rows="10" contenteditable="true" dataindex="scheme_step_email_body1">


                                        </div>
                                    </div>
                                    <div class="contacts">
                                        <h4>Customization Variables</h4>
                                        <ul id="contactList" class="contactList">
                                            @foreach($customization_variables as $indexKey =>$cv)
											<li>
											<div class="contact h-card" data-contact="{{$indexKey}}" draggable="true" tabindex="0"><span class="glyphicon glyphicon-plus"></span>{{$cv->name}}</div>
											</li>
											 @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- email_body -->

			<table cellpadding=10 cellspacing=10>	
				<tr>
					<td>
					    <strong>How long to wait after sending:</strong>
					</td>
					<td>    
						<select id="lock_type_1" name="lock_type_1" dataindex="lock">
							<option value="7">1 week</option>
							<option value="14">2 weeks</option>
							<option value="21">3 weeks</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>
					    <strong>Approve each email before sending:</strong>
					</td>
					<td>
					    <select id="lock_type_2" name="lock_type_2" dataindex="lock">
						<option value="0">No</option>
						<option value="1">Yes</option>
					    </select>
					</td>
				</tr>
			</table>

                            <div class="form-group">
                                <input type="button" onclick="createOrUpdateSchemeStep(this.id)" name="CreateOrUpdateSchemeStep" id="CreateOrUpdateSchemeStep" value="Save Step" class="btn btn-primary" dataindex="CreateOrUpdateSchemeStep" for="DeleteSchemeStep"> &nbsp;
                                <input name="DeleteSchemeStep" id="DeleteSchemeStep" onclick="deleteSchemeStep(this.id);$(this).parent().parent().remove()" value="Delete" type="button" class="remove btn btn-primary" dataindex="DeleteSchemeStep">
                            </div>
                        </div>
                        
                    </div>
                    <a href="#" class="addschemestep btn btn-primary">
                        <span class="glyphicon glyphicon-plus"></span> Add New Automated Email Step</a>

                    <div class="form-group form-btns-below">
                        <button onclick="editSavedSearchItem($('#ss_id').val())" class="btn btn-primary update-btn" type="button">Update</button>
                        <button type="button" class="btn btn-primary close-btn" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <div class="modal-footer hide">

                </div>
            </div>

        </div>
    </div>
</form>
<!-- Save SearchEdit Modal -->


<!-- new design -->

<script>
//$('body').delegate('#editor','blur', function(){});

    $('body').on('focus', '[contenteditable]', function() {
    
    //var id = $(this).attr("id");
    //var num = id.replace(/^\D+/g, '');
      // console.log($(this).attr("id"));
       $(".cke_editor_scheme_step_email_body1").show();
        //$("div[id*='toolbarLocation']").html($("#toolbar").html()); 
        //if( !$.trim( $('#toolbarLocation'+num).html() ).length ) $('#toolbarLocation'+num).html($("#toolbar").html()); 
        //if( !$.trim( $('#toolbarLocation_subject'+num).html() ).length ) $('#toolbarLocation_subject'+num).html($("#toolbar").html()); 

}).on('blur keyup paste input', '[contenteditable]', function() {
   
    //var id = $(this).attr("id");
    //var num = id.replace(/^\D+/g, '');
    //console.log($(this).attr("id"));
        //$("div[id*='toolbarLocation']").html($("#toolbar").html()); 
        //if( !$.trim( $('#toolbarLocation'+num).html() ).length ) $('#toolbarLocation'+num).html($("#toolbar").html()); 
        //if( !$.trim( $('#toolbarLocation_subject'+num).html() ).length ) $('#toolbarLocation_subject'+num).html($("#toolbar").html()); 
        //console.log('#toolbarLocation_subject'+num);
        //console.log('#toolbarLocation'+num);
        $(".cke_editor_scheme_step_email_body1").show();
});

</script>
