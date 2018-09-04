@include("layouts.partials._header")
<body>
<!-- Preloader -->
<div class="preloader">
    <div class="cssload-speeding-wheel"></div>
</div>
<div id="wrapper">
  <!-- Navigation -->
  <nav class="navbar navbar-default navbar-static-top" style="margin-bottom: 0">
    <div class="navbar-header"> <a class="navbar-toggle hidden-sm hidden-md hidden-lg " href="javascript:void(0)" data-toggle="collapse" data-target=".navbar-collapse"><i class="ti-menu"></i></a>
      <div class="top-left-part">
     @if(Auth::user()) 
		<ul class="nav navbar-top-links navbar-right pull-left full-width">
			
			<li class="dropdown full-width"> <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <div class="TopUserFullwidth">
       <?php if (file_exists("uploads/profile/".Auth::id().".jpg")) { ?>
       <img src="uploads/profile/{{Auth::id()}}.jpg" alt="user-img" class="img-circle">
       <?php } else { ?>
       <img src="http://www.wilwia.com/images/default-user.png" alt="user-img" class="img-circle">
       <?php } ?>
       <span id="TopUserName" class="hidden-xs TopUserName">{{Auth::user()->name}}</span> </div> <div class="pull-right pt-15 hidden-xs"><div class="user-menu-bullet"></div><div class="user-menu-bullet"></div><div class="user-menu-bullet"></div></div></a>
			  <ul class="dropdown-menu dropdown-user">
				<li><a href="/home"><i class="ti-settings"></i> Home</a></li>
        <li role="separator" class="divider"></li>        
        <li><a href="/profile"><i class="ti-user"></i> My Profile</a></li>				
				<li role="separator" class="divider"></li>
				<li><a href="/logout"><i class="fa fa-power-off"></i> Logout</a></li>
			  </ul>
			  <!-- /.dropdown-user -->
			</li>
			<!-- /.dropdown -->
		  </ul>
      @endif
	  </div>
      <ul class="nav navbar-top-links navbar-left">
        <li><a onclick="TopUserName()" href="javascript:void(0)" class="open-close hidden-xs waves-effect waves-light"><i class="icon-arrow-left-circle ti-menu"></i></a></li>
		<li><h3 class="page-title">Recruiter Intel <small>Data Scraping Platform</small></h3></li>
      </ul>  
    </div>
    <!-- /.navbar-header -->
    <!-- /.navbar-top-links -->
    <!-- /.navbar-static-side -->
  </nav>
  <div class="navbar-default sidebar nicescroll" role="navigation">
    @include("layouts.partials._left-sidebar")
    <!-- /.sidebar-collapse -->
  </div>
  <!-- Page Content -->
  <div id="page-wrapper">
    <div class="container-fluid">
      <!-- row -->
      <!--row -->
        <div class="row">
              @yield('content')
             
        <!--Right sidebar start-->
        @if(Auth::user())  
        @endif
        <!--Right sidebar end-->
        
      </div>
      <!--row -->        
      <!--/ row -->
    </div>
    <!-- /.container-fluid -->
  </div>
  <!-- /#page-wrapper -->
  @include("layouts.partials._footer")
