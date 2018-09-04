
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link rel="shortcut icon" href="user-preferences/images/favicon.ico" type="image/x-icon">
		
		<title>Role Sentry - Preferences</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-14/css/ionicons.min.css">
		<link rel="stylesheet" href="http://recruiterintel.com/Features-Boxed.css">
		<link rel="stylesheet" href="http://recruiterintel.com/Footer-Dark.css">
		<link rel="stylesheet" href="http://recruiterintel.com/Navigation-Menu.css">
		<link rel="stylesheet" href="http://recruiterintel.com/Newsletter-Subscription-Form.css">
		<link rel="stylesheet" href="http://recruiterintel.com/Pretty-Header.css">
		<link rel="stylesheet" href="http://recruiterintel.com/styles.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
		
		<!-- css, js and fonts added -->
		<link rel="stylesheet" href="css/homestyle.css">
		<link rel="stylesheet" href="colorbox.css">
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="js/jquery.colorbox.js"></script>

		<link rel="stylesheet" type="text/css" href="user-preferences/css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="user-preferences/css/bootstrap-grid.css">
		<link rel="stylesheet" href="css/homestyle.css">
		<link rel="stylesheet" type="text/css" href="user-preferences/css/style.css">
		
		<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
		
    <style>
	 .navbar-collapse {
		 display: hide;
	 }
	</style>

	</head>
	
	<body>
			<div id="top-header">
					<div class="container">
						<div class="row">
							<div class="col-md-6">
								<img class="img-responsive top-logo" src="images/logo-recruiter-intel.svg">
								
							</div>
							<div class="col-md-6">
								<nav class="navbar">
								  <div class="container-fluid">
									<ul class="nav navbar-nav navbar-right">
									   @if(Auth::guest())	
											<li class="dropdown">
												<a data-toggle="modal" data-target="#login-modal" href="#">
												<span class="user-name">Log In</span>
												<span class="glyphicon glyphicon-option-vertical"></span></a>
											</li>
										@else 
											<li class="dropdown">
												<a class="dropdown-toggle1" data-toggle="dropdown" href="#">
												<span class="user-name">Welcome Admin</span>
												<span class="glyphicon glyphicon-option-vertical"></span></a>
												<ul class="dropdown-menu">
												  <li><a href="/change-password"><span class="glyphicon glyphicon-user"></span>Account</a></li>
												  <li><a href="/userpref"><span class="glyphicon glyphicon-star"></span>Preferences</a></li>
												  <li><a href="/logout"><span class="glyphicon glyphicon-off"></span>Logout</a></li>
												</ul>
											</li>
			
										@endif
									</ul>
								  </div>
								</nav>
							</div>
						</div>
					</div>
				</div>
	<div id="top-banner">
		<div class="container">
            <div class="row">
                <div class="col-md-12">
					<h2>Select Your Preferences</h2>
				</div>
			</div>
		</div>
	</div>

	<div id="notif-toggle-section">
		<div class="container">
			<div class="row report-type">
				<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
					<img src="user-preferences/images/openings-report.png"/>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<h3>New Opening Report</h3>
					<p>Daily Email detailing all the new openings from today.</p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<label class="toggle">
					<input type="checkbox" id="new_opening_report" onclick="toggle(this,event)" style="display: none;"/>
					<div data-off="Off" data-on="On">Notification</div>
					</label>
				</div>
			</div>
			<div class="row report-type">
				<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
					<img src="user-preferences/images/hiring-manager.png"/>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<h3>Hiring Manager Report</h3>
					<p>Daily Email detailing all active hiring managers in your area.</p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<label class="toggle">
					<input type="checkbox" id="hiring_manager_report" onclick="toggle(this,event)" style="display: none;"/>
					<div data-off="Off" data-on="On">Notification</div>
					</label>
				</div>
			</div>
			<div class="row report-type">
				<div class="col-lg-1 col-md-1 col-sm-12 col-xs-12">
					<img src="user-preferences/images/role-alert.png"/>
				</div>
				<div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
					<h3>High Value Role Alert</h3>
					<p>Instant Email alert when a new high value role opens in your area.</p>
				</div>
				<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
					<label class="toggle">
					<input type="checkbox" id="high_value_role_report" onclick="toggle(this,event)" style="display: none;"/>
					<div data-off="Off" data-on="On">Notification</div>
					</label>
				</div>
			</div>
		</div>
	</div>
	
	<form  action="{{ URL::to('save-user-preferences')}}"   id="user-prefrences-form" enctype="multipart/form-data" method="post">
		{{ csrf_field() }}

	<div id="content-section1">
		<div class="container">
			<div class="row titles">
				<div class="col-md-15 col-xs-3"></div>
				@foreach($locations as $location)
				<div class="col-md-15 col-xs-3 title">{{$location->name}}</div>
				@endforeach 
				
			</div>
			@foreach($jobtypes as $jobtype)
			<div class="row checklist">
				<div class="col-md-15 col-xs-3 title-2">{{$jobtype->name}}</div>
								
				@foreach($locations as $location)
				<?php 
					$up = "up_".$jobtype->id."_".$location->id;
			  	 ?>
				<div class="col-md-15 col-xs-3 check"><label class="check-container">
					
					<input type="checkbox" id="up_{{$jobtype->id}}_{{$location->id}}" name="up_{{$jobtype->id}}_{{$location->id}}" @if(isset(${$up})) checked @endif    >
					<span class="checkmark"></span>
					</label>
					
				</div>
				@endforeach
				
			</div>
		   @endforeach	
			
			<div class="row pref-btn">
					<button type="button" id="btn_select_all" class="btn btn-lg" style="position: absolute" >
							Select All
					</button>
				<button type="submit"  class="btn btn-lg">Save Preferences</button>

				
			</div>
			
		</div>
	</div>
	</form>	
	
	<div id="footer-section">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<p class="copyright">© 2018 Recruiter Intel LLC. All rights reserved.</p>
				</div>
		</div>
	</div>
	
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
	
	<script>
	var new_opening_report = parseInt('<?php print $new_opening_report?>');
	var hiring_manager_report = parseInt('<?php print $hiring_manager_report?>');
	var high_value_role_report = parseInt('<?php print $high_value_role_report?>');
	
	</script>
	<script src="user-preferences.js"></script>
	</body>
</html>