

@if(isset($status))
<div class="alert alert-success registered_status">
     {{$status}}
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
                <!--link rel="stylesheet" href="https://recruiterintel.com/Newsletter-Subscription-Form.css"-->
                <!--link rel="stylesheet" href="https://recruiterintel.com/Pretty-Header.css"-->
                <!--link rel="stylesheet" href="https://recruiterintel.com/styles.css"-->
                
                <!-- General Header CSS -->
                <link rel="stylesheet" href="css/style-header.css">
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
                
                <!-- Fonts -->
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Abel">
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,600,700">
                <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700">
                
                <!-- JS -->
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
                <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
                <script src="https://recruiterintel.com/js/jquery.colorbox.js"></script>

            </head>
    
            <body cz-shortcut-listen="true"  id="login-page">
                    <div id="loading" style="width:200px;height:100px;position:absolute;left:50%;top:3%;z-index:9999;display:none"><img src="https://recruiterintel.com/images/loading.gif"> </div>
                   
                    <!-- top header -->
                    <div id="top-header">
                        <div class="row">
                            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12 toplogo-container">
                                <img class="img-responsive top-logo" src="https://recruiterintel.com/images/logo-recruiter-intel.svg">
                            </div>
                            <div class="col-lg-10 col-md-9 col-sm-6 col-xs-12 topmenu">
                                
                            </div>
                        </div>
                    </div>
                    <!-- top header -->   
        

        @yield('content')
    

	<!-- footer -->
	<div class="footer-dark">
            <footer>
                <p class="copyright">© 2018 Recruiter Intel LLC. All rights reserved.</p>
            </footer>
        </div>
        <!-- footer -->	
        <script>
          /*  $('#reset-form').click(function(){
                $('#acct-form').trigger("reset");
                $('#acct-form').find('input[type=checkbox]:checked').removeAttr('checked');
                $('#acct-form').find('input[type=text], input[type=email], input[type=password]').reset();
                return false;
            }); */
        </script>
      
      </body>
    </html>    
