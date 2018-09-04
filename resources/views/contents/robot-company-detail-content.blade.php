<?php

	$user = Auth::user();
	
?>  
  
<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                <div class="panel-heading head-desc">
                    <h4> {{ucwords($company->company_name)}}  </h4>
                </div>

                <div class="panel-body">

                   
                   
                    <div class="steamline" style="padding-left:30px; font-weight:normal !important;"> 
                        
                        
                        <form  action="{{ URL::to('save-robot-company-data')}}"   id="robot-company-form" enctype="multipart/form-data" method="post">
                            {{ csrf_field() }}
                            <input name="id" id="id" class="form-control" type="hidden" value="<?php if(isset($company->id)) print $company->id; ?>">
           


                                <div class="clearfix"></div>
                                <br>
                            <div style="padding-bottom:20px;"><strong>ID: {{$company->id}}</strong> </div>
                            <div style="padding-bottom:20px;"><strong>Name: {{$company->company_name}}</strong> </div>
                            <div style="padding-bottom:20px;"><strong>Source ID: {{$company->source_id}}</strong> </div>
                            <div style="padding-bottom:20px;"><strong>Website Status ID: {{$company->website_status_id}}</strong> </div>
                            <div style="padding-bottom:20px;"><strong>Website: <span id="span_website">{{$company->website}}</span></strong>
                            
                                <input type="text" name="website" value="@if(isset($company->website)) {{$company->website}} @endif" id="website" > 
                                <input type="button" class="save_manual" id="save_website_manual" name="save_website_manual" value="Save Manual Input">
                                <input type="button" onclick="findAutoInput('website')" name="find_website_auto" id="find_website_auto" value="Find Website Automatically’">
                            </div>
                            <div style="padding-bottom:20px;"><strong>Career Page Status ID: {{$company->career_page_status_id}}</strong> </div>
                            <div style="padding-bottom:20px;"><strong>Career Page:<span id="span_career_page"> {{$company->career_page}}</span></strong> 
                                <input type="text" value="@if(isset($company->career_page)) {{$company->career_page}} @endif" name="career_page" id="career_page" > 
                                <input type="button" class="save_manual" id="save_career_page_manual" name="save_career_page_manual" value="Save Manuall Input">
                                <input type="button" onclick="findAutoInput('career_page')" id="find_career_page_auto" name="find_auto" value="Find Career Page Automatically"> 
                            </div>

                            <div style="padding-bottom:20px;"><strong>Key Selector: <span id="span_key_selector">{{$company->key_selector}}</span> </strong>
                                <textarea class="form-control" name="key_selector" id="key_selector" >
                                        @if(isset($company->key_selector)) {{trim($company->key_selector)}} @endif
                                </textarea>
                                <input type="button" class="save_manual" id="save_key_selector_manual" name="save_key_selector_manual" value="Save Manuall Input">
                                <input type="button" onclick="findAutoInput('key_selector')" id="find_key_selector_auto" name="find_auto" value="Find Automatically"> 
                            </div>

                            <div style="padding-bottom:20px;"><strong>Positions Found with Key Selector:<br> 
                                
                                @if(isset($positions))
                                  @foreach($positions as $pos)
                                    <span id="span_positions">{{$pos->position_title}}</span><br />
                                  @endforeach  
                                @endif
                            </strong>
                        </div> 

                        </form>   
                        	
                    </div>

                
                    <hr>
                   
                   
                   
                   
                   
                   
                   
                   
                    <div class="steamline">
                       <table class="table table-striped ">
                         <tr><th>Action</th> <th>Created</th></tr>
                         @foreach($logs as $log)
                         <tr>
                         <td>{{$log->robot_action_type_id=1?"finding domain from company name":"finding career page from domain"}}</td>
                         <td>{{$log->created_at}}</td>

                         </tr>
                         @endforeach
                       </table>
                       
                   </div> 

 

                    

                   
                    
                    

 
                </div>
            </div>  
</div>
        
