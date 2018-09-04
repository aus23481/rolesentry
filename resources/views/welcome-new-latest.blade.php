<?php

?>

@if (session('registered_status'))
<div class="alert alert-success registered_status">
    {{session('registered_status')}}
</div>
@endif



<!DOCTYPE html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recruiter Intel</title>
  <!-- Bootstrap & Icons CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-14/css/ionicons.min.css">
  
  <!--link rel="stylesheet" href="http://recruiterintel.com/Features-Boxed.css"-->
  <!--link rel="stylesheet" href="http://recruiterintel.com/Footer-Dark.css"-->
  <!--link rel="stylesheet" href="http://recruiterintel.com/Navigation-Menu.css"-->
  <!--link rel="stylesheet" href="http://recruiterintel.com/Newsletter-Subscription-Form.css"-->
  <!--link rel="stylesheet" href="http://recruiterintel.com/Pretty-Header.css"-->
  <!--link rel="stylesheet" href="http://recruiterintel.com/styles.css"-->
  
  <!-- General Header CSS -->
  <!--<link rel="stylesheet" href="css/style-header.css">-->
  <link rel="stylesheet" href="css/style-footer.css">
  
  <!-- Homepage CSS -->
  <link rel="stylesheet" href="css/homestyle.css">
  <link rel="stylesheet" href="https://recruiterintel.com/colorbox.css">
  <link rel="stylesheet" href="css/popout.css">
  <link rel="stylesheet" href="css/style-popout.css">
  <link rel="stylesheet" href="css/style-why-section.css">
  
  <!-- Other Pages CSS -->
  <link rel="stylesheet" href="css/style-platform-redesign.css">
  <link rel="stylesheet" href="css/style-preferences.css">
  <link rel="stylesheet" href="css/style-account.css">
  <link rel="stylesheet" href="css/style-modals.css">

  <!-- New Header CSS -->
  <link rel="stylesheet" href="css/style-new-header.css"> <!-- new header style -->		
  <!-- Pages CSS -->
  <link rel="stylesheet" href="css/style-about-page.css"> <!-- about page style -->
  <link rel="stylesheet" href="css/style-faq-page.css"> <!-- faq page style -->		
  <!-- Contact Section CSS -->
  <link rel="stylesheet" href="css/style-contact-section.css"> <!-- contact section style -->
  
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700">
  
  <!-- JS -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
  <script src="https://recruiterintel.com/js/jquery.colorbox.js"></script>
	<script>
			var baseurl = "{{url ('/')}}";
			//alert(baseurl);
			var _token = '<?php echo csrf_token(); ?>';
	</script>
<script>
window['_fs_debug'] = false;
window['_fs_host'] = 'fullstory.com';
window['_fs_org'] = 'BW3RJ';
window['_fs_namespace'] = 'FS';
(function(m,n,e,t,l,o,g,y){
    if (e in m) {if(m.console && m.console.log) { m.console.log('FullStory namespace conflict. Please set window["_fs_namespace"].');} return;}
    g=m[e]=function(a,b){g.q?g.q.push([a,b]):g._api(a,b);};g.q=[];
    o=n.createElement(t);o.async=1;o.src='https://'+_fs_host+'/s/fs.js';
    y=n.getElementsByTagName(t)[0];y.parentNode.insertBefore(o,y);
    g.identify=function(i,v){g(l,{uid:i});if(v)g(l,v)};g.setUserVars=function(v){g(l,v)};
    y="rec";g.shutdown=function(i,v){g(y,!1)};g.restart=function(i,v){g(y,!0)};
    y="consent";g[y]=function(a){g(y,!arguments.length||a)};
    g.identifyAccount=function(i,v){o='account';v=v||{};v.acctId=i;g(o,v)};
    g.clearUserCookie=function(){};
})(window,document,window['_fs_namespace'],'script','user');
</script>

<script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:897934,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>
 
	
</head>

<body cz-shortcut-listen="true">

		@include("layouts.partials._header-user-nav")

	
	<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
		<div class="modal-dialog">
			<div class="loginmodal-container">
				<h1>Login to Your Account</h1><br>
				<form  action="{{ URL::to('/login')}}" class="col-md-12"   id="subsribe-form"  method="post">                        
						{{ csrf_field() }}    
				<input type="text" name="email" placeholder="email" required data-hj-whitelist>
				<input type="password" name="password" placeholder="password" required>
				<input type="submit" name="login" class="login loginmodal-submit" value="Login">
			  </form>
				<h5 style="text-align:center"><a data-toggle="modal" data-target="#forgotpwModal" href="#forgotpwModal">Forgot Password</a></h5>
			</div>
		</div>
	</div>

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
	
	<!--
	<div id="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					<h2><b>Staffing Leads for Recruiters Reinvented</b><br>Hiring Managers Identified</h2>
					<p><a class="inline cta-btn" href="#subsform">START TRIAL</a></p><h3 style="margin-top:5px;font-size: 18px;color:#d4d4d4">No Credit Card Needed</h3>
				</div>
			</div>
		</div>
	</div>
-->

<div id="banner-section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h2><b>Hiring Managers Identified</b><br>Automated Lead Generation for Agency Recruiters</h2>
<iframe src="https://player.vimeo.com/video/286709232" style="padding-bottom:20px" class="hidden-xs hidden-sm" width="684" height="392" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
<iframe src="https://player.vimeo.com/video/286709232" style="padding-bottom:15px" class="hidden-lg hidden-sm hidden-md" width="348" height="197" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
				<div class="input-group">
					<input type="email" style="height:43px" name="user_email" id="user_email" required class="form-control" placeholder="Email Address">
					<span class="input-group-btn">
						<button id="btn_start_trial" class="inline btn btn-primary" type="button"   href="#subsform">
						</span>Start Trial</button>
					</span>
				</div>
			</div>
		</div>
	</div>
</div>

<!---
	
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
							<img style="width:46px;height:55px" src="images/admin.png"/>
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
	-->

  <!-- new designs start -->
  <!-- subform -->
   
  @include("subform")

  <!-- subform -->



</div>




	<div id="report-section">
		<div class="container">
			 <div class="row">
					<div class="whybox">
					<div class="row">
						<div class="col-lg-6">

						<div class="whyus-text-mobile visible-xs visible-sm">
								<h3 style="margin-top: 0px;  font-weight: bold;">Automated Hiring Manager Prospecting</h3>
<hr style="    /* margin-top: 20px; */
    margin-bottom: 20px;
    border: 0;
    border-top: 4px solid #58759b;">
<p class="explain-font">

Automatically target and reach out to hundreds of new hiring managers each week with our AI Prospecting toolset.  Recruiter Intel does all your prospecting work for you. <br><br>

We monitor job boards and provide new business prospecting for agency recruiters. When new roles open our proprietary software identifies the hiring manager and sends a heavily customized human-like outreach from your email address. <br><br>


</p>

						<div style="padding:0px" class="col-lg-6">
							<a href="#gif1" class="gif1 "><img style="margin-top: 10px;margin-bottom: 15px;" src="images/autoemailtool.gif" width="100%" class="why-gifs"/></a>
						</div>
	

<p class="explain-font">
Deep outreach customization ability puts you in control. Set up different email templates based on job type, location, and keywords in the job title. <br><br>
                                                                <a href="#subsform"  class="why-btn inline hidden-sm hidden-xs">Start Trial</a>
</p>
							</div>

						<div class="whyus-text hidden-xs hidden-sm ">
								<h3 style="margin-top: 0px;  font-weight: bold;">Automated Hiring Manager Prospecting</h3>
<hr style="    /* margin-top: 20px; */
    margin-bottom: 20px;
    border: 0;
    border-top: 4px solid #58759b;">
								<p class="explain-font">

Automatically target and reach out to hundreds of new hiring managers each week with our AI Prospecting toolset.  Recruiter Intel does all your prospecting work for you. <br><br>

We monitor job boards and provide new business prospecting for agency recruiters. When new roles open our proprietary software identifies the hiring manager and sends a heavily customized human-like outreach from your email address. <br><br>

Deep outreach customization ability puts you in control. Set up different email templates based on job type, location, and keywords in the job title. <br><br>

</span>

                                                                <!--<a href="#subsform"  class="why-btn inline hidden-sm hidden-xs hidden-md">Start Trial</a> --!>
</p>
							</div>

						</div>
						<div class="col-lg-6">
							<a href="#gif1" class="gif1 hidden-xs hidden-sm"><img style="margin-top: 10px;margin-bottom: 10px;" src="images/autoemailtool.gif" width="100%" class="why-gifs"/></a>
							<a href="#subsform"  class="hidden-xs hidden-sm visible-md why-btn inline">Start Trial</a>
						</div>
					</div>
				</div>
	
				<div class="whybox">
					<div class="row">
						<div class="col-lg-6 hidden-sm hidden-xs hidden-md">
							<a href="#gif2" class="gif2"><img style="margin-top: 10px;margin-bottom: 10px;" src="images/emailalert.gif" width="100%" class="why-gifs"/></a>
						</div>
						<div class="col-lg-6">

						<div class="whyus-text-mobile visible-xs visible-sm visible-md">
								<h3 style="margin-top: 0px;    font-weight: bold;">Daily Email Alerts</h3>
<hr style="    /* margin-top: 20px; */
    margin-bottom: 20px;
    border: 0;
    border-top: 4px solid #58759b;">
								<p class="explain-font"><span class="visible-xs visible-sm visible-md">Recruiter Intel consolidates openings from all major job boards and curates them for you. Our acclaimed email alerts are <b>customizable by location and specialization.</span> <br>Every new role we identify contains a <b>direct link to the job description and the hiring manager's LinkedIn profile</b>. <span class="hidden-xs hidden-sm"></span>
</span></p>
							</div>
						<div class="whyus-text hidden-xs hidden-sm hidden-md">
								<h3 style="margin-top: 0px;    font-weight: bold;">Daily Email Alerts</h3>
<hr style="    /* margin-top: 20px; */
    margin-bottom: 20px;
    border: 0;
    border-top: 4px solid #58759b;">
								<p class="explain-font"><span class="hidden-xs hidden-sm hidden-md">Recruiter Intel consolidates openings from all major job boards and curates them for you because you should know about new roles before anyone else.<br><br></span>

<p class="explain-font">

Stop waiting for busy hiring managers to tell you when they are hiring. Recruiter Intel keeps you aware of all opportunities in your niche market.
</p>

<p class="explain-font">
<span class="hidden-xs hidden-sm"></span><br>Every new role we identify contains a direct link to the hiring manager's LinkedIn profile. Our acclaimed email alerts are <b>customizable by location, specialization and more</b>.  

</p>
							</div>
						</div>


						<div class="col-lg-6 visible-sm visible-xs visible-md">
							<a href="#gif2" class="gif2"><img style="margin-top: 10px;margin-bottom: 10px;" src="images/emailalert.gif" width="100%" class="why-gifs"/></a>
							<a href="#subsform"  class="visible-xs visible-sm visible-md why-btn inline">Start Trial</a>
						</div>


					</div>
				</div>


	
				<div class="whybox">
					<div class="row">
						<div class="col-lg-6">


							<div class="whyus-text-mobile visible-sm visible-xs">
								<h3 style="margin-top: 0px;font-weight: bold;">Powerful Search Platform</h3>
<hr style="    /* margin-top: 20px; */
    margin-bottom: 20px;
    border: 0;
    border-top: 4px solid #58759b;">
							<p class="explain-font"><span>Leverage our platform to search for new openings by location, job title, hiring manager and much more.</span><br><br><span class="hidden-xs hidden-sm">Recruiter Intel automatically <b>filters out low value roles and 3rd party postings</b>, and allows you to save custom searches and <b>get alerts</b> when new roles in your niche market hit our database.<br><br></span>We aggregate all major job boards and monitor over 10,000 company career pages directly to provide you comprehensive new opening coverage.</p>
								<a href="#subsform"  class="hidden-sm hidden-xs why-btn inline">Start Trial</a> 
							</div>


							<div class="whyus-text hidden-sm hidden-xs">
								<h3 style="margin-top: 0px;font-weight: bold;">Powerful Search Platform</h3>
<hr style="    /* margin-top: 20px; */
    margin-bottom: 20px;
    border: 0;
    border-top: 4px solid #58759b;">
							<p class="explain-font"><span>Leverage our platform to search for new openings by location, job title, hiring manager and much more.</span><br><br><span class="hidden-xs hidden-sm">Recruiter Intel automatically <b>filters out low value roles and 3rd party postings</b>, and allows you to save custom searches and <b>get alerts</b> when new roles in your niche market hit our database.<br><br></span>We aggregate all major job boards and monitor over 10,000 company career pages directly to provide you comprehensive new opening coverage.</p>
							</div>
						</div>

						<div class="col-lg-6">
							<a href="#gif3" class="gif3"><img style="margin-top: 10px;margin-bottom: 10px;" src="images/searchplatform.gif" width="100%" class="why-gifs"/></a>
 <a href="#subsform"  class="visible-xs visible-sm visible-md why-btn inline">Start Trial</a>
						</div>

					</div>
				</div>




		

						
				<div style="display: none;">
					<div id="gif1" style="padding: 30px 30px 0;">
						<img src="images/autoemailtool.gif" width="100%" style="border-radius: 10px"/>
					</div>
					<div id="gif2" style="padding: 30px 30px 0;">
						<img src="images/emailalert.gif" width="100%" style="border-radius: 10px"/>
					</div>
					<div id="gif3" style="padding: 30px 30px 0;">
						<img src="images/searchplatform.gif" width="100%" style="border-radius: 10px"/>
					</div>
				</div>
			</div>
		</div>
	</div>


   <!-- new designs ends -->
    <div class="features-boxed">
		 <div id="latest-openings" class="container features-boxed-title">
            <div class="row">
                <div class="col-md-12">
					<h2 >Latest Openings 
						
						in <span id="location_name_span"> {{$location_name}} </span>					
					</h2>
				  	
				
					</div>
			</div>
			<div class="row">
				<br><br>
					<div class="col-md-12">
							<select  id="job_type_id" onchange="updateRequestedAlert()" >
									@foreach($job_types as $job_type)
								@if($job_type->id == 1 || $job_type->id == 2)
								<option  value="{{$job_type->id}}"> {{$job_type->name}}  </option>
								@endif
									@endforeach
								</select>
								
							<select id="location_id" onchange="updateRequestedAlert()" >
									@foreach($locations as $location)
								@if($location->id == 1 || $location->id == 5)
								<option value="{{$location->id}}" @if($location_id==$location->id) selected @endif> {{$location->name}}  </option>
								@endif
									@endforeach
								</select>
							</div>

			</div>

		</div>
            <div class="row features">
                <div class="item">
				<div class="bs-example">
					<div id="myCarousel" class="carousel slide" data-interval="3400"  data-ride="carousel">
						<!-- Carousel indicators -->
						<div class="carousel-inner">

							                            
                                <?php 
                                
                                  $box = 1;
                                ?>
                                 @foreach($alerts as $alert)
                                    @if($box == 1) <div class="active item">
                                    @elseif(($box-1)%3 == 0) <div class="item">
                                    @endif       
                                    <div class="box">
						
										<!--- new -->


										<span class="role-type">{{$alert->job_type}}</span>
										<h3 class="timehead">Opened - {{date("m/d/Y",strtotime($alert->created_at))}}</h3>
										
										<div class="title-city">
										<a class="rtitle" href="{{$alert->job_description_link}}">{{$alert->title}}</a><br>
										<span class="city">{{$alert->location}}</span>
										</div>
										
										<div class="recruiter-info">
											<div class="row">
												<div class="col-md-4 col-xs-4 col-sm-4">
													<a href="{{$alert->hiring_manager_linkedin}}" class="linkedin-icon"><img src="https://recruiterintel.com/Linkedin.png" class="pop"/></a>
												</div>
												<div class="col-md-8 col-xs-8 col-xs-8">
													<span class="hr-title">Hiring Manager</span><br>
													<span class="hr-name">{{$alert->hiring_manager_name}}</span><br>
													<span class="hr-position">{{$alert->hiring_manager_position}}</span><br>
												</div>
											</div>
											<span class="company-name">{{ucwords($alert->company)}}</span>
										</div>

										<!-- new -->
                                        
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
						<!-- carousel-inner -->
						
					</div>
				</div>
			</div>
        </div>
    </div>
	




	<div id="contact-section">
		<div class="container">
            <div class="row">
                <div class="col-md-12">
					<h2>Questions about our platform?</h2>
               <hr class="orange-line"/>

					<p>One of our representatives will get back to you within 24 hours.</p>
					<form  action="{{ URL::to('/contact-us')}}" class="col-md-12"   id="contact-form"  method="post">                        
						{{ csrf_field() }} 
						<div class="form-group col-md-6">
							<input type="text" class="form-control" name="name" required placeholder="Your Name">
						</div>
						<div class="form-group col-md-6">
							<input type="text" class="form-control" name="email" id="email" required placeholder="Your Email Address">
						</div>
						<div class="form-group col-md-12">
							<textarea class="form-control" name="describe" >Your Message</textarea>
						</div>
						<div class="form-group col-md-12">
							<button class="btn-primary" type="submit">Send Message</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>




	<!--
		<div id="report-section">
		<div class="container">
			 <div class="row">
				<h2>Why Choose Recruiter Intel?</h2>
                <div class="col-md-4">
					<div class="box1">
						<h3>The Reporting</h3>
						<div class="adv-list">
							<img src="images/openings-report.png">
							<h4>New Openings Report</h4>
							<p>We cover over ten thousand small to mid sized companies and send you daily reports on all new positions opened in your territories, everyday.</p>
						</div>
						<div class="adv-list">
							<img src="images/report.png">
							<h4>Hiring Manager Report</h4>
							<p>We identify the hiring managers for positions through a combination of machine learning and industry research.</p>
						</div>
						<div class="adv-list">
							<img src="images/role-alert.png">
							<h4>High Value Role Alerts</h4>
							<p>When very high value positions open up that you are interested in, we send you an instant email alert so you can capture the opportunity before the competition.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="box2">
						<h3>The Platform</h3>
						<div class="adv-list">
							<img src="images/binoculars.png">
							<h4>Search Tools</h4>
							<p>Search open positions by location, job type or particular skill.  We empower you with the only available comprehensive view of local job markets.</p>
						</div>
						<div class="adv-list">
							<img src="images/insight.png">
							<h4>Insight On Companies</h4>
							<p>Valuable insight on companies such as hiring process, department level statistics, funding rounds, organizational structure and more.</p>
						</div>
						<div class="adv-list">
							<img src="images/hiring-manager.png">
							<h4>Hiring Manager Intel</h4>
							<p>Easily search our database and sign up for our email alerts to know who is hiring and what they are looking for specifically.</p>
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div class="box3">
						<h3>The Advantage</h3>
						<div class="adv-list">
							<img src="images/search.png">
							<h4>Job Board Monitoring</h4>
							<p>We monitor all major job boards and provide comprehensive coverage of positions posted directly by the company hiring.</p>
						</div>
						<div class="adv-list">
							<img src="images/company.png">
							<h4>Monitor Companies Directly</h4>
							<p>We monitor over ten thousand companies directly, and let you know the moment they open a new position.</p>
						</div>
						<div class="adv-list">
							<img src="images/relations.png">
							<h4>Develop New Relationships</h4>
							<p>Organically grow your network by being in the know about all the latest roles opening up in your area, and the hiring managers opening them.</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
-->
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
	@if (session('user_id'))	
	<script>
			var user_id = parseInt('<?php print session("user_id")?>');
			window.location = "/change-password?action=new_account&user_id="+user_id;
	</script>
	@endif

	<script src="js/welcome.js"></script>

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
	
	<script>
		

		$(document).ready(function(){
			$(".inline").colorbox({inline:true, width:"80%", height:"auto"});
			if($(window).width() < 480) { 
			$(".inline").colorbox({inline:true, width:"100%", height:"auto"}); 
			}
			$(".gif1").colorbox({inline:true, width:"auto", height:"auto"});
			$(".gif2").colorbox({inline:true, width:"auto", height:"auto"});
			$(".gif3").colorbox({inline:true, width:"auto", height:"auto"});
		});
		
		$('#reset-check-1').click(function(){
			$('#jobtypesbox, #locationsbox').find('input[type=checkbox]:checked').removeAttr('checked');
		});
		
		$("#togglebtn1").click(function () {
			$(".toggletext1").toggleClass('toggletext1b');
		});
		
		$("#togglebtn2").click(function () {
			$(".toggletext2").toggleClass('toggletext2b');
		});
		
		$("#togglebtn3").click(function () {
			$(".toggletext3").toggleClass('toggletext3b');
		});
		
		$("#togglebtn4").click(function () {
			$(".toggletext4").toggleClass('toggletext4b');
		});
		
		$("#togglebtn5").click(function () {
			$(".toggletext5").toggleClass('toggletext5b');
		});
		
		$("#togglebtn6").click(function () {
			$(".toggletext6").toggleClass('toggletext6b');
		});
		
		$("#togglecheckbtn1").click(function () {
			$("#togglecheck1").slideToggle();
			$(".togglechecktxt1a").toggleClass('togglechecktxt1b');
			$("#togglecheck2, #togglecheck3, #togglecheck4, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn2").click(function () {
			$("#togglecheck2").slideToggle();
			$(".togglechecktxt2a").toggleClass('togglechecktxt2b');
			$("#togglecheck1, #togglecheck3, #togglecheck4, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn3").click(function () {
			$("#togglecheck3").slideToggle();
			$(".togglechecktxt3a").toggleClass('togglechecktxt3b');
			$("#togglecheck1, #togglecheck2, #togglecheck4, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn4").click(function () {
			$("#togglecheck4").slideToggle();
			$(".togglechecktxt4a").toggleClass('togglechecktxt4b');
			$("#togglecheck1, #togglecheck2, #togglecheck3, #togglecheck5, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn5").click(function () {
			$("#togglecheck5").slideToggle();
			$(".togglechecktxt5a").toggleClass('togglechecktxt5b');
			$("#togglecheck1, #togglecheck2, #togglecheck3, #togglecheck4, #togglecheck6").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt6a").removeClass('togglechecktxt6b');
			});
			return false;
		});
		
		$("#togglecheckbtn6").click(function () {
			$("#togglecheck6").slideToggle();
			$(".togglechecktxt6a").toggleClass('togglechecktxt6b');
			$("#togglecheck1, #togglecheck2, #togglecheck3, #togglecheck4, #togglecheck5").hide(function () {
				$(".togglechecktxt1a").removeClass('togglechecktxt1b');
				$(".togglechecktxt2a").removeClass('togglechecktxt2b');
				$(".togglechecktxt3a").removeClass('togglechecktxt3b');
				$(".togglechecktxt4a").removeClass('togglechecktxt4b');
				$(".togglechecktxt5a").removeClass('togglechecktxt5b');
			});
			return false;
		});
	</script>
</body></html>
