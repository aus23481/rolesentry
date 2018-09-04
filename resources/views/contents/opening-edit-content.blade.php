<?php

    $user = Auth::user();
    
	
?>  
  
<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                    <div><a href="/openings">Back to opening list</a></div>
                <div class="panel-heading head-desc">
                    <h4> Opening Edit (Id:: {{$opening->id}}) </h4>
                   
                </div>
                
                <div class="panel-body">

                   
                   
                    <div class="steamline" style="padding-left:30px; font-weight:normal !important;"> 
                        
                        
                        <form class="form-horizontal"  action="{{ URL::to('save-opening-data')}}"   id="opening-form" enctype="multipart/form-data" method="post">
                            {{ csrf_field() }}
                            <input name="id" id="id" class="form-control" type="hidden" value="<?php if(isset($opening->id)) print $opening->id; ?>">
           


                            <div class="form-group">
                                <label class="control-label col-sm-2" for="hiring_manager_name">Hiring Manager Name:</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" name="hiring_manager_name" value="@if(isset($opening->hiring_manager_name)) {{$opening->hiring_manager_name}} @endif" placeholder="Enter Hiring Manager Name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="hiring_manager_postion">Hiring Manager Position:</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="hiring_manager_position" value="@if(isset($opening->hiring_manager_position)) {{$opening->hiring_manager_position}} @endif" placeholder="Enter Hiring Manager Position">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="hiring_manager_linkedin">Hiring Manager Linkedin:</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" name="hiring_manager_linkedin" value="@if(isset($opening->hiring_manager_linkedin)) {{$opening->hiring_manager_linkedin}} @endif" placeholder="Enter Hiring Manager Name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="title">Job Title:</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control" name="title" value="@if(isset($opening->title)) {{$opening->title}} @endif">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="hiring_manager_linkedin">Job Description:</label>
                                <div class="col-sm-10">
                                  <textarea class="form-control" name="job_description">@if(isset($opening->job_description)){{$opening->job_description}} @endif</textarea>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="company">Company:</label>
                                <div class="col-sm-10">
                                
                                    <select class="form-control" name="rolesentry_company_id" >
                                        @foreach($companies as $company)
                                    <option @if($company->id == $opening->rolesentry_company_id) selected @endif value="{{$company->id}}" >{{$company->name}}</option>
                                        @endforeach
                                    </select>    

                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-sm-2" for="job_type_id">Job Type:</label>
                                <div class="col-sm-10">
                                <select class="form-control" name="job_type_id" >
                                    @foreach($job_types as $type)
                                <option @if($type->id == $opening->job_type_id) selected @endif value="{{$type->id}}" >{{$type->name}}</option>
                                    @endforeach
                                </select>    
                                </div>
                            </div>

                            <div class="form-group">
                                    <label class="control-label col-sm-2" for="job_type_id">Location:</label>
                                    <div class="col-sm-10">
                                    <select class="form-control" name="location_id" >
                                        @foreach($locations as $location)
                                    <option @if($location->id == $opening->location_id) selected @endif value="{{$location->id}}" >{{$location->name}}</option>
                                        @endforeach
                                    </select>    
                                    </div>
                                </div>


                        <div class="form-group" style="margin-left:17%">
                                <button type="submit" name="approve" class="btn btn-default">Approve</button>
                                <button type="submit" name="skip" class="btn btn-default">Skip</button>
                        </div>

                            

                        </form>   
                        	
                    </div>

                
                </div>
            </div>  
</div>
        
