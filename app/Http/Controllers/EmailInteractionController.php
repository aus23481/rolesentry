<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DOMDocument;
use App\Rolesentry_company;
use DB;
use App\User;
use App\HiringManager;
use App\Email;
use App\UserEmailInteractions;

class EmailInteractionController extends Controller
{
    public function __construct()
    {
    }

    public function sendgridPost(Request $request){

	$events = $request->all();

	$my_file = '/code/file.txt';
	$handle = fopen($my_file, 'a') or die('Cannot open file:  '.$my_file);
	fwrite($handle, json_encode($events));
	
	foreach($events as $event){
  	 if (isset($event['email']) && isset($event['email_id'])){	

		$user = User::where('email', '=', $event['email'])->first();
		$hiring_manager = HiringManager::where('email', $event['email_id'])->first();
		$email = Email::where('id', '=', $event['email_id'])->first();

		$email_interaction_id = 0;

		if ($event['event'] == 'open') {
			$email_interaction_id = 1;
		}
		else if ($event['event'] == 'click') {
			$email_interaction_id = 2;
		}
	
		if ($hiring_manager) {
			UserEmailInteractions::create(['email_id'=>$email->id, 'user_id'=>$hiring_manager->id, 'email_interaction_id'=>$email_interaction_id]);
		}
		else if ($user) {
			
			UserEmailInteractions::create(['email_id'=>$email->id, 'user_id'=>$user->id, 'email_interaction_id'=>$email_interaction_id]);
		}


	  }
	}

		
    } 

	}
