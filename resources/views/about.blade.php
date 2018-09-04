
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>About Recruiter Intel</title>
		
		<!-- Bootstrap & Icons CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-14/css/ionicons.min.css">

		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700">
		<link rel="stylesheet" href="css/homestyle.css">
		<!-- New Header CSS -->
        <link rel="stylesheet" href="css/style-new-header.css"> <!-- new header style -->
        
         <!-- Homepage CSS -->
		<link rel="stylesheet" href="css/homestyle.css">
		<link rel="stylesheet" href="https://recruiterintel.com/colorbox.css">
		<link rel="stylesheet" href="css/popout.css">
		<link rel="stylesheet" href="css/style-popout.css">
		<link rel="stylesheet" href="css/style-why-section.css">
		
		<!-- Pages CSS -->
		<link rel="stylesheet" href="css/style-about-page.css"> <!-- about page style -->
		<link rel="stylesheet" href="css/style-faq-page.css"> <!-- faq page style -->
		
		<!-- Contact Section CSS -->
		<link rel="stylesheet" href="css/style-contact-section.css"> <!-- contact section style -->
		
		<!-- General Footer CSS -->
		<link rel="stylesheet" href="https://recruiterintel.com/css/style-footer.css">

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
	</head>

  <body cz-shortcut-listen="true"  id="about-page">
  
        @include("layouts.partials._header-user-nav")
	<!-- end of top header -->
	
	<!-- about content sections -->
	<div id="about-section-1">
		<div class="row">
			<div class="container">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="about-text">
						<h1>About Us</h1>
						<hr class="orange-line"/>
						<p>Our powerful platform helps you keep your finger on the pulse of your specific job market by alerting your to the newest openings and the hiring managers behind them.<br><br>Our proprietary job board monitoring software never misses a beat, so say goodbye to checking Indeed, Careerbuilder, Monster or any other job board ever again.<br><br>We filter out low-value roles and do all the homework for you.  We provide you with the hiring manager and other critical intel on the opening so you can spend less time researching and more time matching great candidates with great companies.</p>

					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="about-section-2">
		<div class="row">
			<div class="container">
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="about-quote">
						<span>Our Goal</span>
						<hr class="orange-line"/>
						<p class="quote-text">Provide recruiters with a strategic advantage in today's hot job markets using cutting edge tech</p>

						<p class="quote-moretext">
	Recruiter Intel intersects the latest advances in monitoring technology with machine learning algorithms to provide a comprehensive view of any local job market.
				</p>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
					<div class="about-graphic">
						<img class="img-responsive" src="images/about-graphic-img.png">
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div id="about-section-3">
		<div class="row">
			<div class="container">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="about-wedo">
						<h2>What We Do</h2>
						<hr class="orange-line"/>
						<div class="row">
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
								<img class="img-responsive wwd-icons" src="images/icons-01.png">
								<p>Our experienced researchers leverage our technology to tap a wide array of sources and provide recruiters a competitive edge.</p>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
								<img class="img-responsive wwd-icons" src="images/icons-02.png">
								<p>Alerts on all new openings in your specific niche via email, delivered daily, full of valuable intel to help you win the role.</p>
							</div>
							<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
								<img class="img-responsive wwd-icons" src="images/icons-03.png">
								<p>Our powerful search platform enables you to track hiring managers and companies while getting a rich local view of any job market.</p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
    
   <!--  
	<div id="contact-section">
		<div class="row">
			<div class="container">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="contact-content">
						<h4>Questions? We'd love to help</h4>
						<hr class="orange-line"/>
						<p>Send us your questions and we'll answer as soon as we can.</p>
						<form action="">
						  <div class="form-group">
							<div class="col-md-6">
								<input type="text" class="form-control" placeholder="Your Name">
							</div>
							<div class="col-md-6">
								<input type="email" class="form-control" placeholder="Your Email">
							</div>
						  </div>
						  <div class="form-group">
							<div class="col-md-12">
								<textarea class="form-control">Write your questions here.. </textarea>
							</div>
						  </div>
						  
						  <button type="submit" class="btn btn-default">Submit</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
-->

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

 <!-- subform -->
   
 @include("subform")
 
   <!-- subform -->

    
    <!-- end of about content sections -->
	
	<!-- footer -->
	<div class="footer-dark">
		<footer>
			<p class="copyright">© 2018 Recruiter Intel LLC. All rights reserved.</p>
		</footer>
	</div>
	<!-- end of footer -->	


 	
<script>
        $(document).ready(function(){
            $(".inline").colorbox({inline:true, width:"90%"});
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




  </body>
</html>        
