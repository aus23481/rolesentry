
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FAQs</title>
		
		<!-- Bootstrap & Icons CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/4.0.0-14/css/ionicons.min.css">

		<!-- Fonts -->
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700">
		<link rel="stylesheet" href="css/homestyle.css">
        
        <!-- Homepage CSS -->
		<link rel="stylesheet" href="css/homestyle.css">
		<link rel="stylesheet" href="https://recruiterintel.com/colorbox.css">
		<link rel="stylesheet" href="css/popout.css">
		<link rel="stylesheet" href="css/style-popout.css">
		<link rel="stylesheet" href="css/style-why-section.css">
		
        
        
        <!-- New Header CSS -->
		<link rel="stylesheet" href="css/style-new-header.css"> <!-- new header style -->
		
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

  <body cz-shortcut-listen="true"  id="faq-page">
  
	<!-- top header -->	

    @include("layouts.partials._header-user-nav")
    
	<!-- end of top header -->
	
	<!-- about content sections -->
	<div id="faq-section-1">
		<div class="row">
			<div class="container">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="faq-text">
						<h1>FAQs</h1>
						<hr class="orange-line"/>
						<p>Have a question in mind?</p>
						
						<div id="search-container"> 
							<div class="input-group stylish-input-group">
								<input type="text" class="form-control"  placeholder="Write a phrase or a keyword..." >
								<span class="input-group-addon">
									<button type="submit">
										<span class="glyphicon glyphicon-search"></span>
									</button>  
								</span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="container">
				<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
					<div class="panel-group" id="accordion">
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
							How do I customize the email alerts? <span class="glyphicon glyphicon-chevron-down"></span></a>
						  </h4>
						</div>
						<div id="collapse1" class="panel-collapse collapse in">
						  <div class="panel-body">Once logged in to the platform, please click on the "Preferences" link in the top navigation.  This will open the preferences page where you can customize your daily email alerts.  You can customize by job type (ex: Tech) or to get even more specific, job subtype (ex: Front end, devops, design)</div>
						</div>
					  </div>
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
							Can I see all new openings - not just the ones with hiring managers?<span class="glyphicon glyphicon-chevron-down"></span></a>
						  </h4>
						</div>
						<div id="collapse2" class="panel-collapse collapse">
						  <div class="panel-body">Yes. Please uncheck the "Only show roles with Hiring Manager" checkbox in the platform, which is located to the left of the searchbox.  This will show all roles for your search criteria, not just the ones we have attached hiring manager intel for.		</div>
						</div>
					  </div>
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
							Can I track a specific hiring manager? <span class="glyphicon glyphicon-chevron-down"></span></a>
						  </h4>
						</div>
						<div id="collapse3" class="panel-collapse collapse">
						  <div class="panel-body">Yes. Please click on the star icon next to the hiring manager's name that you wish to follow.  You will be alerted to any new roles that this hiring manager opens.  You can select to be notified by email instantly as soon as we detect a new role opened by any hiring manager that you follow, or you can have all your alerts sent once daily in an easy to read digest.</div>
						</div>
					  </div>
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse4">
							Is Recruiter Intel available to job seekers? <span class="glyphicon glyphicon-chevron-down"></span></a>
						  </h4>
						</div>
						<div id="collapse4" class="panel-collapse collapse">
						  <div class="panel-body">No.  Our service is specfically tailored for recruiters.  Job boards like Indeed, CareerBuilder and Monster currently provide a great service for job seekers.</div>
						</div>
					  </div>
					  <div class="panel panel-default">
						<div class="panel-heading">
						  <h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapse5">
							How should I contact the hiring managers you identify for new openings? <span class="glyphicon glyphicon-chevron-down"></span></a>
						  </h4>
						</div>
						<div id="collapse5" class="panel-collapse collapse">
						  <div class="panel-body">Our most successful subscribers only reach out to hiring managers that we identify if they are already working with a candidate who would be great for the role.</div>
						</div>
					  </div>




                                          <div class="panel panel-default">
                                                <div class="panel-heading">
                                                  <h4 class="panel-title">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse6">
                                                        How do you filter out low-value and third party roles? <span class="glyphicon glyphicon-chevron-down"></span></a>
                                                  </h4>
                                                </div>
                                                <div id="collapse6" class="panel-collapse collapse">
                                                  <div class="panel-body">Our proprietary monitoring software looks for specific keywords in job titles to exclude and parses job descriptions to look for signs that it was posted by a third party and not the hiring company.  We also in some cases manually curate our openings to provide you with the best possible results.</div>
                                                </div>
                                          </div>

</div>

				</div>
				<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
					<div class="most-popular-filter">
						<h3>Most Popular</h3>
							<ul>
								<li><a data-toggle="collapse" href="#collapse1">How do I customize the email alerts?</a></li>
								<li><a data-toggle="collapse" href="#collapse3">Can I track a specific hiring manager?</a></li>
								<li><a data-toggle="collapse" href="#collapse4">Is Recruiter Intel available to job seekers?</a></li>
							</ul>
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
						<h4>How can we help?</h4>
						<hr class="orange-line"/>
						<p>Need more detailed inquiries? Feel free to contact us.</p>
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
