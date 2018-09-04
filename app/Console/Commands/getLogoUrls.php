<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Rolesentry_company;
use App\Rolesentry_alert;
use App\Rolesentry_job;
class getLogoUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:logos';

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
/*
	$alerts = Rolesentry_alert::all();
	foreach($alerts as $alert){
		$alert_job = Rolesentry_job::find($alert->rolesentry_job_id);
		echo ".";
	
		if ($alert_job != null){
			
			$alert->title = $alert_job->title;
			$alert->save();
			echo 'saving';
		}
	}
*/
	$companies = Rolesentry_company::where('tried_logo','!=',1)->orWhereNull('tried_logo')->get();
	foreach($companies as $company) {
		$url = $this->getLogoURL($company->id);
		$company->logo_url = $url;
		$company->tried_logo = 1;
		$company->save();
		//echo "saved ".$company->logo_url."logo";
	}
    }

    public function getLogoURL($company_id) {

	$company = Rolesentry_company::find($company_id);
	if ($company->logo_url){
		return $company->logo_url;
	}

  	$params = array(
        );

		$requestUrl = 'https://autocomplete.clearbit.com/v1/companies/suggest?query='.$company->name;
            // Generate curl request
            $session = curl_init($requestUrl);
            // Tell curl to use HTTP POST

            // Tell curl not to return headers, but do return the response
            curl_setopt($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);

	    $JSONResponse = json_decode($response);

            curl_close($session);

		if ($JSONResponse){
			if (is_array($JSONResponse)){
				if (isset($JSONResponse[0])){
					if (isset($JSONResponse[0]->logo)){
						return $JSONResponse[0]->logo;
					}
				}
			}
		}
	    return false;
    }


}
