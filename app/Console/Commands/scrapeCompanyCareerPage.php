<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class scrapeCompanyCareerPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:career_page';

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
public function handle(){
	$url_for_node_server = config('app.scraperurl');

	$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	);  

	$scrapeSessionParameters = json_decode($this->getScrapeSessionParameters());
	

	//var_dump($scrapeSessionParameters);
	$results = $this->getResultsFromNodeServer($scrapeSessionParameters->career_page_url, $scrapeSessionParameters->xpath, $url_for_node_server);
	$this->update_job_postings($scrapeSessionParameters->id, $results, $scrapeSessionParameters->first_scrape);

    }

    public function getResultsFromNodeServer($career_page_url, $xpath, $url_for_node_server) {
	
	$response = @file_get_contents($url_for_node_server . '?page=' . urlencode($career_page_url) . '&selector=' . urlencode($xpath));
	return json_decode($response);
    }

    public function update_job_postings($company_id, $job_postings, $first_scrape) {

	//echo $company_id . "-" . $first_scrape;
	if(empty($job_postings)) return;

	foreach($job_postings as $job_posting){

		$pieces = explode(" 9999x9999 ", $job_posting);

		$job_posting = $pieces[0];
		$job_description_link = $pieces[1];

		echo 'Job: ' . $job_posting;
		echo 'JD Link: ' . $job_description_link;

		$this_job = DB::table('rolesentry_jobs')->where('title', '=', $job_posting)->where('rolesentry_companies_id', '=',$company_id)->first();

		//var_dump($this_job);
	
		if (!$this_job) {

			echo 'DIDNT SEE THIS JOB - ADDING';

			$last_job_id = DB::table('rolesentry_jobs')->insertGetId(
			    ['rolesentry_companies_id' => $company_id, 
			     'title' => $job_posting,
			     'job_description_link' => $job_description_link,
			     'created_at' => date('Y-m-d H:i:s')
			    ]
			);

			var_dump($last_job_id);

			if($first_scrape == 0){

				echo "not first scrape - alerting";

				$last_alert_id = DB::table('rolesentry_alerts')->insertGetId(
				    ['rolesentry_job_id' => $last_job_id, 
				     'title' => $job_posting,
				     'updated_at' => date('Y-m-d H:i:s'),
				     'created_at' => date('Y-m-d H:i:s')
				    ]
				);

				$send_email_url = config('app.url').'/send-preliminary-email/'.$last_alert_id;

				echo $send_email_url;

				$ch = curl_init($send_email_url);
				
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$response = json_decode(curl_exec($ch));

			}
		
		}else{

			echo 'had this one updating last_seen';
			DB::table('rolesentry_jobs')->where('id',"=",$this_job->id)
				->update(['last_seen_at'=>date('Y-m-d H:i:s')]);
		}

	}

	DB::table('rolesentry_companies')
                ->where('id', $company_id)
                ->update(['first_scrape'=> 0]);

    }

    public function getScrapeSessionParameters()
    {

	$company_to_scrape = DB::table('rolesentry_companies')->orderBy('last_scrape','ASC')->whereNull('legacy')->whereNull('scrapeable')->first();


	DB::table('rolesentry_companies')
		->where('id', $company_to_scrape->id)
		->update(['last_scrape'=> date("Y-m-d H:i:s")]);

	$response = [];
	$response['id'] = $company_to_scrape->id;
	$response['name'] = $company_to_scrape->name;
	$response['career_page_url'] = $company_to_scrape->career_page_url;
	$response['xpath'] = $company_to_scrape->xpath;
	$response['needs_custom'] = $company_to_scrape->needs_custom;
	$response['first_scrape'] = $company_to_scrape->first_scrape;
	
	$response['ip'] = \Request::ip();	

	$responseJSON = json_encode($response);

	//self::logScrape('get_iscrape_session_parameters', $responseJSON, $response['ip']);

	return $responseJSON;
    }

}
