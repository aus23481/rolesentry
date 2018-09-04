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
				<input type="hidden" id="prospecting_type_id" name="prospecting_type_id" value=8>
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
						
                                                                                         @if ($user->type == 1 || $user->type == 3)	
							<div style="margin-top:20px;" class="col-md-3">
							 <button class="btn btn-warning btn-md" onclick="saveSearchItem()" type="button"> Create Automation Template</button> 
							</div> 
											 @endif
						</div>	
					</div>
												


							
						
					</div>
			</form>		

			
			
			            <!-- savedsearch list -->
						<div class="row"


                                                                                         @if ($user->type != 1)
                                                                                                style="display:none1"
                                                                                         @endif
						>
							<h5 style="margin-left: 1.5%;font-weight: bold;">Prospecting Automation Templates</h5>
							<div class="col-md-12">
								<div class="search-table-section">
									<table id="table_saved_search_result" class="table">
										<thead>
										  <tr>
											<th>Job Title</th>											
											<th>Job Type / Location</th>
											<th>Time</th>
											<th>Total Sent</th>
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
							
						
			
			<!-- alert/openings search result 
			<div class="row">
					<h5 style="margin-left: 1.5%;font-weight: bold;">Newest Openings</h5>
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
							 
							<ul id="pagination" class="pagination"></ul>
						</div>
					</div>
				</div>
			</div> -->

			<!-- alert/openings search result -->

						 <!-- Hiringg Manager list -->
						 <div class="row"
                                                                                         @if ($user->type != 1)
												style="display:none1"	
											 @endif
						 >
								

							<!-- Trigger the modal with a button -->








								
								<h5 style="margin-left: 1.5%;font-weight: bold;">Hiring Manager <a data-toggle="modal" onclick="$('#btn_hm_add').show();$('#btn_hm_save').hide();" data-target="#hmModal"  class="btn btn-primary">Add Hiring Manager</a> &nbsp;&nbsp;
								<a data-toggle="modal" onclick="" data-target="#importHmModal" class="btn btn-primary">Import Multiple Hiring Managers</a></h5>
								
								<div class="col-md-12"
								>
									<div class="search-table-section">
										<table id="table_hiring_managers_result" class="table">
											<thead>
											  <tr>
												<th>Name</th>											
												<th>Job Type / Location</th>
												<th> Number Prospecting Action</th>
												<th>Company</th>
												<th>Action</th>						 
											  </tr>
											</thead>
										<tbody>	
											

										</tbody>
										</table>
				
										
									</div>
								</div>
							</div>
				

		</div>
			



@include("contents.saved-search-content")

					  																	<!-- HM Add Modal -->

																						  <form enctype="multipart/form-data"   action="{{ URL::to('add-hm')}}"    id="hm-form"  method="post">                        
																							{{ csrf_field() }} 
																							<input type="hidden" name="hiring_manager_id" id="hiring_manager_id" value="">   
																						<div class="modal fade" id="hmModal" role="dialog">
																							<div class="modal-dialog modal-md">
																							<div class="modal-content">
																									<div class="modal-header">
																									<button type="button" class="close" data-dismiss="modal">&times;</button>
																									<h4 class="modal-title">Add Hiring Manager</h4>
																									</div>
																									<div class="modal-body">
																										
																										<div class="form-group">
																											<b>Job Type</b>
																											<select  class="form-control" name="job_type_id" id="job_type_id">
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
																											<input class="form-control" type="text" required id="email" name="email" value="" placeholder="email">
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
																<button onclick="editHiringManager()" class="btn btn-primary" id="btn_hm_save" type="button">Save</button> <button onclick="addHm()" class="btn btn-primary" id="btn_hm_add" type="button">Add</button>
															</div>
					
														<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
														</div>					
												</div>
												</div>
											</div>
										</div>
											</form>	
										<!-- Add HM Modal -->
				


				

	
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
								</div>
								  </form>	
								<!-- email Modal -->


		<!-- HM Import Modal -->
		<div id="importHmModal" class="modal fade" role="dialog">
			<div class="modal-dialog">
		  
			  <!-- Modal content-->
			  <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h4 class="modal-title">Import Hiring Managers</h4>
				</div>
				<div class="modal-body">
	
						<div class="form-group">
								<b>Add .csv file to upload</b>
								<input class="form-control" type="file" class="csv"  id="csv_hm" name="csv_hm" >
						</div>
						<div class="form-group">
								<b>Start Automation</b>
								<input style="margin-top: -30px;margin-left: -25%;" class="form-control" type="checkbox"  id="start_automation" name="start_automation" > 
						</div>
							
				  
				</div>
				<div class="modal-footer">
						<button type="button" onclick="importHiringManagersCSV()" class="btn btn-primary" >Upload</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			  </div>
		  
			</div>
		  </div>
	
			<!-- import HM modal -->		
			
			
			<!--saved search history Modal -->
			
				 <div class="modal fade modal-2" id="hmHistoryModal" role="dialog" >
					<div class="modal-dialog modal-lg" style="    width: 976px;
				left: 140px;">
			<div class="modal-content">
			<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">×</button>
			<h4 class="modal-title">Hiring Manager History</h4>
			</div>
			<div class="modal-body">
			
			<div class="search-table-section history-result">
				<table class="table" id="table_hiring_manager_history_list">
					<thead>
						<tr>						
						<th width="36%" style="padding-left: 14px; border-radius: 8px 0 0 0;">Message</th>
						<th width="12%">Hiring Manager</th>
						<th width="12%">Email</th>
						<th width="18%">Company</th>
						<th width="5%">Opens</th>
						<th width="15%">Time Sent</th>
						<th width="2%" style=" border-radius: 0 8px 0 0;">Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="subject-panel">
								<div class="panel-group accordion">
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
										<span class="glyphicon glyphicon-plus"></span>
										<span class="glyphicon glyphicon-minus"></span>
										<b>Subject:</b> Email Subject Test</a>
										</h4>
									</div>
									</div>
								</div>
							</td>
							<td>07/23/2018 11:00am </td>
							<td>Software Engineer</td>
							<td>Jason Bourne</td>
							<td>Microsoft</td>
							<td><a title="Delete" onclick="" style="cursor:pointer"><i class="fa fa-trash-o"></i></a> &nbsp;|&nbsp;<a title="Edit" onclick="" data-toggle="modal" data-target=""> <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
						</tr>
			
						<tr>
							<td colspan="6" class="hidden-panel">
								<div id="collapse1" class="panel-collapse collapse">
									 <div class="panel-body"><b>Message:</b> Lorem ipsum dolor</div>
								</div>
							</td>
						</tr>
			
					</tbody>
				</table>
			
				<div class="row">
					<div style="float:right" class="locpages hidden-sm hidden-xs">
							<ul id="hmh-pagination" style="margin-right:20px !important" class="pagination">
							</ul>
						</div>
				</div>
				<!--<button class="load-more-btn hide">Load More</button> -->
			</div>
			
			</div>
			<div class="modal-footer">
			<button class="close-btn" data-dismiss="modal">Close</button>
			</div>
			</div>
					</div>
			</div>
			<!-- Edit Saved search history Modal -->
			

		


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
	//alert(_platform);
	
</script>


<script src="js/intro.js"></script>
<script src="js/platform.js"></script>
<script src="js/sidebar-favorites.js"></script>
<script src="js/platform-saved-search.js"></script>
<script src="js/hiring-managers.js"></script>


