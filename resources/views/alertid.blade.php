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
                <div class="col-md-5" style="background:#FFF;min-height:400px;">

                 
                    <div class="" style="padding:20px 10px 10px 30px;">
                        
                       @if(isset($alert)) 
                       <div class="row" style="">
                        
                         <div><img  width="50" height="50" src="{{$alert->job->company->logo_url}}"> </div><div style="float:right;margin-right:150px;margin-top:-60px;"><h2 style="">{{$alert->job->company->name}}</h2> </div>
                        </div>
                        <div class="row">
                          <div>Pic of Hiring manager</div>  
                          <div> <h4>Hiring Manager:  {{$alert->job->hiring_manager}}</h4></div> 
                        <div><a href="{{$alert->job->hiring_manager_linkedin}}"></a>Linkedin</div>  
                        
                        </div>
                        <div class="row"><h3>Job Opened </h3><br> 
                              <h2>{{date('l M j, ga', strtotime('$alert->created_at'))}}</h2></div>
                        

                        
                        @endif
                    
                    
                    </div>
                            
                </div>

                <div class="col-md-7" style="background:#FFF;min-height:400px;">
                    
                                     
                                        <div class="">
                                            
                                           @if(isset($alert)) 
                                           
                                           <h2>{{$alert->job->title}}</h2>
                                           <h4>
                                                {{$alert->job->notes}}

                                           </h4>
                                           <a href="{{$alert->job->job_description_link}}">Details</a>
                                           @endif
                                        
                                        
                                        </div>
                                                
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

</body></html>
