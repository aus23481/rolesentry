
@include("layouts.partials._header-user")

 <!-- top header -->
 @include("layouts.partials._header-user-nav")
 <!-- top header -->   
 
 <!-- banner -->
	<!--<div id="banner-section" class="hide">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
					<img src="images/new_mail.png"/>
					<h2>Your daily notifications, recommendations and updates were sent to your email.</h2>
					<img src="images/close-icon.png" class="close-btn"/>
				</div>
			</div>
		</div>
    </div>
  -->
  <!-- banner -->	
	<div id="main-content-section" style="padding-top:50px">
		
		<div class="row">
           <!-- left sidebar --> 
	@if (!strpos(Request::url(), 'change-password') && !strpos(Request::url(), 'userpref'))
           @include("layouts.partials._left-sidebar-user")
            <!-- left sidebar -->	
	@else
	 <!--  @include("layouts.partials._left-sidebar-user-change-password") --!>
         @endif
            <!-- Main -->
            @yield('content')
             <!-- main -->
		</div> <!-- row --> 	
    </div> <!-- main content section -->
    
        
   
