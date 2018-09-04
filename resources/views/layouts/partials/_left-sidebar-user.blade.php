<?php

	$user = Auth::user();
	//print $user->alert_frequency."-kd";
	//1 admin
	//2 project manager
	//3 salesperson
	//4 developer

?>


           <!-- left sidebar --> 
		   <div class="hidden-sm hidden-xs col-lg-2 col-md-3 sidebar">
				
<!--
				<span id="back-btn2" class="sidebar-btn side-bkbtn"><img src="images/arrow-left.png"></span> 				 
<div class="tour-btn"> <br /><a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Launch guided Tour</a></div>
-->
<span id="back-btn2" class="sidebar-btn side-bkbtn"><img src="http://stage.rolesentry.com/images/arrow-left.png"></span> 

<!-- <div class="tour-btn"> <br /><a class="btn btn-large btn-success" href="javascript:void(0);" onclick="javascript:introJs().start();">Launch guided Tour</a></div>	-->

<p class="notif-title">Select Alert Frequency</p>
<div class="btn-group notif-btns" data-toggle="buttons">
	<label onclick="alertFrequency(1)" id="instant"  class="btn btn-default form-check-label notif-btn1 @if($user->alert_frequency === 1) active @endif">
		<input  class="form-check-input" type="radio"  autocomplete="off">Fast
	</label>
	<label onclick="alertFrequency(2)" id="daily" class="btn btn-default form-check-label notif-btn2 @if($user->alert_frequency === 2) active @endif">
		<input  class="form-check-input " type="radio" autocomplete="off">Daily
	</label>
	<label onclick="alertFrequency(0)" id="never" class="btn btn-default form-check-label notif-btn3 @if($user->alert_frequency === 0) active @endif ">
		<input  class="form-check-input" type="radio"  autocomplete="off">Never
	</label>
</div>




				<div id="favorites">
															
								<div id="searchbox2" class="searchboxlist" data-step="4" data-intro="Ok, This is 4th Step" data-position='right' data-scrollTo='tooltip'>
									<h4>Hiring Managers <a href="#togglelist2" data-toggle="collapse" id="togglebtn2"><span class="toggletext2"></span></a></h4>
									<div id="custom-search-input">
										<div class="input-group col-md-12">
											<input type="text" class="form-control" id="searchbox_hiring_manager" name="search" placeholder="Search" />
											<span class="input-group-btn">
												<button class="btn btn-info" type="button">
													<i class="glyphicon glyphicon-search"></i>
												</button>
											</span>
										</div>
									</div>
									<div id="togglelist2" class="collapse in">
										<div id="favelist1" class="favelist">
											<ul id="ul_favorite_hiring_managers">
											</ul>
										</div>
									</div>
								</div>
									
								<div id="searchbox1" class="searchboxlist" data-step="5" data-intro="Ok, This is 5th Step" data-position='right' data-scrollTo='tooltip'>
									<h4>Companies <a href="#togglelist1" data-toggle="collapse" id="togglebtn1"><span class="toggletext1"></span></a></h4>
									<div id="custom-search-input">
										<div class="input-group col-md-12">
											<input type="text" class="form-control" id="searchbox_company" name="search" placeholder="Search" />
											<span class="input-group-btn">
												<button class="btn btn-info" type="button">
													<i class="glyphicon glyphicon-search"></i>
												</button>
											</span>
										</div>
									</div>
									<div id="togglelist1" class="collapse in">
										<div id="favelist1" class="favelist">
											<ul id="ul_favorite_companies">
											</ul>
										</div>
									</div>
								</div>

								
								
								<!-- candidate -->
								<div id="searchbox3" class="searchboxlist">
										<h4>Candidates <a href="#togglelist3" data-toggle="collapse" id="togglebtn3"><span class="toggletext3"></span></a></h4>
										<div id="custom-search-input" class="hide1">
											<div class="input-group col-md-12">
												<input type="text" class="form-control" id="favorite_searchbox_saved_search" name="search" placeholder="Search" />
												<span class="input-group-btn">
													<button class="btn btn-info" type="button">
														<i class="glyphicon glyphicon-search"></i>
													</button>
												</span>
											</div>
										</div>
										<div id="togglelist3" class="collapse in">
											<div id="favelist1" class="favelist">	
											<ul id="ul_favorite_candidates" class="candidate1-list">
											</ul>
										</div>
										</div>
									</div>

									<!-- candidate -->
<!---
								<div id="searchbox3" class="searchboxlist">
									<h4>Searches <a href="#togglelist3" data-toggle="collapse" id="togglebtn3"><span class="toggletext3"></span></a></h4>
									<div id="custom-search-input" class="hide">
										<div class="input-group col-md-12">
											<input type="text" class="form-control" id="favorite_searchbox_saved_search" name="search" placeholder="Search" />
											<span class="input-group-btn">
												<button onclick="getUserFavoritesSearch(3);" class="btn btn-info" type="button">
													<i class="glyphicon glyphicon-search"></i>
												</button>
											</span>
										</div>
									</div>
									<div id="togglelist3" class="collapse in">
										<div id="favelist1" class="favelist">

											<ul id="ul_saved_searches">
											</ul>
										</div>
									</div>
								</div>
--!>


								
								
								

								<div id="searchbox4" class="searchboxlist hide">
									<h4>Openings  <a href="#togglelist4" data-toggle="collapse" id="togglebtn4"><span class="toggletext4"></span></a></h4>
									<div id="custom-search-input">
										<div class="input-group col-md-12">
											<input type="text" class="form-control" id="searchbox_opening" name="search" placeholder="Search" />
											<span class="input-group-btn">
												<button onclick="getUserFavoritesSearch(4)" class="btn btn-info" type="button">
													<i class="glyphicon glyphicon-search"></i>
												</button>
											</span>
										</div>
									</div>
									<div id="togglelist4" class="collapse in">
										<div id="favelist1" class="favelist">
											<ul id="ul_favorite_openings">
												<li><span class="glyphicon glyphicon-star"></span>Compliance Specialist<span id="remove-item"><img src="images/remove.png"/></span></li>
												<li><span class="glyphicon glyphicon-star"></span>Marketing Director<span id="remove-item"><img src="images/remove.png"/></span></li>
												<li><span class="glyphicon glyphicon-star"></span>Full Stack Engineer<span id="remove-item"><img src="images/remove.png"/></span></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- left sidebar -->
