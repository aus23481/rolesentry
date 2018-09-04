<?php

	$user = Auth::user();
	
?>  	
					<div class="col-md-12">
							<form  action="{{ URL::to('/platform')}}"   id="robot-company-approval" enctype="multipart/form-data" method="get">
								{{ csrf_field() }}
							<div class="row">
								<div class="col-md-3 cstm-col3">
									<h4>Job Type</h4>
									<div class="row job-type-cont">
									@foreach($job_types as $indexKey =>$job_type) 	
											@if($indexKey == 0 || $indexKey == 3 )<div class="col-md-6"> @endif
												<span class="job-type-option"><input @if(isset($_REQUEST["job_type"])&& in_array($job_type->id,$_REQUEST["job_type"])) checked @endif class="job-type-btn" name="job_type[]" type="checkbox" value="{{$job_type->id}}">{{$job_type->name}}</span> 
												<span class="checkmark"></span>
												@if($indexKey == 2 || $indexKey == 5 ) </div> @endif
									@endforeach	
										
									</div>
								</div><!-- 1st box -->
								<div class="col-md-6 cstm-col6">
									<h4>Locations</h4>
									<div class="row job-type-cont">
									   @foreach($locations as $indexKey =>$location) 	
									   	@if($indexKey == 0 || $indexKey == 3 || $indexKey == 6 ) <div class="col-md-4"> @endif
										   <span class="job-type-option"><input @if(isset($_REQUEST["location"])&&in_array($location->id, $_REQUEST["location"])) checked @endif class="job-type-btn" name="location[]" type="checkbox" value="{{$location->id}}">{{$location->name}}</span> 										   
										@if($indexKey == 2 || $indexKey == 5 || $indexKey == 8 ) </div> @endif
										@endforeach
										
									</div>
								</div><!-- 2nd box -->
								<div class="col-md-3 cstm-col3">
									<h4>Intel</h4>
									<div class="row job-type-cont">
										<div class="col-md-10">
											<span class="job-type-option"><input class="job-type-btn" name="intel" type="radio" value="1">Hiring Manager</span> <span class="job-type-option"><input class="job-type-btn" name="intel" type="radio" value="2">Growing Company</span> <span class="job-type-option"><input class="job-type-btn" name="intel" type="radio" value="3">High Value Role</span>
										</div>
										
									</div>
								</div><!-- 3rd box -->
							</div>
							<div class="row platform-table-top-cont">
								<div class="col-md-8">
									
								</div>
								<div class="col-md-4">
										<div class="input-group">
										  <input type="text"   class="form-control" placeholder="Search" value="<?php if(isset($_REQUEST['search'])) print $_REQUEST['search']; ?>" name="search">
										  <div class="input-group-btn"  >
											<input class="btn btn-primary" name="Submit" type="submit" value="Submit">
										  </div>
										</div>
									</div>
							</div>
						</form>
						<div class="row">
							<div class="col-md-12">
								<div class="table-responsive">
									<table style="text-align:left" class="table table-bordred table-striped" id="mytable">
										<thead class="platform-thead">
											<tr>
												<th>
													<h4>Position</h4>
												</th>
												<th>
													<h4>Company</h4>
												</th>
												<th>
													<h4>Hiring Manager</h4>
												</th>
												<th>
													<h4>Linked</h4>
												</th>
												<th>
													<h4>Data Opened</h4>
												</th>
											</tr>
										</thead>
										<tbody class="platform-tbody">
											
										@foreach($alerts as $indexKey => $alert)
											<tr>
												<td >{{$alert->title}}</td>
												<td>{{ucfirst($alert->company)}}</td>
												<td>{{$alert->hiring_manager}}</td>
												<td>
													<a href="#"><i class="fa fa-linkedin"></i>{{$alert->hiring_manager_linkedin}}</a>
												</td>
												<td>{{$alert->created_at}}</td>
											</tr>
										@endforeach												
										</tbody>
									</table>
									<div class="clearfix"></div>
									
								</div>
							</div>
						</div>
					</div>
					
					<!-- content -->
				