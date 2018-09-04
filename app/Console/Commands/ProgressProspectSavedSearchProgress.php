<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CMSController;
use App\SavedSearch;
use App\SchemeStep;
use App\ProspectSavedSearchProgress;
use App\Lock;
use App\User;
use App\Prospect;
use App\LockDetail;
use App\Approval;


class ProgressProspectSavedSearchProgress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'progress:pssps {--time=}';

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

    public function attemptUnlock($lock, $time_now) {

	$cms = new CMSController; //to send prospecting email

	$time_now = (int) $time_now;

	$do_unlock = true;

	$lockDetails = LockDetail::where('lock_id', $lock->id)->get();

	echo 'looping through lock details';

	foreach($lockDetails as $lockDetail) {
		
		if ($lock->lock_type_id == 1) { //how many days to wait

			$seconds_since_lock_was_created = ($time_now - strtotime($lock->created_at));
			$days_since_lock_was_created = ($seconds_since_lock_was_created / 86400);

			echo "its been $days_since_lock_was_created days since this lock created";

			if (!($days_since_lock_was_created > $lockDetail->value)) {
				$do_unlock = false;
			}
		}

		if ($lock->lock_type_id == 2) {
//			if (!$has approval){i

				$do_unlock = false;

				$ProspectSavedSearchProgress = ProspectSavedSearchProgress::find(Lock::find($lock->id)->prospect_saved_search_progress_id);

				$approval_for_this_lock = Approval::where('prospect_saved_search_progress_id',$ProspectSavedSearchProgress->id)
					->where('scheme_step_id', $ProspectSavedSearchProgress->current_scheme_step_id)
					->first();

				if (!$approval_for_this_lock) {

					$email_body_and_subject = $cms->populateAutomatedEmail($ProspectSavedSearchProgress->id, $ProspectSavedSearchProgress->current_scheme_step_id);	

					$approval = Approval::create([
						"prospect_saved_search_progress_id" => $ProspectSavedSearchProgress->id,
	 					"scheme_step_id" => $ProspectSavedSearchProgress->current_scheme_step_id,
						"email_subject" => $email_body_and_subject['email_subject'],
						"email_message" => $email_body_and_subject['email_body']
					]);
				}

				else {
				     if ($approval_for_this_lock->is_approved == 1) {
					$do_unlock = true;
				     }
				}
				
//			}
		}
	}

	if ($do_unlock) {
		echo "UNLOCKING $lock->id";
		$lock->is_unlocked = 1;
		$lock->save();
		return true;
	}else{
		echo "NOT UNLOCKING $lock->id";
		return false;
	}

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
    
	$cms = new CMSController; //to send prospecting email
	    //
	$pssps = ProspectSavedSearchProgress::where('is_finished', 0)->get();

	$time_now = $this->option('time') ? $this->option('time') : time();
	$stop_progress = false;
	
	foreach($pssps as $pssp){
		$currentSchemeStep = SchemeStep::where('id',$pssp->current_scheme_step_id)->first();

		$after_locks = [];
		$before_locks = [];

			$after_locks = Lock::where('scheme_step_id', $currentSchemeStep->id)
						->where('prospect_saved_search_progress_id', $pssp->id)
						->where('is_email_sent',1)
						->where('is_unlocked',0)
						->get();

			$before_locks = Lock::where('scheme_step_id', $currentSchemeStep->id)
						->where('prospect_saved_search_progress_id', $pssp->id)
						->where('is_email_sent',0)
						->where('is_unlocked',0)
						->get();


		$have_all_before_locks_been_unlocked = true;

		foreach($before_locks as $before_lock) {
			if (!$before_lock->is_unlocked) {
				echo 'tryingot unlock';
				$unlock_successful = $this->attemptUnlock($before_lock, $time_now);	
				if (!$unlock_successful){
					$have_all_before_locks_been_unlocked = false;
				}
			}
			else{
				//already unlocked
			}
		}

		if ($have_all_before_locks_been_unlocked && !$pssp->is_email_for_current_step_sent) {

			$saved_search = SavedSearch::find($pssp->saved_search_id);
			$subscriber = User::where('id',$saved_search->user_id)->first();
			$prospect = Prospect::where('id', $pssp->prospect_id)->first();

			if ($subscriber->credits){
				if (($time_now - $prospect->last_bothered) > 604800 ) { //last bothered more than 1 week ago
					$subscriber->credits = $subscriber->credits - 1;
					$subscriber->save();

					$prospect->last_bothered = $time_now;
					$prospect->save();

					$email_body_and_subject = $cms->populateAutomatedEmail($pssp->id, $pssp->current_scheme_step_id);	

					$cms->sendAutomatedEmail($email_body_and_subject, $pssp->id);

					$saved_search->last_email_sent = $time_now;
					$saved_search->emails_sent = $saved_search->emails_sent + 1;
					$saved_search->save();

					$pssp->is_email_for_current_step_sent = 1;
					$pssp->save();
				



				}
				else{
					$stop_progress = true;
					echo 'prospect already bothered this week';
				}
			}
			else{
				$stop_progress = true;
				echo 'subscriber no credits';
			}

		}

		$have_all_after_locks_been_unlocked = true;

		foreach($after_locks as $after_lock){
			if (!$after_lock->is_unlocked){
				$unlock_successful = $this->attemptUnlock($after_lock, $time_now);
				if (!$unlock_successful){
					$have_all_after_locks_been_unlocked = false;
				}
			}
		}

		if ($have_all_after_locks_been_unlocked && $have_all_before_locks_been_unlocked && $pssp->is_email_for_current_step_sent = 1 && !$stop_progress){

			$next_scheme_step = SchemeStep::where('id', '>', $pssp->current_scheme_step_id)
					->where('saved_search_id',$pssp->saved_search_id)
					->orderBy('id','ASC')
					->first();

			if ($next_scheme_step){
				$pssp->current_scheme_step_id = $next_scheme_step->id;
				$pssp->is_email_for_current_step_sent = 0;
				$cms->make_locks_for_next_scheme_step($pssp);
			}
			else{
				$pssp->current_scheme_step_id = NULL;
				$pssp->is_finished = 1;
			}

			$pssp->save();
		}
		else{
			if ($pssp->max_age){
				if ($pssp->max_age < ($time_now - $pssp->created_at)){
					$pssp->is_finished = 1;
					$pssp->expired = 1;
					$pssp->save();
				}
			}
		}		
	}
    }
}
