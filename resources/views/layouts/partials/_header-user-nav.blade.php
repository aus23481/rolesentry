
	
	<!-- top header -->	
	
	<div id="header-section">
		<nav class="navbar navbar-default" 

      @if(request()->route()->getName() != "welcome")
style="    
position: fixed;
    top: 0;
    width: 100%;
    z-index: 100000000;"
	@endif
>
		  <div class="container-fluid">
			<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar1">
			  <span class="sr-only">Toggle navigation</span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</button>
			  <a class="navbar-brand" href="/"><img class="img-responsive toplogo" src="https://recruiterintel.com/images/logo-recruiter-intel.svg"></a>
			</div>
			 <div id="navbar1" class="collapse navbar-collapse">
					@if(Auth::guest())	
						<ul class="nav navbar-nav">
							<li class="active1"><a href="/about">About Us</a></li>
							<li><a href="/faq">FAQ</a></li>
							<li><a href="/#contact-section">Contact Us</a></li>
							<li><a href="/automated-emails">Email Automation</a></li>
							<li><a href="/#latest-openings">Sample Data</a></li>
						</ul>
						<ul class="nav navbar-nav navbar-right">
							<li> <a data-toggle="modal" data-target="#login-modal" href="#" class="login-btn">Login</a></li>
							@if(request()->route()->getName() == "welcome")
								
							@elseif(request()->route()->getName() != "unsubscribe")
							  
								<li>
									<a  class="trial-btn inline" href="#subsform" >Start Trial</a>								
								</li>							  
							@endif
						</ul>
				   @else
						
				   <ul class="nav navbar-nav">
						<li><a href="/change-password">Account</a></li>
						<li><a href="/userpref">Preferences</a></li>
						<li><a href="/platform">New Openings</a></li>
						@if (Auth::user()->type == 1)
							<li><a href="/platform-hiring-manager">My Hiring Managers</a></li>
							<li><a href="/platform-candidate-activity">Candidate Activity</a></li>
							<li><a href="/platform-candidate">My Candidates</a></li>
						@endif	
						<li><a href="#" data-toggle="modal" data-target="#inviteColleagueModal" href="#inviteColleagueModal" >Invite Colleague</a></li>
					</ul>
					<ul class="nav navbar-nav navbar-right">
							<li class="user-name-li">
								<a   href="#" ><span class="user-name">Welcome {{ucfirst(Auth::user()->name)}}</span></a>
									
							 </li>
						<li><a class="login-btn" href="/logout">Logout</a></li>
					</ul>

				   @endif
			</div>
		  </div>
		</nav>
		
	</div>
    <span id="back-btn2" class="tophead-btn side-bkbtn"><img src="images/arrow-right.png"></span>
	
	<!-- end of top header -->

 <!-- Login Modal -->

	<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
			<div class="modal-dialog">
				<div class="loginmodal-container">
					<h1>Login to Your Account</h1><br>
					<form  action="{{ URL::to('/login')}}" class="col-md-12"   id="subsribe-form"  method="post">                        
							{{ csrf_field() }}    
					<input type="text" name="email" placeholder="email" required>
					<input type="password" name="password" placeholder="password" required>
					<input type="submit" name="login" class="login loginmodal-submit" value="Login">
				  </form>
					<h5 style="text-align:center"><a data-toggle="modal" data-target="#forgotpwModal" href="#forgotpwModal">Forgot Password</a></h5>
				</div>
			</div>
		</div>
	 <!-- Login Modal -->
		  <!-- Forgot pw Modal -->
		  
		  <form  action="{{ URL::to('mail-forgot-password')}}" class="form-inline"   id="forgotpw-form"  method="post">                        
				{{ csrf_field() }}    
			<div class="modal fade" id="forgotpwModal" role="dialog">
				<div class="modal-dialog modal-sm">
				<div class="modal-content">
						<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Forgot Password?</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
							
						<h5 style="    margin-top: auto;color: #666;font-weight: 400;">No problem! <br><br>Please enter the email associated with your account and we will send you an email with instructions to reset your password.<br><br></h5>
							<input type="email" class="form-control" name="email" placeholder="Email" id="email">
							</div>
							<button type="submit" class="btn btn-default">Reset Password</button>
						
						</div>
				
				</div>
				</div>
			</div>
		  </form>	
		<!-- Forgot pw Modal -->
	


		

     		 <!-- invite colleague -->
			  @if (Auth::check())
                <form  action="{{ URL::to('invite-colleague')}}"    id="invite-colleague-form"  method="post">                        
					{{ csrf_field() }} 
					<input type="hidden" name="remaining_invites" id="remaining_invites" value="@if(isset(Auth::user()->remaining_invites)) {{Auth::user()->remaining_invites}} @endif">
					<div class="modal fade" id="inviteColleagueModal" role="dialog">
						<div class="modal-dialog modal-md">
						<div class="modal-content">
								<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Invite Colleague</h4>
								<h6>Remaining Invites: @if(isset(Auth::user()->remaining_invites)) {{Auth::user()->remaining_invites}} @endif</h6>
								</div>
								<div class="modal-body">
									
									<div class="form-group">
									<label class="control-label">Your Colleague's Email <span style="color: red;">*</span></label>
									<input class="form-control" type="email" required id="invite_colleague_email" name="invite_colleague_email" value="">
									</div>
		
									<div class="form-group">
									<label class="control-label">Your Colleague's Name <span style="color: red;">*</span></label>
									<input class="form-control" type="text" required id="invite_colleague_name" name="invite_colleague_name" value="" placeholder="">
									</div>
		
									<div class="form-group">
									<label class="control-label">Your Message</label>
									<textarea class="form-control"  id="invite_colleague_message" name="invite_colleague_message" rows="3" placeholder=""></textarea>
									</div>
		
									<div class="form-group text-right">
										@if(isset(Auth::user()->type)&&Auth::user()->type == 1)
										<input style="margin-right:2px" type="checkbox" name="donotsendemail" id="donotsendemail"> &nbsp;Do not send email
										@endif
										<button onclick="inviteColleague()" class="btn btn-primary send-btn" type="button" style="margin-top: 12px;">Send Invitation</button>
									</div>
				   
						</div>
						</div>
					</div>
					</div>
			  </form>	
			@endif 
			<!-- invite colleague -->
	
			
	
			<script>
					
		   function inviteColleague() {
		   
			   var data = $("#invite-colleague-form").serialize();
			   
			   if($("#remaining_invites").val() <= 0) { 
				   alert("You have no remaining invites!!!"); return false; 
				}

			   if($("#invite_colleague_email").val() == "") {
				alert("Pls provide colleague email!!!"); return false; 
			   }
			   
			  if($("#invite_colleague_name").val() == "") {
				alert("Pls provide colleague name!!!"); return false; 
			   }

			   if (!validateEmailBetter($("#invite_colleague_name").val())) { alert("Please enter a valid email"); return false; }


			   $("#loading").show();
		   
			   $.ajax({
				   type: "get",
				   url: baseurl + '/invite-colleague',
				   data: data + "&_token=" + _token,
				   success: function(res) {
					   $("#inviteColleagueModal").modal("hide");
					   if (res.success) {
						   alert("Successfully sent Recruiter Intel invitation!");
					   } else alert("Unable to send");
					   $("#loading").hide();
				   }
		   
			   });
		   }


		   function validateEmailBetter(email) {
			var re = /^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
//    			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return true;
    			return re.test(String(email).toLowerCase());
			}
				 </script>
					   

		
