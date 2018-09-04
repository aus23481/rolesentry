<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Input;
use Session;
use App\Invoice;
use App\Invoice_type;
use App\User;

use Stripe\Stripe;
use Stripe\Charge;
use Auth;


class InvoiceController extends Controller
{
    public $common;
	
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
		//$this->common = new CommonController;	
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		
		
		 $data["user"] = User::find($_REQUEST["user_id"]);
		 $data["invoice_type"] = $_REQUEST["invoice_type"];
			

	     $data['stripe_publishable_secret'] = env('STRIPE_PUBLISHABLE_SECRET');
            //print_r($orders);
          // return response()->json($orders);
        
        return view('invoice', $data);
    }


    public function stripemonthlypost(Request $request){
        
                
                $customer = User::find($request->input('user_id'));
                // Get the credit card details submitted by the form
                $token = $request->input('stripeToken');
            
                // Create the charge on Stripe's servers - this will charge the user's card
                try {
                    Stripe::setApiKey(env('STRIPE_SECRET'));
        
                    $source = \Stripe\Source::create([
            
                        "type"=>"card",
                        "currency"=>"usd",
                        "token"=>$token			
        
                    ]);
        
                    $stripe_customer = \Stripe\Customer::create([
                        'email' => $customer->email,
                        'source' => $source
                    ]);
        

                    $customer->stripe_id = $stripe_customer->id;
                    $customer->is_paid = 1;
                    $customer->save();
                    
                    if($request->input("invoice_type") == 2) $plan = "plan_CkrHwun7xGBUHS"; //"Hiring Manager Report Access";
                    else $plan = "plan_CkrHwun7xGBUHS"; //Platform Access

                    $subscription = \Stripe\Subscription::create([
                        'customer' => $stripe_customer->id,
                        'items' => [['plan' => $plan]],
                    ]);
                        
                    $invoice = new Invoice;
                    $invoice->user_id = $customer->id;
                    $invoice->invoice_type_id = $request->input("invoice_type");
                    $invoice->is_paid = 1;
                    $invoice->created_at = date("Y-m-d H:i:s",time());
                    $invoice->updated_at = date("Y-m-d H:i:s",time());
    
                    $invoice->save();
    
                    return redirect("/invoice?user_id=".$_REQUEST["user_id"]."&invoice_type=".$_REQUEST["invoice_type"])->with("status", "Successfull Paid");

                }
        
                catch(Stripe_CardError $e) {
                                $e_json = $e->getJsonBody();
                                $error = $e_json['error'];
                                // The card has been declined
                                // redirect back to checkout page
                    return back()->withInput();
                        }
    }

    public function stripepost(Request $request){

        //print "Successfully Paid ";


        Stripe::setApiKey(env('STRIPE_SECRET'));
		// Get the credit card details submitted by the form
		$token = $request->input('stripeToken');
		//print $token."-";
		//die();
	
		// Create the charge on Stripe's servers - this will charge the user's card
		try {
			$charge = Charge::create(array(
			  "amount" => $request->input('amount'), 
			  "currency" => "usd",
			  "card"  => $token,
			  "description" => 'Recruiter Subscription')
			);
			$paymentid     = $charge->id;
			$payerid       = $charge->source->id;
			$status        = $charge->status;
			// if status="succeeded" do rest of the insert operation start
            // end
            //print "Success:::$paymentid::$payerid::$status";
            if($status == "succeeded"){
                
                $invoice = new Invoice;
                $invoice->user_id = Auth::id();
				$invoice->invoice_type_id = 1;
				$invoice->is_paid = 1;
				$invoice->created_at = date("Y-m-d H:i:s",time());
				$invoice->updated_at = date("Y-m-d H:i:s",time());

                $invoice->save();

                return redirect("/invoice?user_id=".$_REQUEST["user_id"]."&invoice_type=".$_REQUEST["invoice_type"])->with("status", "Successfull Paid");
                
            }

		} catch(Stripe_CardError $e) {
			$e_json = $e->getJsonBody();
			$error = $e_json['error'];
		  	// The card has been declined
		  	// redirect back to checkout page
            return back()->withInput();	
		}
    }
}
