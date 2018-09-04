@extends('layouts.app')
@section('content')


<div id="account-form-section">
		<div class="row">
			<div class="container">
			<div class="col-lg-6 col-md-6 col-sm-7 col-xs-12 account-box-container">
				<div id="account-box">
					<h2>Welcome! <ul class="nav navbar-right filter-dropdown">
								<li class="dropdown">
									<a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
									<span class="glyphicon glyphicon-option-vertical"></span></a>
									<ul class="dropdown-menu">
										<li><span id="reset-form">Clear all</span></li>
									</ul>
								</li>
							</ul></h2>
					<div id="form-container">
						
                    <form id="acct-form" class="form-horizontal" method="POST" action="{{ route('login') }}">
                                        {{ csrf_field() }}
							<div class="form-group row">
								
								<div class="col-lg-12 col-md-12">
								  <label for="usr">Email:</label>
								  <input type="email" id="email" name="email" class="form-control" placeholder="johntanner@gmail.com">
								</div>
								<div class="col-lg-12 col-md-12">
								  <label for="usr">Password:</label>
								  <input type="password" name="password" id="password" class="form-control">
								</div>
								
							</div>
							
								
							
							<label class="check-container">
									
									<input type="checkbox" id="up_1_1" name="up_1_1" checked="">
									<span class="checkmark"></span> Remember me. <span><h5 style="text-align:left"><a data-toggle="modal" data-target="#forgotpwModal" href="#forgotpwModal">Forgot Password</a></h5></span>
							</label>
							<div class="form-group text-right">
								<button type="submit" class="btn btn-primary">Login</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>


	 <!-- Forgot pw Modal -->
	  
	 <form  action="{{ URL::to('mail-forgot-password')}}" class="form-inline"   id="forgotpw-form"  method="post">                        
		{{ csrf_field() }}    
	<div class="modal fade" id="forgotpwModal" role="dialog">
		<div class="modal-dialog modal-sm">
		<div class="modal-content">
				<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Forgot Password?</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
					
				<h5 style="    margin-top: auto;color: #666;font-weight: 400;">No problem! <br><br>Please enter the email associated with your account and we will send you an email with instructions to reset your password.<br><br></h5>
					<input type="email" class="form-control" name="email" placeholder="Email" id="email">
					</div>
					<button type="submit" class="btn btn-default">Reset Password</button>
				
				</div>
		
		</div>
		</div>
	</div>
  </form>	
<!-- Forgot pw Modal -->

@endsection
