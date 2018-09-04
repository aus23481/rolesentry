
	<div class="col-md-12 mainbar">
		<div class="backtoplat-btn"><a href="/platform"><span class="glyphicon glyphicon-arrow-left"></span> Back to Platform</a></div>
	<form  action="{{ URL::to('update-account')}}"   id="change-password-form" enctype="multipart/form-data" method="post">
		{{ csrf_field() }}
	
	<div id="account-form-sectionsss" style="margin-top:20px">
			<div class="row">
				<div class="containerggf" style="padding: 0px 30px 0px">
					<div id="account-box">
						<h2>Change Account Password</h2> 
						<div id="form-container">
							<h3><span>You are not required to change your password to edit your account information.</span></h3>
						
							<form id="acct-form">
								<div class="form-group row">
									<div class="col-lg-3 col-md-6">
										<label for="usr">First Name:</label>
										<input type="text" required class="form-control" value="@if(isset(explode(" ", Auth::user()->name)[0])) {{explode(" ", Auth::user()->name)[0]}}@endif" name="name"  placeholder="" >
									</div>
									<div class="col-lg-3 col-md-6">
										<label for="usr">Last Name:</label>
										<input type="text" class="form-control" value="@if(isset(explode(" ", Auth::user()->name)[1])) {{str_replace(explode(" ", Auth::user()->name)[0], "", Auth::user()->name)}}@endif" name="name2" placeholder="" >
									</div>
									<div class="col-lg-6 col-md-6">
										<label for="usr">Email:</label>
										<input type="email" class="form-control" value="{{Auth::user()->email}}" name="email" id="email"  placeholder="johntanner@gmail.com" >
									</div>
									<div class="col-lg-6 col-md-6">
										<label for="usr">Company Name:</label>
										<input type="text" class="form-control" value="{{Auth::user()->company}}" name="company" id="company" placeholder="Company Name" >
									</div>
									<div class="col-lg-3 col-md-6">
										<label for="usr">New Password:</label>
										<input type="password" class="form-control" value="" name="new_pw" >
									</div>
									<div class="col-lg-3 col-md-6">
										<label for="usr">Confirm Password:</label>
										<input type="password" class="form-control" value="" name="new_pw_retype">
									</div
								</div>
								
							</div>
										<label class="check-container">
										<input type="checkbox" id="receive_newsletter" name="receive_newsletter" @if(isset(Auth::user()->receive_newsletter)&&Auth::user()->receive_newsletter)  checked @endif >
										<span class="checkmark"></span> Yes I would like to receive monthly email newsletter.</label>
									
										<div class="form-group text-right">
									<button type="submit" onclick="if(this.form.new_pw.value !== this.form.new_pw_retype.value){ alert('passwords don\'t match, please retype new and confirm password'); return false; } " class="btn btn-primary">Save Settings</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
	@if(isset($_REQUEST["user_id"]))	
<!---		<div> <a href="/invoice?user_id=<?php print $_REQUEST["user_id"]?>&invoice_type=1">Platform Access</a> &nbsp; &nbsp; &nbsp;
			<a href="/invoice?user_id=<?php print $_REQUEST["user_id"]?>&invoice_type=2">Hiring Manager Report Access</a> &nbsp; &nbsp; &nbsp;
		</div>
-->		
		
		<div class="alert alert-success payment_status">
			@if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "new_account")	Please set a password for this account
			@else Please reset your password
		  @endif
		</div>
	@endif	
	</div> <!-- main content -->	

<script>
	 //$(".sidebar").hide();
		$('#reset-form').click(function(){
			$('#acct-form').trigger("reset");
			$('#acct-form').find('input[type=checkbox]:checked').removeAttr('checked');
			$('#acct-form').find('input[type=text], input[type=email], input[type=password]').reset();
			return false;
		});
	</script>

	
