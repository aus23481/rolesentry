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

	
</head>

<body cz-shortcut-listen="true">
	
	<div id="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					<h2>Start your free <b>Recruiter Intel</b> trial today!</h2>
					<p><a class="inline cta-btn" href="#subsform">START FREE TRIAL</a></p>
				</div>
			</div>
		</div>
	</div>
	
	<div id="four-iconbox">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div style="text-align:left;" class="row four-box">
                        <div class="col-md-3">
							<a href="#">
							<img src="images/new_mail.png"/>
							<h3>Email alerts on new roles opening</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="images/network.png"/>
							<h3>10,000+ companies followed</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="images/admin.png"/>
							<h3>Identified Hiring Managers </h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="images/gear.png"/>
							<h3>Fully customizable alert options</h3>
							</a>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	
	<div style="display:none">
		<div id="subsform" class="newsletter-subscribe">
			<div class="intro">
				<h2 class="text-center text-primary">Start Your Free <b>Recruiter Intel</b> Trial Today</h2>
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
				<textarea rows=5 style="width: 520px; height:74px" name="describe" id="describe" required placeholder="Your Target Industries and Locations" class="form-group form-control"></textarea>
				</div>
				<div class="form-group md-col-6 form-inline">
				<div class="form-group"><button class="btn-primary" type="submit">Start Free Trial</button></div>
				</div>
			</form>
		</div>
	</div>
	

    <div class="features-boxed">
            <div class="row features">
                <div class="item">
				<h2>Latest Openings in {{$location_name}}</h2>

				<div class="bs-example">
					<div id="myCarousel" class="carousel slide" data-interval="6000"  data-ride="carousel">
						<!-- Carousel indicators -->
						<ol class="carousel-indicators">
							<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
							<li data-target="#myCarousel" data-slide-to="1"></li>
							<li data-target="#myCarousel" data-slide-to="2"></li>
						</ol>   
						<!-- Wrapper for carousel items -->
						<div class="carousel-inner">
                          
                         
                            
                                <?php 
                                
                                  $box = 1;
                                ?>
                                 @foreach($alerts as $alert)
                                    @if($box == 1) <div class="active item">
                                    @elseif(($box-1)%3 == 0) <div class="item">
                                    @endif       
                                    <div class="box">
						<span class="role-type">{{$alert->job_type}}</span><div class="arrow-right"></div>
                                    <h3 class="timehead"><img src="images/time.png">{{$today}}</h3>
                                        
                                        <div style="height:100px;" class="title-city">
                                        <a style="text-decoration:underline;color:#3db164" class="rtitle" href="{{$alert->job_description_link}}">{{$alert->title}}</a><br>
                                        <span class="city">{{$alert->location}}</span>
                                        </div>
                                        
                                        <div style="height:110px" class="recruiter-info">
                                            <span class="hr-title">Hiring Manager</span><br>
                                        <span class="hr-name">{{$alert->hiring_manager}} <br><a href="{{$alert->hiring_manager_linkedin}}"><img style="margin-top:-2px;" width=20 height=20 src="http://recruiterintel.com/Linkedin.png"></a></span><br>
                                        <span class="company-name">{{ucwords($alert->company)}}</span>
                                        </div>
                                        
                                    </div> <!--box -->
                                   <?php
                                     $box++;
                                     if(($box-1)%3==0) print '</div><!--item -->';                                     
                                   ?> 
                                @endforeach
                                
							
						</div>
						<!-- Carousel controls -->
						<a class="carousel-control left" href="#myCarousel" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left"></span>
						</a>
						<a class="carousel-control right" href="#myCarousel" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right"></span>
						</a>
					</div>
				</div>
			</div>
        </div>
    </div>


	<div id="report-section">
		<div class="container">
            <div class="row">
                <div style="padding-bottom:27px;" class="col-md-4">
					<img src="images/openings-report.png"/>
					<h3>New Openings Report</h3>
					<p>We cover over ten thousand small to mid sized companies and send you daily reports on all new positions opened everyday in your territories, customized for you.</p>
					<a class="inline" href="#subsform">start free trial</a>
				</div>
				<div style="padding-bottom:27px;" class="col-md-4">
					<img src="images/hiring-manager.png"/>
					<h3>Hiring Manager Report</h3>
					<p>We identify the hiring managers through a combination of machine learning and industry research and send you daily reports so you can spend more time closing, and less time researching.</p>
					<a class="inline" href="#subsform">start free trial</a>
				</div>
				<div style="padding-bottom:27px;" class="col-md-4">
					<img src="images/role-alert.png"/>
					<h3>Instant High Value Role Alerts</h3>
					<p>When high value positions that you are interested in open up in your territories, we send you an instant email alert so you can capture the opportunity before the competition.</p>
					<a class="inline" href="#subsform">start free trial</a>
				</div>
			</div>
		</div>
	</div>

   <div class="footer-dark">
        <footer>
            <div class="container">
                <p class="copyright">© 2018 Recruiter Intel LLC. All rights reserved.</p>
            </div>
        </footer>
    </div>
	
<script>
	$(document).ready(function(){
		$(".inline").colorbox({inline:true, width:"80%"});
	});
</script>
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

