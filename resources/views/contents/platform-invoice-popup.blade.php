													
											<div class="modal fade" data-keyboard="false" data-backdrop="static" id="platformInvoiceModal" role="dialog">
												<div class="modal-dialog modal-md">
												<div class="modal-content">
														
														<div class="modal-body">
															
															<!-- content invoice -->

															
														

@if (session('status'))
<div class="alert alert-success payment_status">
    {{session('status')}}
</div>
<script>
		
		window.location = "/platform";
</script>
@endif

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

 
  
           
	<div class="panel panel-default">
		<div class="panel-heading  text-white"><h4>Recruiter Intel Invoice</h4></div>



		<form accept-charset="UTF-8" action="{{ URL::to('stripemonthlypost')}}" class="require-validation"
		data-cc-on-file="false"
		data-stripe-publishable-key="{{$stripe_publishable_secret}}"
		id="payment-form" method="post">
		{{ csrf_field() }}
		
		
		<input type="hidden" name="amount" value="@if($invoice_type == 1) 99 @else 199 @endif" id="amount">
		<input type="hidden" name="user_id" value="{{$user->id}}" id="user_id">
		<input type="hidden" name="invoice_type" value="{{$invoice_type}}" id="invoice_type">

		<div class="panel-body">

				@if (session('status'))
				<div class="alert alert-success">
						{{ session('status') }}
				</div>
		@endif
			
			<div class="col-md-6">
				<img class="img-responsive invoice-modal-logo" src="images/logo-recruiter-intel-invoice.svg">
				

				<div class="client-info">
					
					<ul>
					
					<li><b>Name</b><p>{{ucfirst($user->name)}}</p></li>
					<li><b>Email</b><p style="color: #ec9b36;">{{$user->email}}</p></li>
					
					<li><b>Plan</b><p>@if($invoice_type == 1) Platform Access @elseif ($invoice_type == 2) Hiring Manager Report Access @else @endif</p></li>
					<li><b>Price</b><p>@if($invoice_type == 1) 99 @else 199 @endif</p></li>
					
					</ul>
					
					</div>	
					
					<img src="images/credit card icons.png" class="cc-icons" />
		

</div>
				
			
			
			<div class="col-md-6">
				<div class="form-order mt-2 mb-2">
					<div class="stripe_form_container mt-2">    
						                        
						<div class='form-row'>
							<div style="padding:0 !important"  class='col-xs-12 form-group required'>
								<label class='control-label'>Name on Card</label> 
								<input class='form-control cardholder-name' size='4' name="customer" id="customer" type='text'>
							</div>
						</div>
						<div class='form-row'>
							<div style="padding:0 !important" class='col-xs-12 form-group card required'>
								<label class='control-label'>Card Number</label> 
								<input autocomplete='off' class='form-control card-number' size='20' type='text'>
							</div>
						</div>
						<div class='form-row'>
							<div style="padding:0" class='col-xs-4 form-group cvc required'>
								<label class='control-label'>CVC</label> <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4'	type='text'>
							</div>
							<div style="padding-right:0" class="col-xs-8 form-group expiration required">
									<label class='control-label'>Expiration</label> 
									<div style="display:flex;">
									<input class='form-control card-expiry-month' placeholder='MM' size='2' type='text'>
									<input style="margin-left:14px" class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text'>
									</div>
							</div>
						</div>                      
						<div class='form-group'>
							<div class='col-md-12 error form-group hide'>
								<div class='alert-danger alert'>Please correct the errors and try again.</div>
							</div>
						</div>
														
					</div>               
				</div>
				<div class="form-group text-right">    
					<button  type="submit" style="margin-top:20px"  class="btn btn-primary send-btn">Confirm Payment</button>  
				</div>
			</div>
			
			   
		</div>
	</div>
		</form>

		
</div>




 <!-- main content -->	


<script>


$('#monthly_stripe_form').on('submit', function(e) {
    if (!$(this).data('cc-on-file')) {

        e.preventDefault();
        Stripe.setPublishableKey($(this).data('stripe-publishable-key'));
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, MonthlystripeResponseHandler);
    }
});


function MonthlystripeResponseHandler(status, response) {
    if (response.error) {
        $('.error')
            .removeClass('hide')
            .find('.alert')
            .text(response.error.message);
    } else {
        // token contains id, last4, and card type
        var token = response['id'];
        // insert the token into the form so it gets submitted to the server
        $('#stripe_form').find('input[type=text]').empty();
        $('#stripe_form').append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
        $('#stripe_form').get(0).submit();
    }
}




$('#payment-form').on('submit', function(e) {
    if (!$(this).data('cc-on-file')) {

        e.preventDefault();
        Stripe.setPublishableKey($(this).data('stripe-publishable-key'));
        Stripe.createToken({
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
        }, stripeResponseHandler);
    }
});


function stripeResponseHandler(status, response) {
    if (response.error) {
        $('.error')
            .removeClass('hide')
            .find('.alert')
            .text(response.error.message);
    } else {
        // token contains id, last4, and card type
        var token = response['id'];
        // insert the token into the form so it gets submitted to the server
        $('#payment-form').find('input[type=text]').empty();
        $('#payment-form').append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
        $('#payment-form').get(0).submit();
    }
}

function validateEmail(email) {
    var re = /\S+@\S+\.\S+/;
    return re.test(email);
}

//$(".navbar").hide();
</script>


															<!-- end content inv -->
																						
														</div>
																		
												</div>
												</div>
											</div>
										  </form>	
