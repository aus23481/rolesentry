<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class warmLeadCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'skylar:warm';

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
        //
/*
	find all users who are not HOT, have never responded, who are not assigned who are not on trial, who are not paid {
		
		$never_opened = if user has never opened an email.
		$never_logged_on = if user has never logged into the site.

		if ($never_opened && $never_logged_on){

			if (users account is more than 3 days){
				//this person has not interacted at all for 3 days.
				Skylar::sendUserEmail(INACTIVITY_EMAIL);
				return true;
			}
		}

		if ($never_opened) {
			
			if (user has logged in once in past week){
				Skylar::sendUserEmail(EMAIL_SETTINGS_PRIMER);
				//user has never opened an email, but has logged in	
			}
			if (user has logged in > once in past week){
				Skylar::sendUserEmail(EMAIL_SETTINGS_PRIMER);
			}
			if (user has logged in past week, 3 out of 5 days in week){
				if (user has high usage on the site){
					Skylar::sendUserEmail(TRIAL PAYMENT);
				}
				else {
					Skylar::sendUserEmail(REQUEST FEEDBACK);
				}
			}
			return false;
		}

		if ($never_logged_on){
			if (user has opened < 3){
				Skylar::sendUserEmail(PLATFORM_PRIMER);
			} 

			else if (user has opened < 5){
				Skylar::sendUserEmail(REQUEST FEEDBACK);
			}

			else if (user has opened < 10) {
				Skylar::sendUserEmail(TRIAL PAYMENT);
				Skylar::markUserForLimiting(userid);
			}
			return false;
		}

		//HERE - you've opened AND you've logged on

		if ($user has opened and logged on in past week){
			user has opened a lot and logged on a lot
			3/5 days 3/5 days

			5/5 days 5/5 days {
				Skylar::sendUserEmail(TRIAL PAYMENT);
				Skylar::markUserForLimiting(userid);
			}

		}

	}
	*/
    }
}
