<!--
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
                <div class="col-md-12"><img class="img-responsive" src="top-logo2.png" style="text-align:center; display: inline-block; margin-bottom: 50px;">
                
                </div>
                <div class="col-md-12">
                    <div class="row four-box">
                        <div class="col-md-3">
							<a href="#">
							<img src="bell.png"/></br>
							<h3>Instant alerts on new roles opening</h3>
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
							<h3>Detailed Intel on each new role</h3>
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
        <div class="container">
            <div class="intro">
                <h2 class="text-center text-primary" style="font-size:40px;padding:0px;margin:8px;padding-bottom:0px;/*height:91px;*/padding-top:-3px;font-family:Abel, sans-serif;">Unsubscribe from our email list</h2>
            </div>            
                    <form  action="/unsubscribe-action" class="form-inline"   id="subsribe-form"  method="get">                        
                        {{ csrf_field() }}                

            <div class="form-group">
                <input class="form-control" type="email" required id="email" name="email" placeholder="Your Email">
                <input class="form-control" type="text" required id="reason" name="reason" placeholder="Your Reason for Unsubscribing">
            </div>
			<div class="form-group"><button class="btn btn-primary" type="submit">Unsubscribe </button></div>
           </form>
        </div>
    </div>
    <div class="footer-dark">
        <footer>
            <div class="container">
                <p class="copyright">© 2018 RoleSentry. All rights reserved.</p>
            </div>
        </footer>
    </div>
    <script src="./fr-js/jquery.min.js.download"></script>
    <script src="./fr-js/bootstrap.min.js.download"></script>


</body></html>

-->



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
    
    <link rel="stylesheet" href="css/style.css">
    <!-- New Header CSS -->
    <link rel="stylesheet" href="css/style-new-header.css"> <!-- new header style -->
    
     <!-- Homepage CSS -->
    <link rel="stylesheet" href="css/homestyle.css">
    <link rel="stylesheet" href="http://recruiterintel.com/colorbox.css">
    <link rel="stylesheet" href="css/popout.css">
    <link rel="stylesheet" href="css/style-popout.css">
    <link rel="stylesheet" href="css/style-why-section.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700" rel="stylesheet">
    <script>
			var baseurl = "{{url ('/')}}";
			//alert(baseurl);
			var _token = '<?php echo csrf_token(); ?>';
	</script>
</head>

<body cz-shortcut-listen="true" >

        @include("layouts.partials._header-user-nav")

	<div id="banner-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					<img src="images/logo-recruiter-intel.svg" class="logo"/>
				</div>
			</div>
			<div class="row">
                <div class="col-md-12">
                    <div class="row four-box">
                        <div class="col-md-3">
							<a href="#">
							<img src="images/new_mail.png" class="fb-icon"/>
							<h3>Instant alerts on new roles opening</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="images/network.png" class="fb-icon"/>
							<h3>10,000+ companies followed</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="images/openings-report.png" class="fb-icon"/>
							<h3>Detailed Intel on each new role</h3>
							</a>
						</div>
						<div class="col-md-3">
							<a href="#">
							<img src="images/gear.png" class="fb-icon"/>
							<h3>Fully customizable alert options</h3>
							</a>  
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>

    <div class="newsletter-unsubscribe">
        <ul class="list-unstyled fa-ul"></ul>
        <div class="container">
            <div class="intro">
                <h2 class="text-center text-primary">Unsubscribe from our Email List</h2>
            </div>
           
            <form  action="/unsubscribe-action" class="form-inline"   id="subsribe-form"  method="get">                        
                {{ csrf_field() }}                

                <div class="form-group">
                    <input class="form-control input-lg" type="email" required id="email" name="email" placeholder="Your Email">
                    <textarea class="form-control input-lg" required id="reason" name="reason" placeholder="Your Reason for Unsubscribing"></textarea>
                    <button class="btn btn-primary" type="submit">Unsubscribe </button>
                </div>
             </form>


        </div>
    </div>
	
    <div class="footer-dark">
        <footer>
            <div class="container">
                <p class="copyright">© 2018 Recruiter Intel. All rights reserved.</p>
            </div>
        </footer>
    </div>


    <script src="./fr-js/jquery.min.js.download"></script>
    <script src="./fr-js/bootstrap.min.js.download"></script>

</body>
</html>

