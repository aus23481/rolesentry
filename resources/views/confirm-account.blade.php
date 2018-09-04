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
    <script>
            var baseurl = "{{url ('/')}}";
            //alert(baseurl);
            var _token = '<?php echo csrf_token(); ?>';
    </script>
    <style>
    th {
        background-color: #257193;
        color: white;
    } 

    .footer-dark {
    padding: 0px 0;
    color: #f0f9ff;
    background-color: #282d32;
    }

    #favorite_company_list {
        margin-top: -15px;
    }

    #requested_companies {
        margin-top: -15px;
    }

    input[type=checkbox]:after {
    content: " ";
    background-color: #D7B1D7;
    display: inline-block;
    visibility: visible;
 }
    </style>
</head>

<body cz-shortcut-listen="true">
    <div id="top-header">
            <img class="img-responsive" src="top-logo2.png"   style="width:10% !important;text-align:center; position:absolute;left:0px; top:0px;"> 
        <div class="container">
            <div class="row">
                    @if (Route::has('login'))
                    <div class="top-right links">
                        @auth
                            <a href="{{ url('/logout') }}">Logout</a>
                        @else <a href="{{ url('/login') }}">Login</a>
                        @endauth
                    </div>
                @endif
                <div class="clearfix" > <br />  </div>
                <div class="col-md-12" style="min-height:400px;">

                  @if($valid_user)  
                    <div class="alert alert-success">
                        <h2>Thank you for confirming your email!</h2><br>
			<p>Your one-week free trial is now active.  Thank you for trying out Recruiter Intel!<br>Please direct any questions to info@recruiterintel.com<br>
                    </div>
                   @else  
                   <div class="alert alert-warning">
                    <h2>Invalid User Id!</h2>
                   </div>
                  @endif   
                            
                            

                            


                </div>

                
                <div class="clearfix" > <br />  </div>
                <div class="col-md-6" >
                                                    </div>
                    
                    
                                                    <div class="col-md-6" >
                                                            
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
<script>
var user_id = parseInt('<?php print $valid_user;?>');
window.location = "/change-password?user_id="+user_id;
</script>

</body></html>
