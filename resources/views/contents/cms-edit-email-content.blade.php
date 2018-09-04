<?php

	$user = Auth::user();
	
?>  


<script src="https://cdn.ckeditor.com/4.8.0/standard/ckeditor.js"></script>

<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                <div class="panel-heading head-desc">
                    <h4> Content Management System Email ID: {{$email->id}} </h4>

<form action="/cms/fileupload" method="post" enctype="multipart/form-data">

<div class="row cancel">
 {{ csrf_field() }}
    <div class="col-md-4">

         <input type="file" name="fileToUpload" id="fileToUpload">
         <input type="hidden" name="emailId" id="emailId" value="{{$email->id}}">
	 <input type="hidden" name="userType" id="userType" value="{{$user_id}}">
    </div>

    <div class="col-md-4">

	@if (!$email->published_at)
        	<button type="submit" class="btn btn-success">Upload Email CSV</button>
	@else
	This email was published at {{$email->published_at}}
	@endif
	
    </div>

</div>

</form>
	<table>
		<tr>
			<td>
        <a href="/cms/download?id={{$email->id}}"><button type="submit" class="btn btn-success">Download Email CSV</button></a>
			</td>
		<td>
	@if ($user_id == 6 || $user_id == 2)

		@if ( $email->embargo_new_york || $email->embargo_san_francisco || $email->embargo_sydney || $email->embargo_melbourne)	
			<a href="/cms/embargo?emailId={{$email->id}}&toggle=0"><button type="submit" class="btn btn-success">Click to turn off automatic Publish</button></a>
		@else
			<a href="/cms/embargo?emailId={{$email->id}}&toggle=1"><button type="submit" class="btn btn-success">Click to turn on automatic Publish</button></a>
		@endif
	@endif
		</td>
		<td>
        <a href="/cms/previewSummaryAlert?emailId={{$email->id}}"><button type="submit" class="btn btn-success">Preview</button></a>
		</td>
		</tr>
	</table>


               </div>

                <div class="panel-body">

        <a href="/cms/downloadAlertSinceLastPublishedEmailWasCreated"><button type="submit" style="background-color:#5ed4ce;margin:10px" class="btn btn-success">Download Alerts After Last Published Email was Created</button></a>

		</div>
                <div class="panel-body">

		@foreach($alertLocations as $alertLocation)
		
		   <div style="padding:10px"> <h3>Location: {{$alertLocation['name']}}</h3>
		   
			   @foreach($alertLocation['alerts'] as $alert)

		                <b>Title </b>{{$alert->title}}<br>
                                <b>Job Description Link</b>: <a style="color:blue" href="{{$alert->job_description_link}}">{{$alert->job_description_link}}</a><br>
                                <b>Hiring Manager LinkedIn</b>: <a style="color:blue" href="{{$alert->hiring_manager_linkedin}}">{{$alert->hiring_manager_linkedin}}</a>
                                <br><br>

			   @endforeach
			
		  </div>
		@endforeach
                </div>
            </div>  
</div>
        
