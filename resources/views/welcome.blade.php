<?php

?>

@if (session('registered_status'))
<div class="alert alert-success registered_status">
    {{session('registered_status')}}
</div>
@endif
<!DOCTYPE html>
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Intel</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-14/css/ionicons.min.css">
    <link rel="stylesheet" href="Features-Boxed.css">
    <link rel="stylesheet" href="Footer-Dark.css">
    <link rel="stylesheet" href="Navigation-Menu.css">
    <link rel="stylesheet" href="Newsletter-Subscription-Form.css">
    <link rel="stylesheet" href="Pretty-Header.css">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cookie">
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
</head>

<body cz-shortcut-listen="true">
    <div id="top-header">
        <div class="container">
            <div class="row">
                    @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="{{ url('/home') }}">Home</a>
                        @else
                        @endauth
                    </div>
                @endif
                <div class="col-md-12">
                               <img class="img-responsive" src="top-logo2.png" style="text-align:center; display: inline-block; margin-bottom: 50px;"> 
                </div>
                <div class="col-md-12">
                    <div class="row four-box">
                        <div class="col-md-3">
							<a href="#">
							<img src="bell.png"/></br>
							<h3>Email alerts on new roles opening</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="binoculars.png"/></br>
							<h3>10,000+ companies followed</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="id.png"/></br>
							<h3>Hiring Managers Identified for each new role</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="gear.png"/></br>
							<h3>Fully customizable alert options</h3>
							</a>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="newsletter-subscribe" style="padding-top: 50px; padding-bottom: 60px;">
        <ul class="list-unstyled fa-ul"></ul>
        <div class="container" style="background-color:#33529878">
            <div class="intro">
                <h2 class="text-center text-primary" style="font-size:40px;padding:0px;margin:8px;padding-bottom:0px;/*height:91px;*/padding-top:-3px;font-family:Abel, sans-serif;">Start Your Free <b>Recruiter Intel</b> Trial Today</h2>
            </div>            
                    <form  action="{{ URL::to('add-home-data')}}" class="col-md-12"   id="subsribe-form"  method="post">                        
                        {{ csrf_field() }}                

		<div class="form-group md-col-6 form-inline">
                		<div class="form-group"><input class="form-control" type="name" required id="name" name="name" placeholder="Your First Name"></div>
                		<div class="form-group"><input class="form-control" type="name" required id="last_name" name="last_name" placeholder="Your Last Name"></div>
		</div>

		<div class="form-group md-col-6 form-inline">
                		<div class="form-group"><input class="form-control" type="phone" required id="phone" name="phone" placeholder="Your Phone number"></div>
                		<div class="form-group"><input class="form-control" type="email" required id="email" name="email" placeholder="Your Email"></div>
		</div>

		<div class="form-group md-col-6 form-inline">
				<textarea rows=5 style="min-width: 520px; height:74px" name="describe" id="describe" required placeholder="Your Target Industries and Locations" class="form-group form-control"></textarea><br>
				<div class="form-group" style="padding:10px"><button style="padding:10px" class="btn-primary" type="submit">Start Free Trial</button></div>
		</div>
            </form>



        </div>
    </div>
 

    <div class="features-boxed">
        <div class="container" style="width:80%">
            <div class="row features">
                <div class="item">
                    
                    @foreach($alerts as $alert)
                    <div style="margin:20px;padding:20px;display:inline-block;width:350px" class="box">
                        <div class="row">
                            <div class="col-md-12" style="/*width:auto;*/color:rgb(86,146,235);font-family:Abel, sans-serif;padding-left:0;padding-right:0;">
                                <h3 class="text-uppercase text-center timehead" style="text-align:center;margin-right:0px;"><img src="time.png">Opened {{$today}}</h3>
                            </div>
                        </div>
                        <h3 class="name"><a style="text-decoration:underline" href="{{$alert->job_description_link}}">{{$alert->title}}</a><br><br>{{$alert->location}}</h3>
                        <h3 class="name">Hiring Manager<br> {{$alert->hiring_manager}} <a href="{{$alert->hiring_manager_linkedin}}"><img width=20 height=20 src="http://recruiterintel.com/Linkedin.png"></a></h3>
                        <p class="description" style="margin-bottom:-4px;"> {{ucwords($alert->company)}}</p>
                        <p class="description hide" style="margin-top:0px;">120 - 140K</p><a href="http://rolesentry.com/#" class="learn-more"> </a></div>
                  @endforeach
                </div>
                
            </div>
        </div>
    </div>






   <div class="footer-dark">
        <footer>
            <div class="container">
                <p class="copyright">© 2018 Recruiter Intel LLC<br> All rights reserved.</p>
            </div>
        </footer>
    </div>
<script type="text/javascript">
_linkedin_data_partner_id = "242484";
</script><script type="text/javascript">
(function(){var s = document.getElementsByTagName("script")[0];
var b = document.createElement("script");
b.type = "text/javascript";b.async = true;
b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
s.parentNode.insertBefore(b, s);})();
</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://dc.ads.linkedin.com/collect/?pid=242484&fmt=gif" />
</noscript>
</body></html>
