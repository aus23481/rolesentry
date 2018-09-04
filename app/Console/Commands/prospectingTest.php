<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CMSController;
use App\Prospect;
use App\Candidate;
use App\User;
use App\ProspectingAction;
use App\Hiring_manager;

class prospectingTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'prospect:test {prospect_id} {email_subject} {email_message} {prospecting_action_type_id} {logged_in_user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	
	$cms = new CMSController; //to send prospecting email
	$prospect = Prospect::find($this->argument('prospect_id'));
	$user = User::find($this->argument('logged_in_user'));
	$prospecting_action_type_id = $this->argument('prospecting_action_type_id');

	$email_subject = $this->argument('email_subject');
	$email_message = $this->argument('email_message');

	if ($prospect) {


		if ($prospect->type_id == 1) { //this is a hiring manager
			$hiring_manager = Hiring_manager::where('prospect_id',$prospect->id)->first();
			$prospect_email = $hiring_manager->email;
		}

		if ($prospect->type_id == 2) { //this is a hiring manager
			$candidate = Candidate::where('prospect_id',$prospect->id)->first();
			$prospect_email = $candidate->email;
		}

		if ($prospect_email) {
			
			$prospecing_action = new ProspectingAction();

			//User is recruiter sending automatic email to hiring manager or candidate.  User is our subscriber
			$cms->sendProspectingEmail($email_subject, $email_message, $prospect_email, $user);
			$prospectingAction = new ProspectingAction();
			$prospectingAction->prospecting_action_type_id = $prospecting_action_type_id;
			$prospectingAction->prospect_id = $prospect->id;
			$prospectingAction->subject = $email_subject;
			$prospectingAction->message = $email_message;
			$prospectingAction->save();
		}	
	}
        //
    }
}
