<?php

	$user = Auth::user();
	//print $user->type."-kd";
/*
	$opening_location_count = [];
	foreach($location_counts as $count){
		$opening_location_count[$count->location_id] = $count->counts;
	}
*/
// 
?> 




	<script>
	$(document.body).addClass('email-page');	
	var prospect_type_id = parseInt('<?php print $prospect_type_id?>');
	var candidate_or_hm_id = parseInt('<?php print $id?>');
	</script>
		<!-- saved search cke editor for email body -->
		<script src="https://cdn.ckeditor.com/4.10.0/standard-all/ckeditor.js"></script>
		<!-- Invoice Popup Modal -->
		 <!-- Main -->
		 <div class="col-lg-10 col-md-9 mainbar pull-right">

			<div class="row" style="margin:30px 0 0 18px">
			<a title="Edit" class="btn btn-primary btn-md" onclick="loadSavedSearchItem(0, {{$job_type_id}},{{$location_id}});"  data-toggle="modal" data-target="#savedSearchEditModal" href="#savedSearchEditModal" > Create Saved Search</a>

			</div>
			@include("contents.saved-search-content")	
			


			<!-- <div id="personal-info-section">
				<h3>Personal Info
					<ul class="nav navbar-right filter-dropdown">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<span class="glyphicon glyphicon-option-vertical"></span></a>
							<ul class="dropdown-menu">
								<li id="btn_job_type_select_all"><span id="reset-check-1">
										@if($prospect_type_id == 1) 
										<a title="Edit" onclick="editProspect({{$id}});"  data-toggle="modal" data-target="#hmModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										@else <a title="Edit" onclick="getCandidate({{$id}});"  data-toggle="modal" data-target="#cdModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										@endif 
									
									</span></li>
							</ul>
						</li>
					</ul>
				</h3> -->

				<div id="personal-info-section2">
					<h3><a href="#" class="collapse-info up-arrow1"></a>Personal Info
						<ul class="nav navbar-right filter-dropdown">
							<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown" href="#">
								<span class="glyphicon glyphicon-option-vertical"></span></a>
								<ul class="dropdown-menu">
									<li id="btn_job_type_select_all"><span id="reset-check-1">
											 
										@if($prospect_type_id == 1) 
										<a title="Edit" onclick="editProspect({{$id}});"  data-toggle="modal" data-target="#hmModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										@else <a title="Edit" onclick="getCandidate({{$id}});"  data-toggle="modal" data-target="#cdModal"  > <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
										@endif
											 
										
										</span></li>
								</ul>
							</li>
						</ul>
					</h3>


				<div class="info-container">
					<div class="row">
						<div class="col-md-12">
							<h2>{{$first_name}} {{$last_name}}</h2>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6 col-sm-6">
							<p class="hide"><strong>Phone Number: </strong>917-234-2412</p>
						<p><strong>Email: </strong>{{$email}}</p>
						<p><strong>LinkedIn: </strong>{{$linkedin_url}}</p>
						</div>
						<div class="col-md-6 col-sm-6">
						<p><strong>Location: </strong>{{$location}}</p>
							<p><strong>Job Type: </strong>{{$job_type}}</p>
							<p class="hide"><strong>Job Subtype: </strong>Backend</p>
						</div>
					</div>
				</div>
			</div>
			
			








		 
			<!-- direct email -->
			<!--<div class="row" style="margin: 0 16px 18px">

					<form  action="{{ URL::to('send-prospecting-direct-email')}}"    id="prospect-direct-email-form"  method="post">                        
							{{ csrf_field() }} 
					<input type="hidden" name="prospect_id" id="prospect_id" value="{{$prospect_id}}"> 
					<input type="hidden" name="prospect_email" id="prospect_email" value="{{$email}}"> 
					<input type="hidden" name="eml_body" id="eml_body" value="">         
					
					<div class="form-group">
							<b>Email Subject</b>
							<div id="divDrop" style=" height:40px;" ondrop="drop(event)"  ondragover="allowDrop(event)">
							   <input  class="form-control" type="text" required id="email_subject" name="email_subject" placeholder="Email Subject">
							</div>
						</div>
						

						
						<div class="form-group">
							<b>Email Body</b>
							
								

							<div class="columns">
									<div  id="toolbarLocation"></div>
									<div class="editor">
										<div cols="5" id="email_body" name="email_body" rows="5"  contenteditable="true">
											
											
											
										</div>
									</div>
									<div class="contacts">
									</div>
								</div>


							   	
							  </div> 
						

																							
						
						<div style="margin: 0 0 18px"  class="form-group">
							<button  onclick="$('#eml_body').val($('#email_body').html())" class="btn btn-primary" type="submit">Send Email</button>
							
						</div>

					</form>		

			</div> --> <!-- direct email -->

			<!-- direct email -->
			<div id="email-section">
				<h3><a href="#" class="collapse-info up-arrow2"></a>Email Section
					<ul class="nav navbar-right filter-dropdown">
						<li class="dropdown">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							<span class="glyphicon glyphicon-option-vertical"></span></a>
							<ul class="dropdown-menu">
								<li id="btn_job_type_select_all">
									<a a href="#">Edit</a>
								</li>
							</ul>
						</li>
					</ul>
				</h3>
			
				<div class="row">
					<div class="email-form-container">
						<form  action="{{ URL::to('send-prospecting-direct-email')}}"    id="prospect-direct-email-form"  method="post">                        
							{{ csrf_field() }} 
					<input type="hidden" name="prospect_id" id="prospect_id" value="{{$prospect_id}}"> 
					<input type="hidden" name="prospect_email" id="prospect_email" value="{{$email}}"> 
					<input type="hidden" name="eml_body" id="eml_body" value="">         
 
						
						<div class="form-group">
							<b>Email Subject</b>
							<div id="divDrop" style=" height:40px;" ondrop="drop(event)"  ondragover="allowDrop(event)">
							   <input  class="form-control" type="text" required id="email_subject" name="email_subject" placeholder="Email Subject">
							</div>
						</div>
						
						<div class="form-group">
							<b>Email Body</b>
							<div  id="toolbarLocation"></div>
							<div class="editor">
								<div cols="5" id="email_body" name="email_body" rows="5"  contenteditable="true">
								</div>
							</div>
							<div class="contacts">
							</div>
						</div>
						
						<div style="margin: 0 0 18px"  class="form-group">
							<button  onclick="$('#eml_body').val($('#email_body').html())" class="btn btn-primary" type="submit">Send Email</button>
						</div>
						
						</form>		
					</div>
				</div>
			</div>
			<!-- direct email -->


			<div id="checkbox-filter">
					<div class="row">
						<div class="col-md-12 text-right">
							<label class="check-container">
								<input class="job-type-btn" name="job_type[]" type="checkbox" value="1">
									<span class="checkmark"></span>
									Notes
							</label>
							<label class="check-container">
								<input class="job-type-btn" name="job_type[]" type="checkbox" value="1" checked="checked">
									<span class="checkmark"></span>
									Inbound Emails
							</label>
							<label class="check-container">
								<input class="job-type-btn" name="job_type[]" type="checkbox" value="1" checked="checked">
									<span class="checkmark"></span>
									Outbound Emails
							</label>
						</div>
					</div>
				</div>
	




	<form  action="javascript:void(0);"   id="prospect-form" enctype="multipart/form-data" method="get">
	<input type="hidden" name="prospect_id" value="{!! $prospect_id !!}">
	<input type="hidden" name="prospecting_action_type_id" value="{{$prospecting_action_type_id}}">
	<input type="hidden" name="prospect_type_id" value="{{$prospect_type_id}}">

<div id="search-filter-panel" class="row checkbox-filter-section">
<div class="row">
<div class="col-md-12">
								<div class="search-table-section" style="" >
									<table id="table_prospect_result" class="table">
										<thead>
												<tr>						
														<th width="72%" style="padding-left: 23px;">Message</th>
														<th width="7%">Time</th>
														<th width="7%">Inbound / Outbound</th>
														<th width="10%">Prospecting Type</th>
														<th width="4%"></th>
													  </tr>
										</thead>
																	
									</table>
			
									
								</div>
							</div>
		</div>
		</div>	
	</form>
</div>



	  
<!-- Modal -->
<div id="cdModal" class="modal fade" role="dialog">
		<div class="modal-dialog">
	
			<!-- Candidate Add Modal -->
	
			<form enctype="multipart/form-data" action="{{ URL::to('add-candidate')}}" id="candidate-form" method="post">
				{{ csrf_field() }}
				<input type="hidden" name="candidate_id" id="candidate_id" value="">
	
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Edit Candidate</h4>
					</div>
					<div class="modal-body">
	
						<div class="form-group">
							<b>First Name</b>
							<input class="form-control" type="text" required id="first_name" name="first_name" value="" placeholder="First Name">
						</div>
	
						<div class="form-group">
							<b>Last Name</b>
							<input class="form-control" type="text" required id="last_name" name="last_name" value="" placeholder="Last Name">
						</div>
	
						<div class="form-group">
							<b>Email</b>
							<input class="form-control" type="text" required id="cd_email" name="cd_email" value="" placeholder="email">
						</div>
	
						<div class="form-group">
							<b>Linkedin Url</b>
							<input class="form-control" type="text" required id="cd_linkedin_url" name="cd_linkedin_url" value="" placeholder="linkedin url">
						</div>
	
						<div class="form-group">
							<b>Job Type</b>
							<select onchange="loadJobSubTypesCd()" class="form-control" name="cd_job_type_id" id="cd_job_type_id">
								@foreach($job_types_all as $job_type)
								<option value="{{$job_type->id}}">{{$job_type->name}}</option>
								@endforeach
							</select>
						</div>
	
						<div class="form-group">
							<b>Job SubType</b>
							<select class="form-control" style="width:100%" multiple="multiple" name="cd_job_subtype_id" id="cd_job_subtype_id">
								@foreach($job_subtypes as $sub_type)
								<option value="{{$sub_type->id}}">{{$sub_type->name}}</option>
								@endforeach
							</select>
	
						</div>
	
						<script>
							$("#cd_job_subtype_id").select2();
						</script>
	
						<div class="form-group">
							<b>Location</b>
							<select class="form-control" name="cd_location_id" id="cd_location_id">
								@foreach($locations_all_for_dropdown as $location)
								<option value="{{$location->id}}">{{$location->name}}</option>
								@endforeach
							</select>
	
						</div>
	
						<div class="form-group">
							<b>Add Resume</b>
							<input class="file" type="file" name="file" id="file">
							<div id="resume_list">
	
							</div>
						</div>
	
						<div class="form-group">
							<button onclick="editCandidate()" class="btn btn-primary" id="btn_candidate_save" type="button">Save</button>
							<button onclick="addCandidate()" class="btn btn-primary" id="btn_candidate_add" type="button">Add</button>
						</div>
	
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
	
			</form>
			<!-- candidate Modal -->
	
		</div>
	</div>




<!-- HM Add Modal -->

<div class="modal fade" id="hmModal" role="dialog">
		<div class="modal-dialog modal-md">
	
			<form enctype="multipart/form-data" action="{{ URL::to('add-hm')}}" id="hm-form" method="post">
				{{ csrf_field() }}
				<input type="hidden" name="hiring_manager_id" id="hiring_manager_id" value="">
	
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Edit Hiring Manager</h4>
					</div>
					<div class="modal-body">
	
						<div class="form-group">
							<b>Job Type</b>
							<select class="form-control" name="job_type_id" id="job_type_id">
								@foreach($job_types_all as $job_type)
								<option value="{{$job_type->id}}">{{$job_type->name}}</option>
								@endforeach
							</select>
						</div>
	
						<div class="form-group">
							<b>Location</b>
							<select class="form-control" name="location_id" id="location_id">
								@foreach($locations_all_for_dropdown as $location)
								<option value="{{$location->id}}">{{$location->name}}</option>
								@endforeach
							</select>
	
						</div>
	
						<div class="form-group">
							<b>Phone Number</b>
							<input class="form-control" type="text" required id="phone" name="phone" value="" placeholder="Phone Number">
						</div>
						<div class="form-group">
							<b>Company</b>
							<input class="form-control" type="text" required id="company" name="company" value="" placeholder="Company Name">
						</div>

						<div class="form-group">
							<b>Email</b>
							<input class="form-control" type="text" required id="email" name="email" value="{{$email}}" placeholder="email">
						</div>
	
						<div class="form-group">
							<b>Hiring Manager Name</b>
							<input class="form-control" type="text" required id="name" name="name" value="" placeholder="Name">
						</div>
	
						<div class="form-group">
							<b>Position</b>
							<input class="form-control" type="text" required id="title" name="title" value="" placeholder="Title">
						</div>
	
						<div class="form-group">
							<b>Linkedin Url</b>
							<input class="form-control" type="text" required id="linkedin_url" name="linkedin_url" value="" placeholder="linkedin url">
						</div>
	
						<div class="form-group">
							<button onclick="editHiringManager()" class="btn btn-primary" id="btn_hm_save" type="button">Save</button>
							<button onclick="addHm()" class="btn btn-primary" id="btn_hm_add" type="button">Add</button>
						</div>
	
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</form>
	
		</div>
	</div>
	
	<!-- Add HM Modal -->



	<script>
		
	$('#personal-info-section2 .collapse-info').click(function(){
			
	$('.info-container').slideToggle('slow');
			
	$('.collapse-info.up-arrow1').toggleClass('down-arrow1');
		
	});
		
		
	$('#email-section .collapse-info').click(function(){
			
	$('.email-form-container').slideToggle('slow');
			
	$('.collapse-info.up-arrow2').toggleClass('down-arrow2');
		
	});
		
	</script>
																	


					
 

<script src="js/prospect.js"></script>

<script src="js/sidebar-favorites.js"></script>
<script src="js/platform-saved-search.js"></script>
