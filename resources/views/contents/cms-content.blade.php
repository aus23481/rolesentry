<?php

	$user = Auth::user();
	
?>  


<div class="col-md-9 col-lg-9 col-xs-12">          
            <div class="panel panel-default">
                <div class="panel-heading head-desc">
                    <h4> Content Management System  </h4>
		                                    <a href="\cms\createNew"><button type="submit" class="btn btn-success">Create New</button></a>
                </div>

                <div class="panel-body">
			<center><h2>Unpublished</h2></center>

		<?php

        $user = Auth::user();

		?>
		   @foreach($unpublished_emails as $email)
			<div style="padding:10px">
				ID: {{$email->id}} <br>
				Time Created: {{$email->created_at}} 
				<a href="\cms?id={{$email->id}}"><button type="submit" class="btn btn-success">Edit</button></a>
				<?php


					if ($user->id == 6 || $user->id == 2){
				echo '<a href="\cms\delete?id='.$email->id.'"><button type="submit" class="btn btn-success">Delete</button></a>';
					}
					
				?>	
				Embargo Set: @if ( $email->embargo_new_york || $email->embargo_san_francisco || $email->embargo_sydney || $email->embargo_melbourne ) <font color="green">YES</font> @endif
				
			</div>
		   @endforeach
                </div>

                <div class="panel-body">
			<center><h2>Published</h2></center>
		   @foreach($published_emails as $email)
			<div style="padding:10px">
				ID: {{$email->id}} <br>
				Time Published: {{$email->published_at}} 
				<a href="\cms?id={{$email->id}}"><button type="submit" class="btn btn-success">View</button></a>
			</div>
		   @endforeach
                </div>
            </div>  
</div>
        
