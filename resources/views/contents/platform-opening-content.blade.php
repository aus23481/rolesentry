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

		<!-- saved search cke editor for email body -->
		<script src="https://cdn.ckeditor.com/4.10.0/standard-all/ckeditor.js"></script>
		<!-- Invoice Popup Modal -->
		@include("contents.platform-invoice-popup")
		
		<!-- Invoice popup Modal -->

		 <!-- saved search history modal -->
		 @include("contents.platform-common-popups-content")
		 @include("contents.platform-approvals-popups-content")
		 
		<!-- saved search history modal -->
		
		 <!-- Main -->
		 <div class="col-lg-10 col-md-9 mainbar pull-right">
				

@if(isset($alert))
                <div class="alert alert-success payment_status">
			{{$alert}}
			@if(isset($conversion_pixel))
				{!! $conversion_pixel !!}
			@endif
                </div>
@endif
			<form  action="javascript:void(0);"   id="platform-form" enctype="multipart/form-data" method="get">
				<input type="hidden" id="prospecting_type_id" name="prospecting_type_id" value=1>

				{{ csrf_field() }}
				    
					<div id="search-filter-panel" class="row checkbox-filter-section">
						<div class="col-md-4 col-xs-3 col-sm-3 job-type-checkboxes">

							@include("contents.platform-jobtype-location-content")	
						
					<div class="row search-section">
						<div class="col-lg-12 col-md-12">
									<div class="row hidden-sm hidden-xs" style="margin-left:15px">

@if(isset($alert))
	<div style="padding-left:40px !important" class="col-md-5 hidden-xs hidden-sm">
@else

	<div style="padding-left:40px !important" class="col-md-5">
@endif

	<!--</div>-->


											<div class="checkbox hidden-sm hidden-xs">
												<label class="check-container">
													<input   class="job-type-btn filtercheck" name="has_hiring_manager" id="has_hiring_manager" type="checkbox" checked>
													<span class="checkmark"></span>	
													Only roles with hiring manager														
												</label>
											</div>

											 @if ($user->type == 1 || $user->type == 3)
											<div class="checkbox hidden-sm hidden-xs">
												<label class="check-container">
													<input   class="job-type-btn filtercheck" name="editor_mode" id="editor_mode" type="checkbox" >
													<span class="checkmark"></span>	
													Editor Mode														
												</label>
											</div>
											<div class="checkbox hidden-sm hidden-xs">
												<label class="check-container">
													<input class="job-type-btn filtercheck" name="next_email" id="next_email" type="checkbox" >
													<span class="checkmark"></span>	
													Next Email														
												</label>
											</div>
											<div class="checkbox hidden-sm hidden-xs">
												<label class="check-container">
													<input class="job-type-btn filtercheck" name="needs_approval" id="needs_approval" type="checkbox" >
													<span class="checkmark"></span>	
													Needs Approval														
												</label>
											</div>
											<div class="checkbox hidden-sm hidden-xs">
												<label class="check-container">
													<input class="job-type-btn filtercheck" name="is_banned" id="is_banned" type="checkbox" >
													<span class="checkmark"></span>	
													Is Banned										
												</label>
											</div>
 											@endif

						</div>

						
					 <!---
						<div class="col-md-8" style="">
								<button class="btn btn-warning btn-md" type="button" onclick="saveSearchItem()" style="float: right; margin-top:5px"> Save Search</button>
						</div>    
					-->	 



						<div style="margin-top:20px;float:right" class="col-lg-4 col-md-4">
							<div id="custom-search-input">			

								<div class="input-group col-md-12">
									
									<input oninput="searchPlatform(1)" type="text" autocomplete="off" list="search_terms" class="form-control" id="search" name="search" data-hj-whitelist placeholder="Search openings" data-step="1" data-intro="Ok, This is first Step" data-position='right' data-scrollTo='tooltip' />
									<datalist  id="search_terms" style="width:200px !important">
										@foreach($search_terms as $search_term)
										<option value="{{$search_term->term}}"> {{$search_term->term}} </option>									
										@endforeach
									</datalist>
									<span class="input-group-btn">
										<button onclick="searchPlatform(1)" class="btn btn-info" type="button">
											<i class="glyphicon glyphicon-search"></i>
										</button>
									</span>
								</div>
							</div>
							</div>
						
							<div style="margin-top:20px;" class="col-md-3">
							 <button class="btn btn-warning btn-md" onclick="saveSearchItem()" type="button"> Create Automation Template</button> 
							</div> 
						</div>	
					</div>
												


							
						
					</div>
			</form>		

			
			
			            <!-- savedsearch list -->
						<div class="row"


						>
							<h5 style="margin-left: 43px;font-weight: bold;">Prospecting Automation Templates</h5>
							<div class="col-md-12">
								<div class="search-table-section">
									<table id="table_saved_search_result" class="table">
										<thead>
										  <tr>
											<th>Job Title</th>											
											<th>Job Type / Location</th>
											<th>Last Automated Email Send</th>
											<th>Total Automated Emails Sent</th>
											<th>Action</th>						 
										  </tr>
										</thead>
									<tbody>										
									</tbody>
									</table>

									<div class="row">
										<div style="float:right" class="locpages hidden-sm hidden-xs">
												<ul id="ss-pagination" style="margin-right:20px !important" class="pagination">
												</ul>
											</div>
									</div>
						
			
									
								</div>
							</div>
						</div>
			
						<!-- savedsearch result -->


							<!-- savedsearch result -->
							
						
			
			<!-- alert/openings search result -->
			<div class="row">
					<h5 style="margin-left: 43px;font-weight: bold;">Newest Openings</h5>
				<div class="col-md-12">
					<div class="search-table-section">
						<table id="table_platform_search_result" class="table">
							<thead>
							  <tr>
								<th>Position</th>
								<th>Company</th>
								<th>Job Type / Location</th>
								<th>Hiring Manager</th>
								<th>Opened</th>
								<th>Action</th>						 
							  </tr>
							</thead>
						<tbody>
							
							
						</tbody>
						</table>

						<div id="pagination-container" class="text-right"> 
							 <!-- <li id="prev" class="disabled"><span>Previous</span></li><li class="active"><span>1</span></li><li><a onclick="searchPlatform(2)" href="#">2</a></li><li><a onclick="searchPlatform(3)" href="#">3</a></li><li><a onclick="searchPlatform(4)" href="#">4</a></li><li><a onclick="searchPlatform(5)" href="#">5</a></li><li><a onclick="searchPlatform(6)" href="#">6</a></li><li><a onclick="searchPlatform(7)" href="#">7</a></li><li><a onclick="searchPlatform(8)" href="#">8</a></li><li id="next"><a onclick="searchPlatform(2)" href="#" rel="next">Next</a></li> -->
							<ul id="pagination" class="pagination"></ul>
						</div>
					</div>
				</div>
			</div>

			<!-- alert/openings search result -->

						 <!-- Candidate list -->
						 <div class="row"
                                                                                         @if ($user->type != 1)
												style="display:none"	
											 @endif
						 >
								

							<!-- Trigger the modal with a button -->








							</div>
				

		</div>
			


		
							@include("contents.saved-search-content")	




				  <!-- Platform Edit Modal -->
	  
			<form  action="{{ URL::to('platform-edit')}}"    id="platform-edit-form"  method="post">                        
					{{ csrf_field() }} 
					<input type="hidden" name="opening_id" id="opening_id" value="">   
					<input type=hidden id="rolesentry_company_id" name="rolesentry_company_id">
				<div class="modal fade" id="platformEditModal"  role="dialog">
					<div class="modal-dialog modal-md">
					<div class="modal-content">
							<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Platform Edit</h4>
							</div>
							<div class="modal-body">
								<div class="form-group">
							        ID:<input type=text disabled value="" id="showopening_id">	<br>
									<b>Opening Title</b>
									<input class="form-control" type="text" required id="title" name="title" placeholder="Title">
								</div>
								

								<div class="form-group">
							        
									<b>Human Readable Job Title</b>
									<input class="form-control" type="text" required id="human_readable_job_title" name="human_readable_job_title" placeholder="Human Readlable Job Title">
								</div>


								<div class="form-group">
							        
									<b>Human Readable Company Name </b>
									<input class="form-control" type="text" required id="human_readable_company_name" name="human_readable_company_name" placeholder="Human Read Company Name">
								</div>

								<div class="form-group">
									<b>Job Type</b>
									<select onchange="loadJobSubTypes()" class="form-control" name="job_type_id" id="job_type_id">
									   @foreach($job_types_all as $job_type)	
									    <option value="{{$job_type->id}}">{{$job_type->name}}</option>
										@endforeach
									</select>

								</div>

								<div class="form-group">
									<b>Job SubType</b>
									<select class="form-control"  name="job_subtype_id" id="job_subtype_id">
									   @foreach($job_subtypes as $sub_type)	
									    <option value="{{$sub_type->id}}">{{$sub_type->name}}</option>
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

								<div class="form-group" style="display:none" id="hm_names">
										<b>Previously used Hiring Managers for this company</b>
										<br><i>Before selecting please check if hiring manager is still there<br> and if they have same title</i><br>
										<div id="other_hiring_managers">
											Loading...
										</div>
								</div>

							        <div class="form-group">
									<b>Hiring Manager Hint &nbsp;</b>*<span style="color:#404f6b;text-decoration:" id="hiring_manager_hint"></span>*
								</div>	

								<div class="form-group">
									<b>Intel</b>
									<input class="form-control" type="text" required id="intel" name="intel" placeholder="Intel here">
								</div>
								
								<div id="sections">
								  <div class="section" style="border:1px dotted red;padding:5px 5px 5px 5px; margin:5px 0 5px 0px">
									<fieldset>
										
											<input type="hidden" name="hiring_manager_id" id="hiring_manager_id" >
											<div class="form-group">
												<label for="hiring_manager_email">Phone Number</label>
												<input class="form-control" type="text" required id="hiring_manager_phone" name="hiring_manager_phone" placeholder="Phone here">
											</div>


											<div class="form-group">
												<label for="hiring_manager_email">Email</label>
												<input class="form-control" type="text" required id="hiring_manager_email" name="hiring_manager_email" placeholder="Email here">
											</div>
											
											
											<div class="form-group">
												<label for="hiring_manager_name">Hiring Manager Name</label>
													<input class="form-control" type="text" required id="hiring_manager_name" name="hiring_manager_name" placeholder="Hiring Manager Name">
											</div>

											<div class="form-group">
												<label for="hiring_manager_position">Hiring Manager Position</label>
													<input class="form-control" type="text" required id="hiring_manager_position" name="hiring_manager_position" placeholder="Hiring Manager Position">
											</div>

											<div class="form-group">
												<label for="hiring_manager_linkedin">Hiring Manager Linkedin</label>
													<input class="form-control" type="text" required id="hiring_manager_linkedin" name="hiring_manager_linkedin" placeholder="Hiring Manager Linkedin">
											</div>

											<div class="form-group">
												<label for="hiring_manager_linkedin">Hiring Manager Certainty</label>
													<input class="form-control" type="text" required id="hiring_manager_certainty" name="hiring_manager_certainty" placeholder="Hiring Manager Certainty">
											</div>

											<div class="form-group">
												<input type="button" onclick="saveOpeingHiringManager(this.id)" name="Save" id="Save" value="Save" class='btn btn-primary'> &nbsp; <input name="delete" id="delete" onclick="deleteOpeingHiringManager(this.id)" value="Delete" type="button" class='remove btn btn-primary'>
										    </div>		
									</fieldset>
								 </div> <!-- section -->
								</div> <!-- sections -->			
								
								<!--
								 <div class="form-group">
										<b>Hiring Manager Certainty</b>
									  <input type="text" id="hiring_manager_percent" name="hiring_manager_percent" value="80">%
								</div> -->
																
								
								<div class="form-group">
									<a href="#" class='addsection btn btn-primary'>Add New Hiring Manager</a>	<button onclick="editOpeningItem($('#opening_id').val())" class="btn btn-primary" type="button">Update</button>
								</div>
															
							</div>
							<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>					
					</div>
					</div>
				</div>
			  </form>	
			<!-- Edit Modal -->

	
            				  <!-- Edit Ban Modal -->
	  
			<form  action="{{ URL::to('platform-edit')}}"    id="platform-edit-form-ban"  method="post">                        
				{{ csrf_field() }} 
				<input type="hidden" name="opening_id_ban" id="opening_id_ban" value="">   
			<div class="modal fade" id="platformEditModalBan" role="dialog">
				<div class="modal-dialog modal-md">
				<div class="modal-content">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Opening Item Ban</h4>
						</div>
						<div class="modal-body">
							
							<div class="form-group">
								<b>Opening Title</b>
								<input class="form-control" type="text" required id="title_ban" name="title_ban" placeholder="Ban Text For Title">
							</div>

							<div class="form-group">
								<button onclick="banOpeningItem('title')" class="btn btn-primary" type="button">Ban Text</button>
							</div>
							
							<div class="form-group">
								<b>Company</b>
								<input class="form-control" type="text" required id="company_ban" name="company_ban" placeholder="Ban Text For Company">
							</div>

							<div class="form-group">
								<button onclick="banOpeningItem('company')" class="btn btn-primary" type="button">Ban Company</button>
							</div>
							
							<div class="form-group">
								<b>URL</b>
								<input class="form-control" type="text" required id="url_ban" name="url_ban" placeholder="Ban Text For URL">
							</div>
							
							
							<div class="form-group">
								<button onclick="banOpeningItem('url')" class="btn btn-primary" type="button">Ban URL</button>
							</div>
														
						</div>
						<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>					
				</div>
				</div>
			</div>
		  </form>	
		<!-- Edit Ban Modal -->


		  																

		


																	<!-- Candidate Add Modal -->

																	<form  action="{{ URL::to('add-candidate')}}"    id="candidate-form"  method="get">                        
																			{{ csrf_field() }} 
																			<input type="hidden" name="candidate_id" id="candidate_id" value="">   
																		<div class="modal fade" id="candidateModal" role="dialog">
																			<div class="modal-dialog modal-md">
																			<div class="modal-content">
																					<div class="modal-header">
																					<button type="button" class="close" data-dismiss="modal">&times;</button>
																					<h4 class="modal-title">Add Candidate</h4>
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
												<b>Job Type</b>
												<select  class="form-control" name="cd_job_type_id" id="cd_job_type_id">
													@foreach($job_types_all as $job_type)	
													<option value="{{$job_type->id}}">{{$job_type->name}}</option>
													@endforeach
												</select>		
											</div>
	
	
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
												<input type="file" name="resume" id="resume">

											</div>
															
											<div class="form-group">
												<button onclick="editCandidate()" class="btn btn-primary" id="btn_candidate_save" type="button">Save</button> <button onclick="addCandidate()" class="btn btn-primary" id="btn_candidate_add" type="button">Add</button>
											</div>
	
										<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
										</div>					
								</div>
								</div>
							</div>
							</form>	
						<!-- candidate Modal -->
							

		            				  <!-- email Modal -->
	  
									  <form  action="{{ URL::to('platform-email')}}"    id="platform-email-form"  method="post">                        
										{{ csrf_field() }} 
										<input type="hidden" name="opening_id_email" id="opening_id_email" value="">   
									<div class="modal fade" id="platformEmailModal" role="dialog">
										<div class="modal-dialog modal-md">
										<div class="modal-content">
												<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4 class="modal-title">Add Opening Item to Email</h4>
												</div>
												<div class="modal-body">
													
													<div class="form-group">
														<b>Email</b>
													<input class="form-control" type="email" required id="opening_item_email" name="opening_item_email" value="{{Auth::user()->email}}" placeholder="Enter Your Email">
													</div>
						
													<div class="form-group">
														<button onclick="addToEmail()" class="btn btn-primary" type="button">Add To Email</button>
													</div>
	
												<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
												</div>					
										</div>
										</div>
									</div>
								  </form>	
								<!-- email Modal -->




		


 <script>
 $('#back-btn').click(function(){
            $('.sidebar').toggle("slide", {direction:"left"}, 180);
            $('.mainbar').toggleClass('col-lg-10 col-lg-12 col-md-9', 200);
            return false;
        });
        $('.side-bkbtn').click(function(){
			
            $('.sidebar').toggle("slide", {direction:"left"}, 180);
            $('.mainbar').toggleClass('col-lg-10 col-lg-12 col-md-9', 200);
            return false;
        });
		
		$('.sidebar-btn').click(function(){
			
			$('.tophead-btn').fadeIn();
		  return false;
        });
		
		$('.tophead-btn').click(function(){
			
			$('.tophead-btn').fadeOut();
		  return false;
        });
			    					
	</script>						
<script>

$("#togglebtn1").click(function () {
			$(".toggletext1").toggleClass('toggletext1b');
		});
		
		$("#togglebtn2").click(function () {
			$(".toggletext2").toggleClass('toggletext2b');
		});
		
		$("#togglebtn3").click(function () {
			$(".toggletext3").toggleClass('toggletext3b');
		});
		
		$("#togglebtn4").click(function () {
			$(".toggletext4").toggleClass('toggletext4b');
		});
		
		$("#togglebtn5").click(function () {
			$(".toggletext5").toggleClass('toggletext5b');
		});
		
		$("#togglebtn6").click(function () {
			$(".toggletext6").toggleClass('toggletext6b');
		});
		
		$("#togglecheckbtn1").click(function () {
			$("#togglecheck1").slideToggle();
			$(".togglechecktxt1a").toggleClass('togglechecktxt1b');
			$("#togglecheck2, #togglecheck3, #togglecheck4, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn2").click(function () {
			$("#togglecheck2").slideToggle();
			$(".togglechecktxt2a").toggleClass('togglechecktxt2b');
			$("#togglecheck1, #togglecheck3, #togglecheck4, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn3").click(function () {
			$("#togglecheck3").slideToggle();
			$(".togglechecktxt3a").toggleClass('togglechecktxt3b');
			$("#togglecheck1, #togglecheck2, #togglecheck4, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn4").click(function () {
			$("#togglecheck4").slideToggle();
			$(".togglechecktxt4a").toggleClass('togglechecktxt4b');
			$("#togglecheck1, #togglecheck2, #togglecheck3, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn5").click(function () {
			$("#togglecheck5").slideToggle();
			$(".togglechecktxt5a").toggleClass('togglechecktxt5b');
			$("#togglecheck1, #togglecheck2, #togglecheck3, #togglecheck4, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn6").click(function () {
			$("#togglecheck6").slideToggle();
			$(".togglechecktxt6a").toggleClass('togglechecktxt6b');
			$("#togglecheck1, #togglecheck2, #togglecheck3, #togglecheck4, #togglecheck5").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
			});
			return false;
		});

	 $(document).on("click", function(e) { 
		 //'checkmark', , 'check-container' ,'togglecheck-subnav',  'job-type-btn'
		if(!$(e.target).hasClasses(['childpop']) )
		{
			$("div[id*='togglecheck']").hide();					
		}		
	 });

	 $.fn.extend({
		hasClasses: function (selectors) {
			var self = this;
			for (var i in selectors) {
				if ($(self).hasClass(selectors[i])) 
					return true;
			}
			return false;
		}
	});

</script>

<script>

	var alert_frequency = parseInt('<?php print $user->alert_frequency?>');
	
</script>


<script src="js/intro.js"></script>
<script src="js/platform.js"></script>
<script src="js/sidebar-favorites.js"></script>
<script src="js/platform-saved-search.js"></script>
<script src="js/candidate.js"></script>


