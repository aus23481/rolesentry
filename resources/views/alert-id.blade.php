<!DOCTYPE html>
<!-- saved from url=(0022)http://rolesentry.com/ -->
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Intel - Job Alert</title>
    <link rel="stylesheet" href="./alert-id/css/bootstrap.min.css">
    <link rel="stylesheet" href="./alert-id/css/font-awesome.min.css">
    <link rel="stylesheet" href="./alert-id/css/ionicons.min.css">
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500i,700" rel="stylesheet">
    <link rel="stylesheet" href="./alert-id/css/Features-Boxed.css">
    <link rel="stylesheet" href="./alert-id/css/Footer-Dark.css">
    <link rel="stylesheet" href="./alert-id/css/Navigation-Menu.css">
    <link rel="stylesheet" href="./alert-id/css/Newsletter-Subscription-Form.css">
    <link rel="stylesheet" href="./alert-id/css/Pretty-Header.css">
    <link rel="stylesheet" href="./alert-id/css/styles.css">
    <link rel="stylesheet" href="./alert-id/css/Testimonials.css">
</head>

<body cz-shortcut-listen="true">
    <div id="top-header">
        <div class="container">
            <div class="row">
                <div class="col-md-12"><img class="img-responsive" src="./alert-id/images/RecruiterIntelLogo.png" style="text-align:center; display: inline-block;"></div>
            </div>
        </div>
    </div>
    
    <div class="content-section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-6">
					<div class="info-box">
						<h3 class="info-box-title">Company</h3><div class="arrow-right"></div>
                    <img src="@if(isset($alert->job->company->logo_url)&&strlen($alert->job->company->logo_url)>20){{$alert->job->company->logo_url}} @else ./alert-id/images/no-company-logo.png @endif" class="company-logo"/>
                    <div style="padding-left:15%"><h3>@if(isset($alert->job->company->name)) {{ucwords($alert->job->company->name)}}@endif</h3></div>
					</div>
                   <div class="info-box">
						<h3 class="info-box-title">Hiring Manager</h3><div class="arrow-right"></div>
						<div class="row">
							<div class="col-md-4">
								<img src="@if(isset($alert->job->hiring_manager_image)) $alert->job->hiring_manager_image @else ./alert-id/images/hmprofile.jpg @endif" class="profile-pic"/>
							</div>
							<div class="col-md-8">
                            <p class="hm-name">@if(isset($alert->job->hiring_manager)) {{ucwords($alert->job->hiring_manager)}} @else ... @endif</p>
								<a href="@if(isset($alert->job->hiring_manager_linkedin)) {{$alert->job->hiring_manager_linkedin}}@endif" class="linkedin-icon"><img src="./alert-id/images/linkedin-icon.svg"/></a>
							</div>
						</div>
					</div>
					<div class="info-box">
						<h3 class="info-box-title">Date Opened</h3><div class="arrow-right"></div>
						<p class="date-opened">{{date('l M j, ga', strtotime('$alert->created_at'))}}</p>
					</div>
                </div>
				<div class="col-md-8 col-sm-6">
					<div class="text-desc">
                        <h1>@if(isset($alert->job->title)) {{$alert->job->title}}@endif</h1>

						<a href="@if(isset($alert->job->job_description_link)){{$alert->job->job_description_link}}@endif" class="button">View Full Job Description on {{$alert->job->company->name}} Career Page</a>
                        @if(isset($alert->job->job_description_summary)) 
                         <p>{!!$alert->job->job_description_summary!!}</p>
                        @else
						<p> 
                            No Job Description Summary Found
                        </p>
                        @endif
					</div>
				</div>
            </div>
        </div>
    </div>

    <div class="footer-dark">
        <footer>
            <div class="container">
                <div class="row">
                </div>
                <p class="copyright">Recruiter Intel © 2018</p>
            </div>
        </footer>
    </div>
    <script src="./alert-id/js/jquery.min.js.download"></script>
    <script src="./alert-id/js/bootstrap.min.js.download"></script>


</body></html>
