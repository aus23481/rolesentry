<div class="white-box" data-step="3" data-intro="Ok, This is Third Step" data-position='right' data-scrollTo='tooltip'>
		<h3>Job Type

		</h3>
		<input type="hidden" name="selected_values" id="selected_values">
		
		<div id="jobtype-clear" class="jt-options">
			<div class="row">
				@foreach($job_types as $indexKey =>$job_type) 	
				@if($indexKey == 0 || $indexKey == 3 )
					@if($indexKey == 0)
					<div class="col-md-6"> 
					@elseif ($indexKey == 3)
					<div class="col-md-6 hidden-xs hidden-sm">
					@endif
				@endif
					<div class="checkbox parent">
							<?php 
							$jt = "jobtype_".$job_type->id."_";
							?>
					  <label class="check-container parent">
							<input id="{{$jt}}" onclick="toggleSubTypes({{$job_type->id}})" @if(isset($_REQUEST["job_type"])&& in_array($job_type->id,$_REQUEST["job_type"])) checked @endif class="job-type-btn" name="job_type[]" type="checkbox" value="{{$job_type->id}}" @if($indexKey == 0) checked="checked" @endif>
						  <span class="checkmark parent"></span>
						  {{$job_type->name}}
<!---->

							  <a href="#" id="togglecheckbtn{{$indexKey+1}}" class="togglechecktxt{{$indexKey+1}}a parent"></a>														  


						</label>
<!---->

						<div id="togglecheck{{$indexKey+1}}" class="togglecheck-subnav childpop">
							
							@foreach($job_type->subtypes as $subtype)

							<?php 
							$sub = "subtype_".$job_type->id."_".$subtype->id;
							?>
								<label class="check-container childpop">
								<input onclick="checkSubTypeEmpty({{$job_type->id}})" id="{{$sub}}" class="job-type-btn childpop" name="subtype[]" type="checkbox" value="{{$subtype->id}}">
									<span class="checkmark childpop"></span>
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
</div>
<div class="col-md-8 col-xs-3 col-sm-3 location-checkboxes" data-step="2" data-intro="Ok, This is 2nd Step" data-position='right' data-scrollTo='tooltip'>
	<div class="white-box">
		<h3>Location 
			
			
			<div class="srchloc hidden-sm hidden-xs">
			<form action="">
			  <input oninput="searchLocation(1)" type="text"   placeholder="Search..." list="list_location" id="search_location" name="search_location">
			  <datalist  id="list_location" style="width:200px !important">
					@foreach($locations as $indexKey =>$location)
					<option value="{{$location->name}}"> d{{$indexKey}} {{$location->name}} </option>									
					@endforeach
				</datalist> 
			 
			 
			  <button onclick="searchLocation(1)" type="button"><i class="glyphicon glyphicon-search"></i></button>
			</form>
			</div>
		</h3>




		<div id="location-clear" class="loc-options">
			<div class="row" id="location_container_row">
				@foreach($locations as $indexKey =>$location) 	
					@if($indexKey == 0)
					<div class="col-md-3"> 
					@elseif ($indexKey == 3 || $indexKey == 6 || $indexKey == 9)
					<div class="col-md-3 hidden-xs hidden-sm">
					@endif



			   <div class="checkbox">
				 <label class="check-container">
					 <input onclick="checkPlatform()" @if(isset($_REQUEST["location"])&&in_array($location->id, $_REQUEST["location"])) checked @endif class="job-type-btn" name="location[]" type="checkbox" value="{{$location->id}}" @if($indexKey == 0) checked="checked" @endif>
					 <span class="checkmark"></span>
					 {{$location->name}}										   														</label>
			   </div>
			   
			   @if($indexKey == 2 || $indexKey == 5 || $indexKey == 8 || $indexKey == 11) </div> @endif
		   @endforeach
		
			</div>
		</div>

		<div class="row">

				<div class="locpages hidden-sm hidden-xs">
						<ul id="loc-pagination" style="margin-right:20px !important" class="pagination">								
						</ul>
					</div>
	</div>


		





</div>

</div>