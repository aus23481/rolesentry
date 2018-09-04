<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Inbound_email;
use App\Hiring_manager;
use App\Candidate;
use App\ProspectingAction;

class InboundMailParseController extends Controller
{
   public function parseNewInboundMail(Request $request){

	$newEmail = new Inbound_email();
	$newEmail->to = $request->to;	
	$newEmail->from = $request->from;	
	$newEmail->subject = $request->subject;
	$newEmail->message = $request->html;
	$newEmail->save();

	$matches = array();
	$t = preg_match('/<(.*?)\>/s', $request->from, $matches);
	$from_email = $matches[1];

	if ($from_email) {
		$newEmail->from = $from_email;
	}

	$newEmail->save();

	//set prospect_id so we know who this came from in our system
	//we need to know first if this is a hiring manager or a candidate

	$hiring_manager = Hiring_manager::where('email', $newEmail->from)->first();	
	$candidate = Candidate::where('email', $newEmail->from)->first();	

	if ($hiring_manager){
		$newEmail->prospect_id = $hiring_manager->prospect_id;
		$newEmail->save();
	}

	else if ($candidate){
		$newEmail->prospect_id = $candidate->prospect_id;
		$newEmail->save();
	}
	else {
		
		//cannot find prospect in our system.
	}


	$prospecting_action = new ProspectingAction();
	$prospecting_action->prospecting_action_type_id = 3;
	$prospecting_action->prospect_id = ($newEmail->prospect_id ? $newEmail->prospect_id : 0);
	$prospecting_action->subject = $newEmail->subject;
	$prospecting_action->message = $newEmail->message;
	$prospecting_action->save();

	return "OK Thanks sendgrid";
   }
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->common = new CommonController;
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
    }
}
