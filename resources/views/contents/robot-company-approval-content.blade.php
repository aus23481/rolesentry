<?php

	$user = Auth::user();
	
?>  
  
<div class="col-md-12 col-lg-12 col-xs-12">          
            <div class="panel panel-default">
                <form  action="{{ URL::to('robot-company-approval')}}"   id="robot-company-approval" enctype="multipart/form-data" method="get">
                    {{ csrf_field() }}
                <div class="panel-body">

                   
                   <div  class="row">
			@if($notempty)
                    <div class="col-md-6" style="padding-left:30px; font-weight:normal !important;"> 
                        
                        
                       
                            <input name="id" id="id" class="form-control" type="hidden" value="<?php if(isset($company->id)) print $company->id; ?>">
                            <input type="hidden" name="website_old" value="@if(isset($company->website)) {{$company->website}} @endif" id="website_old" > 
                            <input type="hidden" value="@if(isset($company->career_page)) {{$company->career_page}} @endif" name="career_page_old" id="career_page_old" > 
                            <input type="hidden" value="@if(isset($company->key_selector)) {{trim($company->key_selector)}} @endif" class="form-control" name="key_selector_old" id="key_selector_old" >
                            <input type="hidden" value="@if(isset($company->location_id)) {{trim($company->location_id)}} @endif" class="form-control" name="location_id_old" id="location_id_old" >
                            <input type="hidden" value ="" name="action" id="action">

                                <div class="clearfix"></div>
                                <br>
                            <div style="padding-bottom:5px;"><strong>ID: {{$company->id}}</strong> </div>
                            <div style="padding-bottom:5px;"><strong>Name: {{$company->company_name}}</strong> </div>
                            <div style="padding-bottom:20px;"><strong>Website: <span id="span_website">{{$company->website}}</span></strong>
                                <input type="text" name="website" value="@if(isset($company->website)) {{$company->website}} @endif" id="website" > 
                                @if ($manager) <input type="button" onclick="$('#action').val('website')"  class="save_manual_approval" id="save_website_manual" name="save_website_manual" value="Change">
                                @endif

                                
                            </div>
                            <div style="padding-bottom:20px;"><strong>Career Page Status ID: {{$company->career_page_status_id}}</strong> </div>
                            <div style="padding-bottom:20px;"><strong>Career Page:<span id="span_career_page"><a style="color:blue" href="{{$company->career_page}}">{{$company->career_page}}</a></span></strong> 
                                    
                                <input type="text" value="@if(isset($company->career_page)) {{$company->career_page}} @endif" name="career_page" id="career_page" > 
                                 @if ($manager) <input type="button" onclick="$('#action').val('career_page')" class="save_manual_approval" id="save_career_page_manual" name="save_career_page_manual" value="Change">
                                 @endif

                            </div>

                            <div style="padding-bottom:20px;"><strong>Key Selector: <span id="span_key_selector">{{$company->key_selector}}</span> </strong>
                                <input type="text" value="@if(isset($company->key_selector)) {{trim($company->key_selector)}} @endif" class="form-control" name="key_selector" id="key_selector" >
                                @if ($manager) <input type="button" onclick="$('#action').val('key_selector')" class="save_manual_approval" id="save_key_selector_manual" name="save_key_selector_manual" value="Change">
                                @endif

                            </div>

                            <div style="padding-bottom:20px;">
                                    <strong>Location: <span id="span_location_id">@if(isset($company->location)) {{$company->location->name}} @endif</span> </strong>
                                    <select class="form-control" name="location_id" id="location_id">
                                            
                                             @foreach($locations as $location)
                                                  <option @if(isset($company->location)) @if($company->location->id == $location->id) selected @endif @endif value="{{$location->id}}">{{$location->name}}</option>
                                             @endforeach 
                                         </select>
                                      @if ($manager)   <input type="button" onclick="$('#action').val('location_id')" class="save_manual_approval" id="save_location_manual" name="save_location_manual" value="Change">
				      @endif

                            </div>

                            <div style="padding-bottom:20px;"><strong>Positions Found with Key Selector:</strong><br><br> 
                                
                                @if(isset($positions))
                                  @foreach($positions as $pos)
                                    <span id="span_positions">{{$pos->position_title}}</span><br />
                                  @endforeach  
                                @endif
                            </strong>
                        </div> 

                      @endif 
                        	
                    </div>

                    <!-- button -->
                     <div class="col-md-6" >
                        <div>
			    @if(!$notempty)
				No more companies in this mode, please select new mode.
			    @endif
			
				@if($manager)

                            <select id="status_mode" class="form-control" name="mode" onchange=window.location.href='/set-rapid-approve-mode/'+this.value>
				@if(!$notempty)
					<option selected>finished</option>
				@endif

                               <option @if($notempty) @if($company->approval_status_id == 0) selected @endif @endif value="0"> Normal Mode</option>
                               @foreach($approval_statuses as $status)
                                    @if($status->id !== 1)
                                     <option @if($notempty) @if($company->approval_status_id == $status->id) selected @endif @endif value="{{$status->id}}">{{$status->name}}</option>
                                    @endif
                                @endforeach 


                            </select>

			        @endif	

                        </div>   
			@if ($notempty)
                        @foreach($approval_statuses as $status)

                   @if($status->id !== 1)
                            <div class="row" style="margin-top:3%"> <button style="color:#002db7" type="submit" onclick="approveRobotCompany({{$status->id}})" class="btn btn-lg btn-success">{{$status->name}}</button><br></div> 
                           @endif 
                        @endforeach
			@endif

                     </div>
                    <!-- button -->

                </div> 

                
                    <hr>
                
                </div>
            </form>   
            </div>  
</div>
        
