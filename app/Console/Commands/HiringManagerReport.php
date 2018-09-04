<?php

namespace App\Console\Commands;

use App\EmailOpening;
use Illuminate\Console\Command;
use App\Location;
use App\Email;
use App\NextEmail;
use App\opening;
use App\Http\Controllers\CMSController;
class HiringManagerReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:hiring_manager_report {time}';

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
		$locations = Location::where('hiring_manager_report_time', '=', $this->argument('time'))->get();
		$cms = new CMSController;


		foreach($locations as $location) {
				$last_time_this_location_had_email = Location::getLastEmailTimesAllLocations($location->id);

				$openings = opening::select(['openings.id as opening_id', 'hiring_managers.linkedin_url'])
				->join("hiring_manager_openings", 'hiring_manager_openings.opening_id','=','openings.id')
				->join("hiring_managers", 'hiring_managers.id','=','hiring_manager_openings.hiring_manager_id')	
				->join('next_email','openings.id','=','next_email.opening_id')
					->where('openings.location_id', '=', $location->id)
					->get();


				echo $openings->count() . '--';

				$opening_ids_for_this_email = [];

				foreach($openings as $opening){
					echo 'opening';
					$already_emailed_openings = EmailOpening::where('opening_id','=',$opening->opening_id)->first();
					if ($already_emailed_openings) {
						echo 'already emailed this';	
						//already had this			
					}
					else{
						echo $opening->id;
						if ($opening->linkedin_url) {
							if (!in_array($opening->opening_id, $opening_ids_for_this_email)){
								$opening_ids_for_this_email[] = $opening->opening_id;
							}
						}
					}
				}

				echo sizeOf($opening_ids_for_this_email) .'==';

				if (sizeOf($opening_ids_for_this_email) > 9){


					$email_for_this_location = new Email();
					$email_for_this_location->save();

					NextEmail::whereIn('opening_id', $opening_ids_for_this_email)->delete();

					foreach($opening_ids_for_this_email as $opening_id){
						EmailOpening::create([
			                            "opening_id" => $opening_id,
						    "email_id" => $email_for_this_location->id
						]);
					}

					$request = new \Illuminate\Http\Request(['emailId'=>$email_for_this_location->id]);
					echo 'sending';
					$cms->sendSummaryAlert($request, $location->id);
				}
		}
    }
}
