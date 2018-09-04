<?php

	$user = Auth::user();
	
	//1 admin
	//2 project manager
	//3 salesperson
	//4 developer

?>

<div class="sidebar-nav navbar-collapse ">
      <ul class="nav" id="side-menu">
        <h3 class="dashboard-txt">Dashboard</h3>
   @if(Auth::user())
   
   <li> <a href="/home" class="waves-effect"><i class="fa fa-home"></i> Home</a> </li>
   <li> <a href="/new-alerts" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i> New Alerts</a> </li>
   <li> <a href="/add-company" class="waves-effect"><i class="fa fa-star"></i> Add Company</a> </li>
   <li> <a href="/companies" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i> Companies</a> </li>
   <li> <a href="/cms" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i>Content Management</a> </li>
      
   <li><a href="#">Robot CMS</a> </li>

   <li>  <a href="#">Add Robot Company</a>
		<ul class="nav nav-second-level">
			<li> <a href="/add-robot-company-by-name" class="waves-effect"><i class="fa fa-bar-chart-o"></i>By Name</a> </li>
			<li> <a href="/add-robot-company-by-website" class="waves-effect"><i class="fa fa-bar-chart-o"></i>By Website</a> </li>
			<li> <a href="/add-robot-company-by-career-page" class="waves-effect"><i class="fa fa-bar-chart-o"></i>By Career Page</a> </li>
		</ul>
   </li>

   <li>  <a href="#">Robot Companies</a>
	<ul class="nav nav-second-level">
	<li> <a href="/robot-companies?type=1" class="waves-effect"><i class="fa fa-bar-chart-o"></i> &nbsp;&nbsp; ({{session('without_website_count')}})&nbsp;&nbsp; Without Website</a> </li>
		<li> <a href="/robot-companies?type=2" class="waves-effect"><i class="fa fa-bar-chart-o"></i> &nbsp;&nbsp; ({{session('without_careerpage_count')}})&nbsp;&nbsp; Without Career Page</a> </li>
		

		<li> <a href="/robot-companies?type=3" class="waves-effect"><i class="fa fa-bar-chart-o"></i>  Without key selector </a> </li>
		<li> <a href="/robot-companies?type=4" class="waves-effect"><i class="fa fa-bar-chart-o"></i> without approval </a> </li>
	</ul>
</li>
<li><a href="/robot-company-progression-status?id=0" class="waves-effect"><i class="fa fa-shopping-basket"></i>Progression Status</a></li>
<li><a href="/robot-company-approval" class="waves-effect"><i class="fa fa-shopping-basket"></i>Rapid Approve</a></li>   
<!--<li><a href="/add-location" class="waves-effect"><i class="fa fa-shopping-basket"></i>Add Location</a></li> --> 
<li><a href="/locations" class="waves-effect"><i class="fa fa-shopping-basket"></i>Locations</a></li>


<li><a href="/jobtype-definer" class="waves-effect"><i class="fa fa-shopping-basket"></i>Job Type Definer</a></li>   
<li><a href="/auto-marketing" class="waves-effect"><i class="fa fa-shopping-basket"></i>Automated Marketing</a></li> 
<li><a href="/bantext" class="waves-effect"><i class="fa fa-shopping-basket"></i>Ban Text</a></li> 

<li><a href="/openings" class="waves-effect"><i class="fa fa-shopping-basket"></i>Openings</a></li>     

   
	@if($user->user_roles_id === 1)	
		
		<li> <a href="/marketing" class="waves-effect"><i class="fa fa-shopping-basket"></i> Marketing</a> </li>
		<li> <a href="/leads" class="waves-effect"><i class="fa fa-cog"></i> Leads</a> </li>
		<li> <a href="/ta-leads" class="waves-effect"><i class="fa fa-bed"></i>TA Leads</a> </li>
		<li> <a href="/site-maker" class="waves-effect"><i class="fa fa-building-o"></i> Yelp Site Maker</a> </li>
		<li> <a href="/sales" class="waves-effect"><i class="fa fa-bar-chart-o"></i> Reports</a> </li>
		<li> <a href="/all-mockups" class="waves-effect"><i class="fa fa-th"></i> All Mockups</a> </li>
		<li> <a href="/my-mockups" class="waves-effect"><i class="fa fa-tasks"></i> My Mockups</a> </li>		
		<li> <a href="/my-customers" class="waves-effect"><i class="fa fa-user"></i> My Customers</a> </li>
		<li> <a href="/my-notes" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i> My Notes</a> </li>
		<li> <a href="/all-customers" class="waves-effect"><i class="fa fa-user"></i> All Customers</a> </li>
		<li> <a href="/all-notes" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i> All Notes</a> </li>		
		<li> <a href="#" class="waves-effect"><i class="fa fa-cog"></i> Options</a> </li>		
		<li> <a href="#" class="waves-effect"><i class="fa fa-question-circle"></i> Help</a> </li>
	
	@elseif($user->user_roles_id === 2)
		
		<li> <a href="/home" class="waves-effect"><i class="fa fa-home"></i> Home</a> </li>
		<li> <a href="/opportunities" class="waves-effect"><i class="fa fa-star"></i> Opportunities</a> </li>
		<li> <a href="/marketing" class="waves-effect"><i class="fa fa-shopping-basket"></i> Marketing</a> </li>
		<li> <a href="/site-maker" class="waves-effect"><i class="fa fa-building-o"></i> Yelp Site Maker</a> </li>
		<li> <a href="/sales" class="waves-effect"><i class="fa fa-bar-chart-o"></i> Reports</a> </li>
		<li> <a href="/all-mockups" class="waves-effect"><i class="fa fa-th"></i> All Mockups</a> </li>
		<li> <a href="/my-mockups" class="waves-effect"><i class="fa fa-tasks"></i> My Mockups</a> </li>		
		<li> <a href="/my-customers" class="waves-effect"><i class="fa fa-user"></i> My Customers</a> </li>
		<li> <a href="/my-notes" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i> My Notes</a> </li>
		<li> <a href="/all-customers" class="waves-effect"><i class="fa fa-user"></i> All Customers</a> </li>
		<li> <a href="/all-notes" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i> All Notes</a> </li>		
		<li> <a href="#" class="waves-effect"><i class="fa fa-cog"></i> Options</a> </li>		
		<li> <a href="#" class="waves-effect"><i class="fa fa-question-circle"></i> Help</a> </li>
		
	@elseif($user->user_roles_id === 3)
		
		<li> <a href="/home" class="waves-effect"><i class="fa fa-home"></i> Home</a> </li>
		<li> <a href="/opportunities" class="waves-effect"><i class="fa fa-star"></i> Opportunities</a> </li>
		<li> <a href="/marketing" class="waves-effect"><i class="fa fa-shopping-basket"></i> Marketing</a> </li>
		<li> <a href="/leads" class="waves-effect"><i class="fa fa-cog"></i> Leads</a> </li>
		<li> <a href="/site-maker" class="waves-effect"><i class="fa fa-building-o"></i> Yelp Site Maker</a> </li>
		<li> <a href="/sales" class="waves-effect"><i class="fa fa-bar-chart-o"></i> Reports</a> </li>

		<li> <a href="/my-mockups" class="waves-effect"><i class="fa fa-tasks"></i> My Mockups</a> </li>		
		<li> <a href="/my-customers" class="waves-effect"><i class="fa fa-user"></i> My Customers</a> </li>
		<li> <a href="/my-notes" class="waves-effect"><i class="fa fa fa-sticky-note-o"></i> My Notes</a> </li>

	
		<li> <a href="#" class="waves-effect"><i class="fa fa-cog"></i> Options</a> </li>		
		<li> <a href="#" class="waves-effect"><i class="fa fa-question-circle"></i> Help</a> </li>

	@elseif($user->user_roles_id === 4)
		
		<li> <a href="/my-mockups" class="waves-effect"><i class="fa fa-tasks"></i> My Mockups</a> </li>		
		<li> <a href="#" class="waves-effect"><i class="fa fa-cog"></i> Options</a> </li>		
		<li> <a href="#" class="waves-effect"><i class="fa fa-question-circle"></i> Help</a> </li>

	
	@endif
	
   @endif
	   
    @if(Auth::guest())
		   <li> <a href="/site-maker" class="waves-effect"><i class="fa fa-building-o"></i> Yelp Site Maker</a> </li>
		   <li> <a href="#" class="waves-effect"><i class="fa fa-question-circle"></i> Help</a> </li>
    @endif
	
	
	
	
	</ul>
</div>
