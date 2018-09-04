
  <div style="display:none">
		<div id="subsform" class="newsletter-subscribe">
			<div class="intro">
				<h2 class="text-center text-primary">Start Your Free 90 Day <b>Recruiter Intel</b> Trial Today</h2>
			</div>            
			<form  action="{{ URL::to('add-home-data')}}" class="col-md-12"   id="request_trial_form"  method="post">                        
					
					{{ csrf_field() }}    
						
				<div class="row">
					<div class="container">
						
						<div class="col-lg-6" style="margin-bottom:15px">
							<div class="formbox">
								<h4 class="form-inst">Tell us about yourself</h4>
								<div class="formsubox">
									<div class="form-group"><input class="form-control" type="text" required id="name" name="name" placeholder="First Name"></div>
									<div class="form-group"><input class="form-control" type="text" required id="last_name" name="last_name" placeholder="Last Name"></div>
									<div class="form-group"><input class="form-control" type="text" required id="phone" name="phone" placeholder="Phone number"></div>
									<div class="form-group"><input class="form-control" type="email" required id="trial_email" name="trial_email" placeholder="Email"></div>
								</div>
							</div>
						</div>
						<div class="col-lg-6" style="margin-bottom:15px">
							<div class="formbox">
								<h4 class="form-inst">Customize your account</h4>
								<div class="formsubox form-group md-col-6 ">
									<ul class="nav nav-tabs">
										<li class="active"><a data-toggle="tab" href="#jobtypesbox" class="form-dropdn-btn">Job Types</a></li>
										<li><a data-toggle="tab" href="#locationsbox" class="form-dropdn-btn">Locations</a></li>
									</ul>
									<div class="tab-content">
										<div id="jobtypesbox" class="tab-pane fade in active">
											<div class="location-checkboxes">
												<div class="row">
													
													@foreach($job_types as $indexKey =>$job_type) 	
														@if($indexKey == 0 || $indexKey == 3 ) <div class="col-md-6" style="text-align:left"> @endif
						
													
														<div class="checkbox">
															<label class="check-container">
															  <input  class="job-type-btn" name="job_type[]" type="checkbox" value="{{$job_type->id}}" @if($indexKey == 0 || $indexKey == 1 || $indexKey == 2) checked @endif>
															  <span class="checkmark"></span>
															  {{$job_type->name}}	
															<a href="#" id="togglecheckbtn{{$indexKey+1}}" class="togglechecktxt{{$indexKey+1}}a"></a>														  
															</label>
															<div id="togglecheck{{$indexKey+1}}" class="togglecheck-subnav">
																
																@foreach($job_type->subtypes as $subtype)
																	<label class="check-container">
																	<input class="job-type-btn" name="subtype[]" type="checkbox" value="{{$subtype->id}}" @if($subtype->job_type_id == 1 || $subtype->job_type_id == 2) checked @endif>
																		<span class="checkmark"></span>
																		{{$subtype->name}}
																	</label>
																@endforeach	
																
															</div>
														</div>													
														@if($indexKey == 2 || $indexKey == 5 ) </div> @endif	
														@endforeach	
														
													
												</div>
											</div>
										</div>
											
										<div id="locationsbox" class="tab-pane fade">
	
											<div class="loc-options">
												<div class="row">		
														
													
															@foreach($locations_all as $indexKey =>$location) 	
															@if($indexKey == 0 || $indexKey == 20 )
															 <div class="col-md-6" style="text-align:left">  @endif
	
															<div id="div-{{$indexKey}}" class="checkbox">
																<label class="check-container">
																<input  class="job-type-btn" name="location[]" type="checkbox" value="{{$location->id}}" @if(isset($location_auto_selects)&&in_array($location->id, $location_auto_selects)) checked @elseif(!isset($location_auto_selects)) @if($indexKey == 0 || $indexKey == 1) checked @endif @endif>
																<span class="checkmark"></span>
															@if($location->name == "Toronto" || $location->name == "Ottawa" || $location->name == "Vancouver")
																<img src="https://recruiterintel.com/images/canada-flag-xs.jpg" width=26px height=14px>
															@endif
															@if($location->name == "Sydney" || $location->name == "Melbourne" || $location->name == "Brisbane")
																<img src="https://recruiterintel.com/images/australia-flag-xs.jpg" width=26px height=14px>
															@endif

																 {{$location->name}}
															</label>
															</div>
																@if($indexKey == 19 || $indexKey == 37) </div> @endif
																@endforeach
			
														
													</div>
														 
												</div>
											</div>
										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
	
					<div id="btn-form-submit" class="form-group md-col-6 form-inline">
						<div class="form-group"><button id="btn_request_trial" class="btn-primary" type="button">Start Free Trial</button></div>
					</div>
				</form>
				</div>
		</div>
