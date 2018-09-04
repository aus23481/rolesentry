<?php

	$user = Auth::user();
	
	//1 admin
	//2 Subscriber
    //print $user->type;
    $createdts = strtotime($alert->created_at);
    $nowts = time();
    $difhm = date("d:h#", $nowts-$createdts);
    $difhm = str_replace(":","  hr ", $difhm);
    $difhm = str_replace("#"," min ago", $difhm);
?>  

<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>
  
<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                <div class="panel-heading head-desc">
                    <h4> Alert Detail  </h4>
                </div>

                <div class="panel-body">
                  
                <form  action="{{ URL::to('update-alert')}}"   id="alert-form" enctype="multipart/form-data" method="post">
            
                        {{ csrf_field() }}
                        <input type=hidden name="id" value="{{$alert->id}}">

                            <!-- Modal -->
                            <div >
                                <div class="modal-dialog">
                                
                                <!-- Modal content-->
                                <div class="modal-content">
                                
                                    <div class="modal-body">
                                    <div class="form-group">
                                        <label for="name">Company :: {{ucfirst($alert->job->company->name)}}</label>                                                                               
                                    </div>

                                    <div class="form-group">
                                        <label for="career_page_url">Title:: {{$alert->job->title}}</label>                                       
                                    </div>

                                    <div class="form-group">
                                        <label for="xpath">Role Opened:: {{$difhm}}</label>                                        
                                    </div>

                                    <div class="form-group">
                                        <label for="xpath">Job description summary::</label>
                                        <textarea name="job_description_summary" id="job_description_summary" class="form-control">{{$alert->job_description_summary}}</textarea>                                        
					<script>
						CKEDITOR.replace('job_description_summary');
					</script>
                                        <label for="xpath">Skills::</label>
                                        <textarea name="skills" id="skills" class="form-control">{{$alert->skills}}</textarea>                                        
					<script>
						CKEDITOR.replace('skills');
					</script>
                                              <label for="xpath">Years Experience::</label>
                                        <textarea name="years_experience" id="years_experience" class="form-control">{{$alert->years_experience}}</textarea>                                        
					<script>
						CKEDITOR.replace('years_experience');
					</script>


                                        <label for="xpath">Education::</label>
                                        <textarea name="education" id="education" class="form-control">{{$alert->education}}</textarea>                                        
					<script>
						CKEDITOR.replace('education');
					</script>
 
                                        <label for="xpath">Hiring Manager::</label>
                                        <textarea name="hiring_manager" id="hiring_manager" class="form-control">{{$alert->hiring_manager}}</textarea>                                        
					<script>
						CKEDITOR.replace('hiring_manager');
					</script>
                                        <label for="xpath">Recent Funding::</label>
                                        <textarea name="recent_funding" id="recent_funding" class="form-control">{{$alert->recent_funding}}</textarea>                                        
					<script>
						CKEDITOR.replace('recent_funding');
					</script>
 
                                    </div>
			<div class="form-group">
                           @foreach($locations as $location)
				{{$location->name}} <input name="locations[]" id="locations" type="checkbox" value="{{ $location->id }}"><br>
			   @endforeach
			</div>

                                    <div class="form-group">            
                                    <button type="submit" id="btn_submit_alert" class="btn btn-default" data-dismiss="modal">
                                            Submit
                                    </button>
                                    </div>

                                </div>
                                
                                </div>
                                
                                </div>
                            </div>
                        </form>       
                </div>
            </div>  
</div>
        
