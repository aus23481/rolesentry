

@include("layouts.partials._header-user")
  @include("layouts.partials._header-user-nav")



   <div class="row platform-full-wrap">  

       <!-- Banner -->

       <div id="banner-section">
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

   <!-- Banner -->

  
      <!-- leftsidebar -->
	
        @include("layouts.partials._left-sidebar-user")

     <!-- leftsidebar -->
     
     <!-- content -->
     	<!-- content -->
         <div class="main cstm-platform-main-wrap">
			<div class="row">
            @yield('content')
        </div>  
     </div> <!-- content -->
            
	
    @include("layouts.partials._footer-user")
   
