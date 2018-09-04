<?php

?>

<link rel="stylesheet" type="text/css" href="styles-cpw.css">
<div class="col-md-10 mainbar">
<div id="form-section"  >
			<div class="container">
				
				<h2>Change Account Info</h2>
				
				<div class="form-box" style="width:90% !important;">
				<form  action="{{ URL::to('account-info')}}"   id="account-info-form" enctype="multipart/form-data" method="get">
					{{ csrf_field() }}
					<input type="hidden" name="code" id="code" >
					<input type="hidden" name="id" id="id" value="{{Auth::user()->id}}" >

					
					 
					
					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-3 col-form-label">Name</label>
						<div class="col-sm-9">
						<input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}" placeholder="Name" disabled>
						</div>
						</div>

					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-3 col-form-label">Email</label>
						<div class="col-sm-9">
						<input type="email" class="form-control" id="email" name="email" value="{{Auth::user()->email}}" placeholder="Email" disabled>
						</div>

					</div>


					<div class="form-group row">
						<label for="inputEmail3" class="col-sm-3 col-form-label">Received Code</label>
						<div class="col-sm-6">
						 <input type="text" class="form-control inline" id="confirmcode" name="confirmcode" value="" placeholder="Type code here you received in Email" disabled>						
					  </div>
						<div class="col-sm-3">	
						<button class="btn btn-default" onclick="sendCode('sendcode')" type="button" name="send_code" id="send_code" value="Send Code">Send Code</button>	
						<button class="btn btn-default hide" onclick="confirmCode()" type="button" name="confirm_code" id="confirm_code" value="Confirm Code">Confirm Code</button>

					  </div>
					</div>
					
					  
					<div class="form-group row">	
					  <div style="float:right">
							<button onclick="" disabled id="submit" type="submit" name="submit" class="btn btn-primary">Submit</button>
						</div>
					</div>	
					</form>
				</div>
				
			</div>
		</div>
	
	</div> <!-- main content -->	
	<script src="user-preferences.js"></script>