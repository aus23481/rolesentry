<?php

	$user = Auth::user();
	//print $user->type."-kd";
/*
	$opening_location_count = [];
	foreach($location_counts as $count){
		$opening_location_count[$count->location_id] = $count->counts;
	}
*/
?>  
<div class="col-md-10 mainbar">
	   
		<form  action="javascript:void(0);"   id="platform-form" enctype="multipart/form-data" method="get">
			{{ csrf_field() }}
				<div class="row checkbox-filter-section">
					<div class="col-md-3 job-type-checkboxes">
						<div style="height:210px;" class="white-box">
						<h3>Job Type</h3>
						<div class="jt-options">
							<div class="row">

							@foreach($job_types as $indexKey =>$job_type) 	
								@if($indexKey == 0 || $indexKey == 3 )<div class="col-md-6"> @endif
									
									<div class="checkbox">
									  <label class="check-container">
											<input @if(isset($_REQUEST["job_type"])&& in_array($job_type->id,$_REQUEST["job_type"])) checked @endif class="job-type-btn" name="job_type[]" type="checkbox" value="{{$job_type->id}}">
										  <span class="checkmark"></span>
										  {{$job_type->name}}
									  </label>
									</div>
				
								
								@if($indexKey == 2 || $indexKey == 5 ) </div> @endif	
							@endforeach	
								
							</div>
						</div>
						</div>
					</div>
					<div class="col-md-6 location-checkboxes">
						<div style="height:210px;" class="white-box">
						<h3>Location</h3>
						<div class="loc-options">
							<div class="row">
								

									
								 @foreach($locations as $indexKey =>$location) 	
								 	@if($indexKey == 0 || $indexKey == 3 || $indexKey == 6 ) <div class="col-md-4"> @endif

									<div class="checkbox">
									  <label class="check-container">
										  <input @if(isset($_REQUEST["location"])&&in_array($location->id, $_REQUEST["location"])) checked @endif class="job-type-btn" name="location[]" type="checkbox" value="{{$location->id}}">
										  <span class="checkmark"></span>
										  {{$location->name}}										   														</label>
									</div>
									
									@if($indexKey == 2 || $indexKey == 5 || $indexKey == 8 ) </div> @endif
								@endforeach
								
								
							</div>
						</div>
						</div>
					</div>
					<div class="col-md-3 intel-checkboxes">
						<div style="height:210px;" class="white-box">
						<h3>Saved Searches</h3>
						<div class="intel-options">
							<div class="row">
								<div class="col-md-12">

										<table id="table_saved_search" style="font-size:8"  class="table">
												<thead>
													<tr>													
														<th>Term</th>
														<th>Daily</th>
														<th>Instant</th>
													</tr>
												</thead>
												<tbody>
													@foreach($saved_search_terms as $saved_search_term)
														<tr id="sstr-{{$saved_search_term->id}}">
														<td>  <a onclick="deleteSavedSearchItem({{$saved_search_term->id}})" href="#"><i class="fa fa-trash-o"></i></a> &nbsp; {{$saved_search_term->term}} <a onclick="addUserFavoriteItem({{$saved_search_term->id}}, 3)" href="#"> @if(isset($favorites[$saved_search_term->id]) && $favorites[$saved_search_term->id] == $saved_search_term->id) <i class="fa fa-star-o"></i>@else <i class="fa fa-star"></i> @endif </a> </td>
														<td> <label class="check-container">
																  <input  @if($saved_search_term->is_daily) checked @endif  class="job-type-btn" name="is_daily" type="checkbox" value="{{$saved_search_term->id}}">
																  <span class="checkmark"></span>															
															  </label>
														</td>
														<td>

																<label class="check-container">
																		<input @if($saved_search_term->is_instant) checked @endif  class="job-type-btn" name="is_instant" type="checkbox" value="{{$saved_search_term->id}}">
																		<span class="checkmark"></span>															
																	</label>

														</td>
														</tr>
													@endforeach
												</tbody>
											 </table>
									
								</div>
							</div>	
						</div>
						</div>
					</div>
				</div>
				<div class="row search-section">
					<div @if($user->type == 2) class="col-md-8" @else class="col-md-6" @endif  >
						<label class="check-container">
							<input   class="job-type-btn" name="has_hiring_manager" id="has_hiring_manager" type="checkbox" checked>
							
							<span class="checkmark"></span>	
							Only roles with hiring manager														
						</label>

						@if($user->type == 1 || $user->type == 3)
						<label class="check-container">
							<input   class="job-type-btn" name="editor_mode" id="editor_mode" type="checkbox" >
							<span class="checkmark"></span>	
							Editor Mode														
						</label>

						<label class="check-container">
							<input class="job-type-btn" name="next_email" id="next_email" type="checkbox" >
							<span class="checkmark"></span>	
							Next Email														
						</label>	

						<label class="check-container">
							<input class="job-type-btn" name="needs_approval" id="needs_approval" type="checkbox" >
							<span class="checkmark"></span>	
							Needs Approval														
						</label>
	

						<label class="check-container">
							<input class="job-type-btn" name="is_banned" id="is_banned" type="checkbox" >
							<span class="checkmark"></span>	
							Is Banned										
						</label>

						<label class="check-container">
							<input class="job-type-btn" name="favorites_only" id="favorites_only" type="checkbox" >
							<span class="checkmark"></span>	
							Favorites Only										
						</label>

						@endif
	
					</div>
					<div class="col-md-4">
						<div id="custom-search-input">
							<div class="input-group col-md-12">
								<input oninput="searchPlatform(1)" type="text" autocomplete="off" list="search_terms" class="form-control" id="search" name="search" placeholder="Search" />
								
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
					<div class="col-md-2">
						
					 <button class="btn btn-warning btn-md" onclick="saveSearchItem()" type="button"> Save Search</button> 

					</div>
				</div>

			</form>		
				<div class="row search-table-section">
					<div class="col-md-12 table-responsive">
					<table id="table_platform_search_result" class="table">
						<thead>
						  <tr>
							<th>Position</th>
							<th>Company</th>
							<th>Location</th>
							<th>Hiring Manager</th>
							<th>Opened</th>
			
							 @if($user->type == 1 || $user->type == 3)<th>Action</th>
							 @else <th>Action</th>
							 @endif
							 
						  </tr>
						</thead>
						<tbody>
							
						</tbody>
					 </table>

					 <div id="pagination-container" class="text-right"> 
					    <ul id="pagination" class="pagination">
                             
                            
						</ul>
					</div>

					 
					</div>
				</div>
            </div>



				  <!-- Edit Modal -->
	  
			<form  action="{{ URL::to('platform-edit')}}"    id="platform-edit-form"  method="post">                        
					{{ csrf_field() }} 
					<input type="hidden" name="opening_id" id="opening_id" value="">   
				<div class="modal fade" id="platformEditModal" role="dialog">
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
								<input type=hidden id="rolesentry_company_id" name="rolesentry_company_id">

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
									   @foreach($locations_all as $location)	
									    <option value="{{$location->id}}">{{$location->name}}</option>
										@endforeach
									</select>

								</div>

								<div class="form-group">
										<b>Hiring Manager Name</b>
										<input class="form-control" type="text" required id="hiring_manager_name" name="hiring_manager_name" placeholder="Hiring Manager Name">
								</div>

								<div class="form-group">
										<b>Hiring Manager Position</b>
										<input class="form-control" type="text" required id="hiring_manager_position" name="hiring_manager_position" placeholder="Hiring Manager Position">
								</div>

								<div class="form-group">
										<b>Hiring Manager Linkedin</b>
										<input class="form-control" type="text" required id="hiring_manager_linkedin" name="hiring_manager_linkedin" placeholder="Hiring Manager Linkedin">
								</div>
									<div class="form-group">
										<b>Hiring Manager Certainty</b>
									  <input type="text" id="hiring_manager_percent" name="hiring_manager_percent" value="80">%
								</div>
																
								
								<div class="form-group">
									<button onclick="editOpeningItem($('#opening_id').val())" class="btn btn-primary" type="button">Update</button>
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
						
						




<script src="js/platform.js"></script>


			
