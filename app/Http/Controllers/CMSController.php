<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Hiring_manager;
use Auth;
use DOMDocument;
use App\Rolesentry_company;
use App\ProspectSavedSearchProgress;
use App\SchemeStep;
use App\LockDetail;
use App\Lock;
use App\SchemeStepLockType;
use App\SchemeStepLockTypeDetail;
use App\ProspectingAction;
use App\Rolesentry_job;
use App\Email;
use App\Email_rolesentry_alerts;
use App\Hiring_manager_opening;
use App\Prospect;
use App\Rolesentry_alert;
use App\Location;
use App\Customization_variable;
use DB;
use App\User;
use Response;
use Excel;
use Carbon\Carbon;
use App\User_preference;
use App\Job_board_alert;	
use App\Ban_url;
use App\Ban_job_title;
use App\Ban_company_name;

use App\Job_type_word;
use App\Job_type;
use App\Job_subtype;



use App\SavedSearch;
use App\opening;
use App\EmailOpening;
use App\SavedSearchJobType;
use App\SavedSearchLocation;
use App\SavedSearchOpening;

 
class CMSController extends Controller
{
    
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->common = new CommonController;
    }

	public function delete(Request $request) {
		$newEmail = Email::find($request->get('id'));
		$newEmail->delete();
                return redirect()->action('CMSController@index');
	}

	public function createNew() {
		$newEmail = new Email();
		$newEmail->save();
                return redirect()->action('CMSController@index',['id'=>$newEmail->id]);
	}

	public function fileUpload(Request $request) {
			
		$emailId = $request->get('emailId');


		$email = $request->file('fileToUpload');
		$destinationPath = public_path('/images');
	//	$email->move($destinationPath, time() . '.' .$email->getClientOriginalExtension());
		$email->move($destinationPath, 'email.csv');

		Excel::load($destinationPath . '/email.csv', function($reader) use ($emailId){
		
			EmailOpening::where('email_id', '=',$emailId)->delete();

			

			foreach($reader->all() as $row){

					$job = $row->id ? opening::find($row->id) : new opening();
				
					$job->hiring_manager_name = $row->hiring_manager_name;
					$job->hiring_manager_position = $row->hiring_manager_position;
					$job->hiring_manager_linkedin = $row->hiring_manager_linkedin;
					$job->job_description_url = $row->job_description_url;
		
				        $location = Location::where('name','=',$row->location)->first();
			         	$job->location_id = $location->id;
					
					if ($row->job_type){
						$job_type = Job_type::where('name','=',$row->job_type)->first();
						$job->job_type_id = $job_type->id;
					}

					 $company = Rolesentry_company::where("name", $row->company_name)->first();
					   if($company === null) {

						$company = Rolesentry_company::create([
					    "name" => $row->company_name,
					    "angellist_url" => "",
					    "career_page_url" => 'legacy',
					    'xpath' => "",
					    'first_scrape' => 1,
					    'location_id' => $row->location_id,
					    'scrapeable' => false,
					    'created_at' => date("Y-m-d H:i:s",time()),
					    'updated_at' => date("Y-m-d H:i:s",time())
					]);
					   }

					$job->rolesentry_company_id = $company->id;
					$job->title = $row->title;
					$job->save();				

					$EmailAlert = new EmailOpening();
					$EmailAlert->email_id = $emailId;
					$EmailAlert->opening_id = $job->id;

					$EmailAlert->save();
				
					$job = new Rolesentry_job();
					$job->hiring_manager = $row->hiring_manager_name . ' - ' . $row->hiring_manager_position;
					$job->hiring_manager_linkedin = $row->hiring_manager_linkedin;
					$job->notes = $row->notes;
					$job->job_description_link = $row->job_description_url;
					$job->job_description_summary = $row->job_description_summary;
					
					if ($row->job_type){
						$job_type = Job_type::where('name','=',$row->job_type)->first();
						$job->job_type_id = $job_type->id;
					}
					$location = Location::where('name','=',$row->location)->first();
		
					$company = new Rolesentry_company();
					$company->name = $row->company_name;
					$company->location_id = $location->id;
					$company->scrapeable = false;
					$company->save();
					$job->rolesentry_companies_id = $company->id;
					$job->title = $row->title;
					$job->save();				
					$alert = new Rolesentry_alert();
					$alert->rolesentry_job_id = $job->id;
					$alert->title = $row->title;
					$alert->save();
					$EmailAlert = new Email_rolesentry_alerts();
					$EmailAlert->emails_id = $emailId;
					$EmailAlert->rolesentry_alert_id = $alert->id;
					$EmailAlert->save();
				
			     
			
				}	
		});

		return redirect()->action('CMSController@index',['id'=>$emailId]);

	}

 	public function downloadAlertSinceLastPublishedEmailWasCreated(Request $request){

	$lastPublishedEmail = Email::where('published_at', '!=',NULL)->orderBy('created_at', 'DESC')->first();
	$start_time = $lastPublishedEmail->created_at;

	    $headers = [
		    'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
		,   'Content-type'        => 'text/csv'
		,   'Content-Disposition' => 'attachment; filename=galleries.csv'
		,   'Expires'             => '0'
		,   'Pragma'              => 'public'
	    ];

	$hours = $request->get('hours');	
	  /*
			$openings = $this->getOpeningsForEmail(2)
						->where('openings.created_at', '>',$start_time)
						->orderBy('.location_id','DESC') //why
						->get();
	*/
/*			$alerts_used_in_previous_emails = Email_rolesentry_alerts::all()->pluck('rolesentry_alert_id')->toArray();

			$email_alerts_from_rolesentry = $email_alerts_from_rolesentry->reject(function($element) use ($alerts_used_in_previous_emails) {
				return in_array($element->id, $alerts_used_in_previous_emails);
			});
*/
			$email_alerts = $this->getOpeningsForEmail()
						->where('openings.created_at', '>', $start_time)
						->where('openings.title','NOT LIKE' ,"=%")
						->where('openings.job_description_url', "!=", "")
						->where('openings.rolesentry_company_id', "!=", "") //company ban list here

//						->whereIn('openings.job_board', ['CareerJet','Monster','SimplyHired','Indeed', 'Stackoverflow'])

						
/*
						->where('opening.job_description_url', "NOT LIKE", "%performzone%")
						->where('opening.job_description_url', "NOT LIKE", "%simplyhired%")
						->where('opening.job_description_url', "NOT LIKE", "%marcumsearch%")
						->where('opening.job_description_url', "NOT LIKE", "%toriirecruitment%")
						->where('opening.job_description_url', "NOT LIKE", "%indeed%")
						->where('opening.job_description_url', "NOT LIKE", "%careerjet%")
						->where('opening.job_description_url', "NOT LIKE", "%trovit%")
						->where('opening.job_description_url', "NOT LIKE", "%dice%")
						->where('opening.job_description_url', "NOT LIKE", "%ziprecruiter%")
						->where('opening.job_description_url', "NOT LIKE", "%careerbuilder%")

*/
						->whereIn('openings.location_id',[1,5,15,16])
						->orderBy('openings.location_id', 'DESC') //why
						->get();

	$transformed_email_alerts = opening::where('id','=','asdhfkadsjdfh')->get();

	$tech_words = Job_type_word::where('job_type_id', 1)->get()->pluck('word')->toArray();
	$tech_search_array = array_map('strtolower', $tech_words);

	$sales_words = Job_type_word::where('job_type_id', 2)->get()->pluck('word')->toArray();
	$sales_search_array = array_map('strtolower', $sales_words);

	$finance_words = Job_type_word::where('job_type_id', 10)->get()->pluck('word')->toArray();
	$finance_search_array = array_map('strtolower', $finance_words);

	$compliance_words = Job_type_word::where('job_type_id', 6)->get()->pluck('word')->toArray();
	$compliance_search_array = array_map('strtolower', $compliance_words);



	foreach($email_alerts as $email_alert) {

		$reports_pos = false;

		if (isset($email_alert->job_description)) {
			$reports_to_text_position = stripos($email_alert->job_description, 'reports to ');
			$reporting_to_text_position = stripos($email_alert->job_description, 'reporting to ');
	
			if ($reports_to_text_position == 0 && $reporting_to_text_position){
				$reports_pos = $reporting_to_text_position;
			}
			if ($reporting_to_text_position == 0 && $reports_to_text_position){
				$reports_pos = $reports_to_text_position;

			}
		}		

		if (!$this->getBanStatus($email_alert->job_description_url, $email_alert->title, $email_alert->company_name)){
			continue;
		}

		$transformed_email_alert = new opening();
		$transformed_email_alert->id = $email_alert->id ? $email_alert->id : "";
		$transformed_email_alert->title = $email_alert->title ? $email_alert->title : "";
		$transformed_email_alert->company_name = $email_alert->company_name ? $email_alert->company_name : "";
		$transformed_email_alert->job_description_url = $email_alert->job_description_url ? $email_alert->job_description_url :"";
		$transformed_email_alert->location = $email_alert->location ? $email_alert->location : "";
		$transformed_email_alert->job_type = $email_alert->job_type ? $email_alert->job_type : "";
		$transformed_email_alert->hiring_manager_name = $email_alert->hiring_manager_name ? $email_alert->hiring_manager_name : "";
		$transformed_email_alert->hiring_manager_position = $email_alert->hiring_manager_position ? $email_alert->hiring_manager_position : "";
		$transformed_email_alert->hiring_manager_linkedin = $email_alert->hiring_manager_linkedin ? $email_alert->hiring_manager_linkedin : "";
	
	//	if ($reports_pos){
	//		echo substr($email_alert->full_description, $reports_pos, 100);die();
			$transformed_email_alert->manager_auto_detect = $reports_pos ? substr($email_alert->job_description, $reports_pos, 50) : "";  
			//$transformed_email_alert->manager_auto_detect = substr($email_alert->full_description, $reports_pos, 50);  
	//	}

		$email_alert_title = "- " . $email_alert->title;

		foreach($tech_search_array as $word_to_mark_tech){
			if (stripos(strtolower($email_alert_title), $word_to_mark_tech)) {
				$transformed_email_alert->job_type = "Tech";
			}
			
		}
		foreach($compliance_search_array as $word_to_mark_compliance){
			if (stripos(strtolower($email_alert_title), $word_to_mark_compliance)) {
				$transformed_email_alert->job_type = "Compliance";
			}
			
		}
		foreach($sales_search_array as $word_to_mark_sales){
			if (stripos(strtolower($email_alert_title), $word_to_mark_sales)) {
				$transformed_email_alert->job_type = "Sales";
			}
			
		}


		foreach($finance_search_array as $word_to_mark_finance){
			if (stripos(strtolower($email_alert_title), $word_to_mark_finance)) {
				$transformed_email_alert->job_type = "Finance";
			}
			
		}

		

		$transformed_email_alerts->push($transformed_email_alert);

	}
	Excel::create('alerts', function($excel) use($transformed_email_alerts) {
	   $excel->sheet('Sheet 1', function($sheet) use($transformed_email_alerts) {
		$sheet->fromArray($transformed_email_alerts);
	   });
	})->export('csv');

    }
  
	public function download(Request $request){
	    $headers = [
		    'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
		,   'Content-type'        => 'text/csv'
		,   'Content-Disposition' => 'attachment; filename=galleries.csv'
		,   'Expires'             => '0'
		,   'Pragma'              => 'public'
	    ];

	$email_id = $request->get('id');	
	
	$email_alerts = $this->getOpeningsForEmail(1)
				->orderBy('openings.location_id','DESC')
				->where('email_id', '=',$email_id)
				->get();



	$transformed_email_alerts = opening::where('id','=','asdhfkadsjdfh')->get();

	foreach($email_alerts as $email_alert) {

		$reports_pos = false;

		if (isset($email_alert->job_description)) {
			$reports_to_text_position = stripos($email_alert->job_description, 'reports to ');
			$reporting_to_text_position = stripos($email_alert->job_description, 'reporting to ');
	
			if ($reports_to_text_position == 0 && $reporting_to_text_position){
				$reports_pos = $reporting_to_text_position;
			}
			if ($reporting_to_text_position == 0 && $reports_to_text_position){
				$reports_pos = $reports_to_text_position;

			}
		}		

		$transformed_email_alert = new opening();
		$transformed_email_alert->id = $email_alert->id ? $email_alert->id : "";
		$transformed_email_alert->title = $email_alert->title ? $email_alert->title : "";
		$transformed_email_alert->company_name = $email_alert->company_name ? $email_alert->company_name : "";
		$transformed_email_alert->job_description_url = $email_alert->job_description_url ? $email_alert->job_description_url :"";
		$transformed_email_alert->location = $email_alert->location ? $email_alert->location : "";
		$transformed_email_alert->job_type = $email_alert->job_type ? $email_alert->job_type : "";
		$transformed_email_alert->hiring_manager_name = $email_alert->hiring_manager_name ? $email_alert->hiring_manager_name : "";
		$transformed_email_alert->hiring_manager_position = $email_alert->hiring_manager_position ? $email_alert->hiring_manager_position : "";
		$transformed_email_alert->hiring_manager_linkedin = $email_alert->hiring_manager_linkedin ? $email_alert->hiring_manager_linkedin : "";
	
	//	if ($reports_pos){
	//		echo substr($email_alert->full_description, $reports_pos, 100);die();
			$transformed_email_alert->manager_auto_detect = $reports_pos ? substr($email_alert->job_description, $reports_pos, 50) : "";  
			//$transformed_email_alert->manager_auto_detect = substr($email_alert->full_description, $reports_pos, 50);  
	//	}

		$transformed_email_alerts->push($transformed_email_alert);


	}
	Excel::create('alerts', function($excel) use($transformed_email_alerts) {
	   $excel->sheet('Sheet 1', function($sheet) use($transformed_email_alerts) {
		$sheet->fromArray($transformed_email_alerts);
	   });
	})->export('csv');

    }

     public function getJobBoardAlerts(){
	

//	$alerts = JobBoardAlert::select(['NULL as id', 'job_title as title', 'company as company_name', 'job_description_on_company_page as job_description_link', 'locations.name as location','job_types.name as job_type', 'NULL as hiring_manager', 'NULL as hiring_manager_linkedin',  'NULL as notes', 'NULL as career_page_url']);
	$alerts = Job_board_alert::select(['full_description','job_title as title', 'company as company_name', 'job_description_on_company_page as job_description_link', 'locations.name as location']);

	$alerts->join('locations','locations.id','=','job_board_alerts.location_id');

	return $alerts;

     }



 
     public function getEditableAlerts($type = NULL){
	
	//DOWNLOAD TYPE 1 -- Download CSV (email alerts exist)
	//DOWNLOAD TYPE 3 -- Prince Charles??
	//Download Typw 2 -- DOWNLOAD ALERTS SINCe LAST  (email alerts dont exist)
if ($type == 1 || $type == 3){
 	$alerts = Email_rolesentry_alerts::select(['rolesentry_alerts.id as id', 'rolesentry_alerts.title as title', 'rolesentry_companies.name as company_name', 'rolesentry_jobs.job_description_link as job_description_link', 'locations.name as location','job_types.name as job_type', 'rolesentry_jobs.hiring_manager as hiring_manager', 'rolesentry_jobs.hiring_manager_linkedin',  'rolesentry_jobs.notes as notes','rolesentry_companies.career_page_url as career_page_url']);

						$alerts->join('rolesentry_alerts', 'rolesentry_alerts.id','=','email_rolesentry_alerts.rolesentry_alert_id');
}
else if ($type == 2) {

	$alerts = Rolesentry_alert::select(['rolesentry_alerts.id as id', 'rolesentry_alerts.title as title', 'rolesentry_companies.name as company_name', 'rolesentry_jobs.job_description_link as job_description_link', 'locations.name as location','job_types.name as job_type', 'rolesentry_jobs.hiring_manager as hiring_manager', 'rolesentry_jobs.hiring_manager_linkedin',  'rolesentry_jobs.notes as notes', 'rolesentry_companies.career_page_url as career_page_url']);

}
else {
 	$alerts = Email_rolesentry_alerts::select(['rolesentry_alerts.id as id', 'rolesentry_alerts.title as title', 'rolesentry_companies.name as company_name', 'rolesentry_jobs.job_description_link as job_description_link', 'locations.name as location','job_types.name as job_type','rolesentry_companies.career_page_url as career_page' , 'rolesentry_jobs.hiring_manager as hiring_manager', 'rolesentry_jobs.hiring_manager_linkedin',  'rolesentry_jobs.notes as notes', 'rolesentry_alerts.created_at as created_at', 'rolesentry_companies.logo_url as logo_url']);
	
						$alerts->join('rolesentry_alerts', 'rolesentry_alerts.id','=','email_rolesentry_alerts.rolesentry_alert_id');
}
						$alerts->join('rolesentry_jobs','rolesentry_jobs.id', '=','rolesentry_alerts.rolesentry_job_id')
						->join('rolesentry_companies','rolesentry_companies.id','=', 'rolesentry_jobs.rolesentry_companies_id')
						->join('locations','locations.id','=','rolesentry_companies.location_id')	
						->leftJoin('job_types','job_types.id','=','rolesentry_jobs.job_type_id');

	return $alerts;

     }

 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

	if (isset($_REQUEST["id"])){
	
		$email = Email::find($_REQUEST["id"]);

		$data['email'] = $email;


		$locations = Location::all();
		foreach($locations as $location) {

				$email_alerts = $this->getEditableAlerts()
						->where('rolesentry_companies.location_id','=',$location->id)
						->where('emails_id', '=',$_REQUEST["id"])
						->orderBy('rolesentry_jobs.job_type_id','DESC`')->get();
				$locationDetails = ['name'=>$location->name,
						   'alerts'=>$email_alerts];

				$data['alertLocations'][] = $locationDetails;
			
		}

		$companies = $this->common->getRightSidebarCompanies();
		$data['companies'] = $companies;
               $data['job_types'] = Job_type::all();
		$data['user_id'] = Auth::user()->id;
		return view("cms-edit-email", $data);
	}   
	
 
	$unpublished_emails = Email::where('published_at','=', NULL)->orderBy('id', 'DESC')->get();
	$published_emails = Email::where('published_at','!=', NULL)->orderBy('id', 'DESC')->get();
	$data['unpublished_emails'] = $unpublished_emails;
	$data['published_emails'] = $published_emails;
	$companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;
    
	return view("cms", $data);

    }

    public function showAlertDetail() 
    {
       // print $_REQUEST["id"];
       $alert = Rolesentry_alert::find($_REQUEST["id"]);
       $data['alert'] = $alert;
       $companies = $this->common->getRightSidebarCompanies();
       $data['companies'] = $companies;
       $locations = Location::where('id','=',1)->orWhere('id','=',2)->get();
       $data['locations'] = $locations;

        return view("alert", $data);

    }

    public function sendOutProspectingEmail($to_email, $from_email, $from_name, $subject, $body, $api_user, $api_password) 
    {

	$to_email = "tanner@recruiterintel.com";

	echo "SENDING OUT PROSPECING EMAIL $from_email $from_name $subject $body $api_user $api_password";

        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => ["tanner@recruiterintel.com","dori@varzay.com"]
        ,
        'category' => 'Recruiter Intel Hiring Manager'
        );

	if (config('app.env') != "Production"){
		$subject .= "==STAGING==";
	}

        $params = array(
            'api_user'  => $api_user,
            'api_key'   => $api_password,
            'x-smtpapi' => json_encode($json_string),
            'to'        => $to_email,
            'subject'   => $subject,
	    'text' 	=> strip_tags($body),
            'html'      => $body,
            //'text'      => 'This is a very big message for us to be sent up.',
            'from'      => $from_email,
	    'fromname'  => $from_name
        );

            $request =  $url.'api/mail.send.json';

            // Generate curl request
            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt ($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            // print everything out

    }


   
    public function sendOutSavedSearchEmail($name, $emails, $message, $term) 
    {
        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => $emails 
        ,
        'category' => 'Recruiter Intel Saved Search'
        );

	$subject = "Custom alert results for " . $term;

	if (config('app.env') != "Production"){
		$subject .= "==STAGING==";
	}

        $params = array(
            'api_user'  => config('app.api_user'),
            'api_key'   => config('app.api_password'),
            'x-smtpapi' => json_encode($json_string),
            'to'        => 'nick@varzay.com',
            'subject'   => $subject,
	    'text' 	=> strip_tags($message),
            'html'      => $message,
            //'text'      => 'This is a very big message for us to be sent up.',
            'from'      => config('app.api_email'),
	    'fromname'  => "Recruiter Intel"
        );


            $request =  $url.'api/mail.send.json';

            // Generate curl request
            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            // print everything out

    }


    public function sendMail($name, $emails, $message, $type=1, $location_id, $email_id=0) 
    {
        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => $emails 
        ,
        'category' => 'Recruiter Intel Alert',
	    'unique_args' => array('email_id'=> $email_id)
        );

        if($type == 2){ $subject = "Preliminary Admin Alert";}
        else {
		$location = Location::find($location_id); 
		if ($name) {
			$subject = $name."'s hiring manager report for " . $location->name;
		}
		else{
			$subject = 'Hiring manager report for ' . $location->name;
		}
	}

	if (config('app.env') != "Production"){
		$subject .= "==STAGING==";
	}

        $params = array(

            'api_user'  => config('app.api_user'),
            'api_key'   => config('app.api_password'),
            'x-smtpapi' => json_encode($json_string),
            'to'        => 'nick@varzay.com',
            'subject'   => $subject,
	    'text' 	=> strip_tags($message),
            'html'      => $message,
            //'text'      => 'This is a very big message for us to be sent up.',
            'from'      => config('app.api_email'),
	    'fromname'  => "Recruiter Intel"
        );


            $request =  $url.'api/mail.send.json';

            // Generate curl request
            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            // print everything out

    }

   public function embargo(Request $request){
	$emailId = $request->get('emailId');
	$toggle = $request->get('toggle');

        $email = Email::find($emailId);
//        if ($email->published_at) {
  //              return redirect("/cms");

    //    }

	if ($toggle == 1) {
		$email->embargo_new_york = 1;
		$email->embargo_san_francisco = 1;
		$email->embargo_sydney = 1;
		$email->embargo_melbourne = 1;
	}
	else{
		$email->embargo_new_york = 0;
		$email->embargo_san_francisco = 0;
		$email->embargo_sydney = 0;
		$email->embargo_melbourne = 0;
	}

	$email->save();
        return redirect("/cms");	

   }

   public function sendSummaryAlert(Request $request, $location_id) {

	$emailId = $request->get('emailId');

	$email = Email::find($emailId);

	$alertLocations = $this->getAlertLocationsForSavedSearchEmail(NULL,$location_id, $email->id);

	if (!$alertLocations){
		return redirect('/home');
	}

        $subscribers = User::all();
	
        foreach($subscribers as $subscriber) {
		//echo $subscriber->email;
		$uniqueEmail = $this->getTemplateForSummaryEmail($alertLocations, $subscriber->id);
		if ($uniqueEmail && (!$subscriber->is_locked)){
		//	echo 'would send . '.($uniqueEmail == "").'. to ' . $subscriber->email;
			$this->sendMail(($subscriber->name ? $subscriber->name : false),[$subscriber->email], $uniqueEmail, 1, $location_id, $email->id);
		}
        }

	$email->published_at = date("Y-m-d H:i:s");
	$email->save();
       		return redirect("/cms");
   }

   public function sendAutomatedEmail($email_body_and_subject, $prospect_saved_search_progress_id) {

	$prospect_saved_search_progress = ProspectSavedSearchProgress::find($prospect_saved_search_progress_id);
	
	$prospect = Prospect::find($prospect_saved_search_progress->prospect_id);

	if ($prospect->type_id == 1) {
		$prospect = Hiring_manager::where('prospect_id', $prospect->id)->first();
	}

	$subscriber = User::find($prospect_saved_search_progress->user_id);

	$body = $email_body_and_subject['email_body'];
	$subject = $email_body_and_subject['email_subject'];

	$pa = ProspectingAction::create(['prospecting_action_type_id'=>1,
				    'prospect_id'=>$prospect->id,
				    'prospect_saved_search_progresses_id'=>$prospect_saved_search_progress_id,
				    'scheme_step_id'=>$prospect_saved_search_progress->current_scheme_step_id,
				    'saved_search_id'=>$prospect_saved_search_progress->saved_search_id,
				    'subject'=>$subject,
				    'message'=>$body]);

	$to_email = $prospect->email; 

        $url = 'https://api.sendgrid.com/';

	$bcc_emails = $subscriber->email;

        $json_string = array(

        'to' => [$to_email],
	'bcc' => [$bcc_emails]
        ,
        'category' => 'Recruiter Intel Hiring Manager',
	    'unique_args' => array('email_id'=> $pa->id)
        );

	if (config('app.env') != "Production"){
		$subject .= "==STAGING==";
	}

        $params = array(
            'api_user'  => $subscriber->prospecting_api_user,
            'api_key'   => $subscriber->prospecting_api_password,
            'x-smtpapi' => json_encode($json_string),
            'to'        => $to_email,
	    'bcc'	=> $bcc_emails,
            'subject'   => $subject,
	    'text' 	=> strip_tags($body),
            'html'      => $body,
            //'text'      => 'This is a very big message for us to be sent up.',
            'from'      => $subscriber->prospecting_from_email,
	    'fromname'  => $subscriber->prospecting_from_name
        );

	print_r($params);

	

            $request =  $url.'api/mail.send.json';

            // Generate curl request
            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt ($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);

	print_r($params);		

	echo $response;


            curl_close($session);


//NOW send to subscriber

            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST

	    $params['to'] = $bcc_emails;
 
        $json_string = array(
	'to' => [$bcc_emails]
        ,
        'category' => 'Recruiter Intel Hiring Manager'
        );

           $params['x-smtpapi'] = json_encode($json_string);
 
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt ($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

	     print_r($params);

            // obtain response
            $response = curl_exec($session);
	echo $response;	


            // print everything out
   } 
	
   public function populateAutomatedEmail($pssp_id, $scheme_step_id) {

				
	
			$pssp = ProspectSavedSearchProgress::find($pssp_id);
			$scheme_step = SchemeStep::find($scheme_step_id);

			$all_custom_variables = Customization_variable::all();
			$custom_variables = [];

			$prospect = Prospect::find($pssp->prospect_id);

			if ($pssp->opening_id){
				$opening = Opening::find($pssp->opening_id);
			}	

			if ($prospect->type_id == 1){
				$prospect_instance = Hiring_manager::where('prospect_id', $prospect->id)->first();
				$company = Rolesentry_company::where('id', $prospect_instance->company_id)->first();
				if ($pssp->opening_id) {
					$job_title = $opening->title;
				}
			}

			foreach($all_custom_variables as $custom_variable) {
				if ($custom_variable->id == 1){
					$first_space = strpos($prospect_instance->name, " ");
					$hiringManagerNameBeforeSpace = substr($prospect_instance->name, 0, $first_space);
					$custom_variables[$custom_variable->name] = $hiringManagerNameBeforeSpace;
				}
				else if ($custom_variable->id == 2){
					$first_space = strpos($prospect_instance->name, " ");
					$hiringManagerNameAfterFirstSpace = substr($prospect_instance->name,$first_space+1);
					$custom_variables[$custom_variable->name] = $hiringManagerNameAfterFirstSpace;
				}
				else if ($custom_variable->id == 3){				
					$custom_variables[$custom_variable->name] = $company->name;
				}
				else if ($custom_variable->id == 4){				
					$custom_variables[$custom_variable->name] = $prospect_instance->phone;
				}
				else if ($custom_variable->id == 5){				
					$custom_variables[$custom_variable->name] = $job_title;
				}
		        }
	
				$populated_template_subject = $this->populateProspectingTemplate($custom_variables, $scheme_step->email_subject);
				$populated_template_body = $this->populateProspectingTemplate($custom_variables, $scheme_step->email_body);
			
		
		return ['email_body' => $populated_template_body,
			'email_subject' => $populated_template_subject];
   }

   public function make_locks_for_next_scheme_step($pssp) {

	$scheme_step_lock_types = SchemeStepLockType::where('scheme_step_id',$pssp->current_scheme_step_id)->get();

	foreach($scheme_step_lock_types as $scheme_step_lock_type){
		$new_lock = new Lock;
		$new_lock->lock_type_id = $scheme_step_lock_type->lock_type_id;

		if ($new_lock->lock_type_id == 2){
			$new_lock->is_email_sent = 0; //this is for before the email is sent in the scheme step
			$new_approval_lock_detail = SchemeStepLockTypeDetail::where('scheme_step_lock_types_id', $scheme_step_lock_type->id)->first();
			if ($new_approval_lock_detail->value == 0){
				continue; //no approval needed for this step
			}	
			
		}

		$new_lock->prospect_saved_search_progress_id = $pssp->id;
		$new_lock->scheme_step_id = $scheme_step_lock_type->scheme_step_id;
		$new_lock->save();

		$scheme_step_lock_details = SchemeStepLockTypeDetail::where('scheme_step_lock_types_id', $scheme_step_lock_type->id)->get();

		foreach($scheme_step_lock_details as $scheme_step_lock_detail) {
			$new_lock_details = new LockDetail;
			$new_lock_details->lock_id = $new_lock->id;
			$new_lock_details->value = $scheme_step_lock_detail->value;

			$new_lock_details->save();
		}	
	}
   }

   public function sendSavedSearchEmail($savedSearch) {

	$alertLocations = $this->getAlertLocationsForSavedSearchEmail($savedSearch->id);
   
	if (!$alertLocations){
		return false;
	}

	$saved_search_openings = SavedSearchOpening::where('saved_search_id', $savedSearch->id)->whereNull('time_sent')->get();
	foreach($saved_search_openings as $saved_search_opening) {


		

		$opening = Opening::find($saved_search_opening->opening_id);
		$hiring_manager_opening = Hiring_manager_opening::where('opening_id', $opening->id)->orderBy('certainty', 'DESC')->first();
		if (!$hiring_manager_opening){
			continue;
		}
		$hiring_manager = Hiring_manager::find($hiring_manager_opening->hiring_manager_id);
		$prospect = Prospect::find($hiring_manager->prospect_id);

		$active_pssp_this_subscriber_has_with_this_prospect = ProspectSavedSearchProgress::where('user_id', $savedSearch->user_id)
													->where('prospect_id', $prospect->id)
													->where('is_finished', 0)
													->first();

		echo 'here';
		if ($prospect->reachable && !$active_pssp_this_subscriber_has_with_this_prospect) {


			echo 'creating PSSP';

			$pssp = new ProspectSavedSearchProgress();

			$pssp->user_id = $savedSearch->user_id;
			$pssp->prospect_id = $hiring_manager->prospect_id;
			$pssp->opening_id = $opening->id;
			$pssp->saved_search_id = $savedSearch->id;
			$pssp->is_finished = 0;

			$first_scheme_step = SchemeStep::where('saved_search_id', $savedSearch->id)->orderBy('id','ASC')->first();

			if ($first_scheme_step){

				$pssp->current_scheme_step_id = $first_scheme_step->id;
				$pssp->save();
				$this->make_locks_for_next_scheme_step($pssp);
			}
		}

		$saved_search_opening->time_sent = date("Y-m-d H:i:s");
	        $saved_search_opening->save(); 
	} 


   }

   public function sendProspectDirectEmail(Request $request) {

	$email_subject = $_REQUEST["email_subject"];
	$email_message = $_REQUEST["eml_body"];
	$prospect_email = $_REQUEST["prospect_email"];
	$prospect_id = $_REQUEST["prospect_id"];
	$user = User::find(Auth::id());
	//print_r($_REQUEST);

	$pa = ProspectingAction::create(['prospecting_action_type_id'=>4,
				    'prospect_id'=>$prospect_id,
				    'subject'=>$email_subject,
				    'message'=>$email_message]);

		

	$this->sendProspectingEmail($email_subject, $email_message, $prospect_email, $user);
	return redirect("/prospect?id=".$prospect_id);

   }

   public function sendProspectingEmail($email_subject, $email_message, $prospect_email, $user) {
	//User is recruiter sending automatic email to hiring manager or candidate.  User is our subscriber

	$to_email = $prospect_email;

        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => [$to_email]
        ,
        'category' => 'Recruiter Intel Message'
        );

	if (config('app.env') != "Production"){
		$email_subject .= "==STAGING==";
	}

        $params = array(
            'api_user'  => $user->prospecting_api_user,
            'api_key'   => $user->prospecting_api_password,
            'x-smtpapi' => json_encode($json_string),
            'to'        => $to_email,
            'subject'   => $email_subject,
	    'text' 	=> strip_tags($email_message),
            'html'      => $email_message,
            //'text'      => 'This is a very big message for us to be sent up.',
            'from'      => $user->prospecting_from_email,
	    'fromname'  => $user->prospecting_from_name,
        );

            $request =  $url.'api/mail.send.json';

            // Generate curl request
            $session = curl_init($request);
            // Tell curl to use HTTP POST
            curl_setopt ($session, CURLOPT_POST, true);
            // Tell curl that this is the body of the POST
            curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
            // Tell curl not to return headers, but do return the response
            curl_setopt ($session, CURLOPT_HEADER, false);
            // Tell PHP not to use SSLv3 (instead opting for TLS)
            curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
            curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

            // obtain response
            $response = curl_exec($session);
            curl_close($session);
            // print everything out

   }


   public function prospectHiringManager($user_id, $prospect_email, $subject, $body) {
		echo "PROSPECTINGHIRINGMANAGRER****";
	  $prospectingUser = User::find($user_id);
	echo "SENDING OUT PROSPEcTING EMAIL";
	  $this->sendOutProspectingEmail($prospect_email, $prospectingUser->prospecting_from_email, $prospectingUser->prospecting_from_name, $subject, $body, $prospectingUser->prospecting_api_user, $prospectingUser->prospecting_api_password);
   }

   public function populateProspectingTemplate($custom_variables, $template){
	
		print_r($custom_variables);

		echo "DOING TEMPLATE NOW";
	
		foreach($custom_variables as $custom_variable_key => $custom_variable_value) {
			print_r($custom_variable_value);
			echo "Replacing all {%$custom_variable_key%} with $custom_variable_value";
			$template = str_replace('{%'.$custom_variable_key.'%}',$custom_variable_value, $template);
		}

		return $template;
   }

   public function previewSavedSearchAlert(Request $request) {
	$saved_search_id = $request->get('id');
	$saved_search = SavedSearch::find($saved_search_id);

	$alertLocations = $this->getAlertLocationsForSavedSearchEmail($saved_search_id);
	
        if (!$alertLocations){
                return false;
        }
	echo $this->getTemplateForSavedSearchEmail($saved_search->term, $alertLocations, Auth::user()->id, true);
   }

   public function previewSummaryAlert(Request $request) {

	$emailId = $request->get('emailId');

        $email = Email::find($emailId);
        $alertLocations = $this->getAlertLocationsForSavedSearchEmail(NULL,NULL,$email->id);

	echo $this->getTemplateForSummaryEmail($alertLocations, Auth::user()->id, true); 

   }


   public function getOpeningsForEmail($type = NULL){

	if ($type == 1){
		$alerts = EmailOpening::join('openings', 'email_openings.opening_id','=','openings.id')
		->join("hiring_managers", 'hiring_managers.id','=','openings.hiring_manager_id')
/*		->join('hiring_manager_openings', function($q){
			$q->on('hiring_manager_openings.opening_id', '=', 'openings.id')
			   ->where('hiring_manager_openings.hiring_manager_id', '=', 'openings.hiring_manager_id');
		})
*/
		->select(['job_description','openings.id as id', 'openings.title as title', 'rolesentry_companies.name as company_name', 'openings.job_description_url as job_description_url', 'locations.name as location','job_types.name as job_type','rolesentry_companies.career_page_url as career_page' , 'hiring_managers.name as hiring_manager_name','hiring_managers.linkedin_url','hiring_managers.title as hiring_manager_position', 'openings.created_at as created_at', 'hiring_managers.phone as hiring_manager_phone', 'hiring_managers.email as hiring_manager_email','hiring_managers.id as hiring_manager_id']); 
	}
	else{
		$alerts = opening::join("hiring_managers", 'hiring_managers.id','=','openings.hiring_manager_id')
/*		->join('hiring_manager_openings', function($q){
			$q->on('hiring_manager_openings.opening_id', '=', 'openings.id')
			   ->where('hiring_manager_openings.hiring_manager_id', '=', 'openings.hiring_manager_id');
		})
*/
		->select(['job_description','openings.id as id', 'openings.title as title', 'rolesentry_companies.name as company_name', 'openings.job_description_url as job_description_url', 'locations.name as location','job_types.name as job_type','rolesentry_companies.career_page_url as career_page' , 'hiring_managers.name as hiring_manager_name','hiring_managers.linkedin_url','hiring_managers.title as hiring_manager_position', 'openings.created_at as created_at', 'hiring_managers.phone as hiring_manager_phone', 'hiring_managers.email as hiring_manager_email', 'hiring_managers.id as hiring_manager_id']);
	}
						 $alerts->join('rolesentry_companies','rolesentry_companies.id','=', 'openings.rolesentry_company_id')
							->join('locations','locations.id','=','openings.location_id')
							->leftJoin('job_types','job_types.id','=','openings.job_type_id');	

	return $alerts;

   }

   public function getAlertLocationsForSavedSearchEmail($saved_search_id = NULL, $location_id = NULL, $email_id = NULL) {

	if (!$location_id){
		$locations = Location::all();
	}
	else{
		$locations = Location::where('id',$location_id)->get();
	}

	$saved_search = SavedSearch::find($saved_search_id);

	$alertLocations = [];
	$not_empty = false;
	foreach($locations as $location){
		$not_empty_location = false;
		$openings = $this->getOpeningsForEmail();

		if ($saved_search_id) {
			$openings->join('saved_search_openings', 'saved_search_openings.opening_id','=','openings.id')
			->whereNull('time_sent')
			->whereNotNull('openings.hiring_manager_id')
			->where('saved_search_openings.saved_search_id', $saved_search_id)
			->where('openings.location_id', '=', $location->id);
		}

		else if ($email_id) {
				$openings->join('email_openings', 'email_openings.opening_id','=','openings.id')
				->where('openings.location_id', '=', $location->id)
				->where('email_openings.email_id', $email_id)
				->orderBy('openings.location_id', 'DESC')
				->orderBy('openings.job_type_id', 'ASC')
				->orderBy('openings.hiring_manager_certainty', 'DESC');
		}
	
		$openings = $openings->get();

		echo "SENDING 772";

			foreach($openings as &$opening) {

				echo "opening_id: " . $opening->id . ' hm_id' . $opening->hiring_manager_id;

				$most_probable_hiring_manager = Hiring_manager_opening::where('opening_id', $opening->id)
									->where('hiring_manager_id', $opening->hiring_manager_id)
									->first();

				echo "HMCERTAINTY - " . $most_probable_hiring_manager->certainty;

				if ($most_probable_hiring_manager->certainty < 100){
					$opening->hiring_manager_alternatives = Hiring_manager_opening::join('hiring_managers', 'hiring_managers.id', '=','hiring_manager_openings.hiring_manager_id')->where('opening_id',$opening->id)
						->where('hiring_managers.id',"!=",$most_probable_hiring_manager->hiring_manager_id)	
						->orderBy('certainty', "DESC")
						->take(2)->get();
				}
			}

		if (!$openings->isEmpty()){
			$not_empty = true;
			$not_empty_location = true;
		}	

		$locationDetails = ['name' => $location->name,
				    'alerts' => $openings];

		if ($not_empty_location){
			$alertLocations[] = $locationDetails;	
		}
	}
	
	if (!$not_empty){
		return false;
	}

	return $alertLocations;	
   }


   public function getAlertLocationsForEmail($email, $location_id = NULL){


	if (!$location_id){
		$locations = Location::all();
	}
	else{
		$locations = Location::where('id',$location_id)->get();
	}

	$alertLocations = [];
	$not_empty = false;



	foreach($locations as $location) {
				$email_alerts = $this->getEditableAlerts()		
	
							->where('rolesentry_companies.location_id','=',$location->id)
							->where('email_rolesentry_alerts.emails_id', '=',$email->id)
							->orderBy('rolesentry_jobs.job_type_id','ASC')
							->orderBy('rolesentry_jobs.hiring_manager_linkedin', 'DESC')
							->orderBy('rolesentry_companies.name', 'DESC')->get();

		if (!$email_alerts->isEmpty()){
			$not_empty = true;
		}
				
		$locationDetails = ['name'=>$location->name,
				   'alerts'=>$email_alerts];

		$alertLocations[] = $locationDetails;

	}


	if (!$not_empty){
		return false;
	}

	return $alertLocations;	
   }


       public function getTemplateForSavedSearchEmail($term, $alertLocations, $user_id, $IN_PREVIEW_MODE = false) {

	$exists = false;

	$firstLocation = ($alertLocations[0]['name']);

       $template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
       <html xmlns="https://www.w3.org/1999/xhtml">
        <head>
         <title>Recruiter Intel</title>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
         
         <style type="text/css">
           html{
               width: 100%; 
           }
       
           body{
             width: 100%;  
             margin:auto !important; 
             padding:0; 
             background: #E7E7E7;
           }
       
           p,h1,h2,h3,h4{
             margin-top:0;
             margin-bottom:0;
             padding-top:0;
             padding-bottom:0;
           }
       
           table{
             font-size: 14px;
             border: 0;
           }
	tbody td{
	}
	tbody tr:nth-child(odd) .colorrows{
	  background-color: #4C8BF5;
	  color: #fff;
	}
           img{
             border: none!important;
           }
         </style>
       
         <!--  Responsive CSS  -->
       </head>
       <body style="margin: 0; padding: 0;" yahoo="fix">
       
         <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#2a3647" style="background-size: cover; -webkit-background-size: cover; -moz-background-size: cover -o-background-size: cover; background-position: bottom center; background-repeat: no-repeat; background-color:#2a3647;">
       
           <!--  header  -->
           <tr>
             <td>
                 <table width="100%" cellpadding="0" cellspacing="0" align="center" border="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; border:0; text-align:center;" class="content_width">    
                   <!--  Logo  -->
                   <tr style="background-color:white;text-align:left"> 
                     <td>
                       <a href="'.config('app.url').'"><img style="padding:10px; padding-left:0px" src="https://recruiterintel.com/images/ri-logo.png" alt="" title="" border="0" style="border:0; display:inline_block;"/></a>
                     </td>
		     <td style="    font-weight: normal;
    font-size: 20px;
    padding:15px;
    font-family: Georgia;
    font-style: italic;
    margin: 11px 0 17px;
    color: #2d2d2d;">Saved Custom Alert - '.$term.'</td>
                   </tr>
                 </table>
             </td>
           </tr>
           <!--  end header  -->
           
         </table><!--  end billboard  -->
       
         <!--  features section  -->
         <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fbfbfb" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
             <!--  spacing  -->
             <!--  end spacing  -->
       
             <tr>
               <td>
                 <table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:center;" class="content_width">
                   <!--  section title  -->
';

	foreach($alertLocations as $alertLocation){
		$banner_exists = false;
		$last_location_type = "newsection";
		if (sizeof($alertLocation['alerts'])){
			
			$template .= '<tr> <td style="text-align:left;padding:10px;display: block; width: 100%; color:white; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700;     background-color: #344865; text-decoration:none; letter-spacing:1px; text-transform:uppercase;">'. $alertLocation['name'] .'</td></tr>';
			foreach($alertLocation['alerts'] as $alert){
			$num = 0;
			if (!$exists){
				$exists = true;
			}
				$num++;
				$template .= '<tr><td>';
				$template .= $this->getSection($alert, $num, $IN_PREVIEW_MODE);
				$template .= '</td></tr>';
			}
			$num = 0;
		$template .="";
		}
	}
$template .='
                   <!--  end section title  -->
      		</table>
	      </td>
	     </tr> 
        </table>
          <!--  blog posts section  -->
         <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fbfbfb" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
             <!--  spacing  -->
             <tr>
               <td width="100%" height="50">&nbsp;</td>
             </tr>
             <!--  end spacing  -->
       
             <tr>
               <td>
                
       
         <!--  take action  -->
         <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fbfbfb" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background: #526178; background: -webkit-gradient(linear, left top, color-stop(0%,#55647b), color-stop(100%,#414d60)); background: -webkit-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: -moz-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: -ms-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: -o-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: linear-gradient(bottom, #55647b 0%, #414d60 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#55647b\', endColorstr=\'#414d60\',GradientType=1 ); text-align:center;">
             
             <!--  take action text  -->
             <tr>
               <td>
                 <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="content_width">
                   <tr>
                     <td style="line-height:13px;color: #fff; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 30px; letter-spacing:1px; text-align:center; font-weight: 300; font-size:10px;padding:18px;"> The information contained in this publication is provided by Recruiter Intel, Inc. for informational purposes only and should never be construed as professional services advice. Recruiter Intel obtains information from a wide variety of publicly available sources and does not certify or guarantee the accuracy or completeness of the information discussed in this publication. Recruiter Intel and it\'s employees disclaim all liability in respect to actions taken based on any or all of the contents of this publication. Copyright 2018 Recruiter Intel, Inc. All rights reserved. <a href="https://recruiterintel.com/unsubscribe">Unsubscribe from all emails</a></td>
                   </tr>
                 </table>
               </td>
             </tr>
             <!--  end take action text  -->
       
         </table>
         <!--  end take action  -->
       
       </body>
       </html>';
	
	return $exists ? $template : false;


	}

       public function getTemplateForSummaryEmail($alertLocations, $user_id, $IN_PREVIEW_MODE = false) {
	if (!$alertLocations){
		return false;
	}

	$exists = false;


	$firstLocation = ($alertLocations[0]['name']);

       $template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
       <html xmlns="https://www.w3.org/1999/xhtml">
        <head>
         <title>Recruiter Intel</title>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
         
         <style type="text/css">
           html{
               width: 100%; 
           }
       
           body{
             width: 100%;  
             margin:auto !important; 
             padding:0; 
             background: #E7E7E7;
           }
       
           p,h1,h2,h3,h4{
             margin-top:0;
             margin-bottom:0;
             padding-top:0;
             padding-bottom:0;
           }
       
           table{
             font-size: 14px;
             border: 0;
           }
	tbody td{
	}
	tbody tr:nth-child(odd) .colorrows{
	  background-color: #4C8BF5;
	  color: #fff;
	}
           img{
             border: none!important;
           }
         </style>
       
         <!--  Responsive CSS  -->
       </head>
       <body style="margin: 0; padding: 0;" yahoo="fix">
       
         <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#2a3647" style="background-size: cover; -webkit-background-size: cover; -moz-background-size: cover -o-background-size: cover; background-position: bottom center; background-repeat: no-repeat; background-color:#2a3647;">
       
           <!--  header  -->
           <tr>
             <td>
                 <table width="100%" cellpadding="0" cellspacing="0" align="center" border="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; border:0; text-align:center;" class="content_width">    
                   <!--  Logo  -->
                   <tr style="background-color:white;text-align:left"> 
                     <td>
                       <a href="'.config('app.url').'"><img style="padding:10px; padding-left:0px" src="https://recruiterintel.com/images/ri-logo.png" alt="" title="" border="0" style="border:0; display:inline_block;"/></a>
                     </td>
		     <td style="    font-weight: normal;
    font-size: 20px;
    padding:15px;
    font-family: Georgia;
    font-style: italic;
    margin: 11px 0 17px;
    color: #2d2d2d;">New Openings - '.$firstLocation.'</td>
                   </tr>
                 </table>
             </td>
           </tr>
           <!--  end header  -->
           
         </table><!--  end billboard  -->
       
         <!--  features section  -->
         <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fbfbfb" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
             <!--  spacing  -->
             <!--  end spacing  -->
       
             <tr>
               <td>
                 <table width="100%" border="0" cellpadding="0" cellspacing="0" style="text-align:center;" class="content_width">
                   <!--  section title  -->
';


	foreach($alertLocations as $alertLocation){
		$banner_exists = false;
		$last_job_type = "newsection";
		if (sizeof($alertLocation['alerts'])){
			
			$num = 0;
			foreach($alertLocation['alerts'] as $alert){
			
			if ($user_id){
				if (!$this->userHasThisPreference($user_id, $alertLocation['name'],($alert['job_type'] ? $alert['job_type'] : "Professional"))) {
					continue;
				}
			}
			if (!$exists){
				$exists = true;
			}
			if (!$banner_exists){
				$banner_exists = true;
			}
	
		if ($last_job_type != $alert['job_type']) {
			$last_job_type = $alert['job_type'];
			$template .= '<tr> <td style="text-align:left;padding:10px;display: block; width: 100%; color:white; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700;     background-color: #344865; text-decoration:none; letter-spacing:1px; text-transform:uppercase;">'.(($alert['job_type'] == "" ) ? "Professional" : $alert['job_type']).'</td></tr>';
		}
				$num++;
				$template .= '<tr><td>';
				$template .= $this->getSection($alert, $num, $IN_PREVIEW_MODE);
				$template .= '</td></tr>';
			}
			$num = 0;
		$template .="";
		}
	}
$template .='
                   <!--  end section title  -->
      		</table>
	      </td>
	     </tr> 
        </table>
          <!--  blog posts section  -->
         <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fbfbfb" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;">
             <!--  spacing  -->
             <tr>
               <td width="100%" height="50">&nbsp;</td>
             </tr>
             <!--  end spacing  -->
       
             <tr>
               <td>
                
       
         <!--  take action  -->
         <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#fbfbfb" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; background: #526178; background: -webkit-gradient(linear, left top, color-stop(0%,#55647b), color-stop(100%,#414d60)); background: -webkit-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: -moz-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: -ms-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: -o-linear-gradient(bottom, #55647b 0%, #414d60 100%); background: linear-gradient(bottom, #55647b 0%, #414d60 100%); filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\'#55647b\', endColorstr=\'#414d60\',GradientType=1 ); text-align:center;">
             
             <!--  take action text  -->
             <tr>
               <td>
                 <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0" class="content_width">
                   <tr>
                     <td style="line-height:13px;color: #fff; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 30px; letter-spacing:1px; text-align:center; font-weight: 300; font-size:10px;padding:18px;"> The information contained in this publication is provided by Recruiter Intel, Inc. for informational purposes only and should never be construed as professional services advice. Recruiter Intel obtains information from a wide variety of publicly available sources and does not certify or guarantee the accuracy or completeness of the information discussed in this publication. Recruiter Intel and it\'s employees disclaim all liability in respect to actions taken based on any or all of the contents of this publication. Copyright 2018 Recruiter Intel, Inc. All rights reserved. <a href="https://recruiterintel.com/unsubscribe">Unsubscribe from all emails</a></td>
                   </tr>
                 </table>
               </td>
             </tr>
             <!--  end take action text  -->
       
         </table>
         <!--  end take action  -->
       
       </body>
       </html>';
	
	return $exists ? $template : false;


    }

	public function getJobSubTypes($job_type_id) {
						
		$job_subtype_names = [];
		$job_subtype_names = Job_subtype::where("job_type_id", $job_type_id)->pluck("name")->toArray();
					
		return implode(',', $job_subtype_names);
	}
 	
    public function getSection($alert, $num, $IN_PREVIEW_MODE = false) {

	$hiring_manager_name = $alert->hiring_manager_name;
	$hiring_manager_position = $alert->hiring_manager_position;
	$hiring_manager_linkedin = $alert->linkedin_url;
	$hiring_manager_id = $alert->hiring_manager_id;
	$hiring_manager_alternatives = $alert->hiring_manager_alternatives;

	$hiring_manager_phone = $alert->hiring_manager_phone;
	$hiring_manager_email = $alert->hiring_manager_email;

	$job_subtypes_names = $this->getJobSubTypes($alert->job_type_id);

	$top_hiring_manager_for_this_role = Hiring_manager_opening::where('opening_id', $alert->id)->where('hiring_manager_id', $alert->hiring_manager_id)->first();
	$subtypes = '';
	
	if ($job_subtypes_names) $subtypes = ' ( '.$job_subtypes_names.' ) ';



	$response = '
                   <tr>
                     <td style="'.($num % 2 == 0 ? 'background-color:#e8e8e8;' : '' ).'padding:35px 15px 15px 15px;color: #414d5f; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-weight: 300; letter-spacing:.5px; text-align:left;    border-bottom: 0.5px dotted black; ">
		<div class="row">
<span style="padding:15px;font-size:20px"><i>'.$alert->company_name.'</i></span>
		 </div>  

		<div class="row">
		     <span style="padding:10px 5px 5px 15px;display:inline-block;font-size:20px;font-weight:bold" class="col-xs-12;col-md-12">'.$alert->title.' '.$subtypes.' </span>
		</div>
		<div class="row">
		     <span style="padding:10px 5px 5px 15px;display:inline-block" class="col-xs-12;col-md-12"><b>Full Job Description: </b> <a href="'.$alert->job_description_url.'">'.$alert->company_name.' career page</a></span>
		 </div>    ';


		echo "HIRING MANAGER ID: " . $hiring_manager_id;



		$response .='<div class="row"><span style="padding:10px 5px 5px 15px;display:inline-block" class="col-xs-12;col-md-12"><b>Date Opened: </b>'.date('l M j', strtotime('-5 hours', strtotime($alert->created_at))).'</span></div>';
		
		if ($hiring_manager_id){

			if ($hiring_manager_name && $hiring_manager_position) {

				echo "NAME" . $hiring_manager_name . '--PHONE' . $hiring_manager_position;

				if (count($hiring_manager_alternatives) > 0){

					$response .= $this->getHiringManagerSection("Most Likely Hiring Manager", $hiring_manager_name, $hiring_manager_position, $hiring_manager_linkedin, $hiring_manager_phone, $hiring_manager_email);
					foreach($hiring_manager_alternatives as $hiring_manager_alternative) {
						$response .= $this->getHiringManagerSection("Possible Alternative Hiring Manager", $hiring_manager_alternative->name, $hiring_manager_alternative->title, $hiring_manager_alternative->linkedin_url,$hiring_manager_alternative->phone, $hiring_manager_alternative->email);
					}
		
				}
				else {
					$hiring_manager_certainty_verbage = ( $top_hiring_manager_for_this_role->certainty > 99 ) ? "" : "Most Likely ";  
					$response .= $this->getHiringManagerSection($hiring_manager_certainty_verbage ."Hiring Manager", $hiring_manager_name, $hiring_manager_position, $hiring_manager_linkedin, $hiring_manager_phone, $hiring_manager_email);
				}
			}
		}

if ($IN_PREVIEW_MODE){
	$response .= '

					 <span style="padding:15px;display:inline-block;width:170px;" class="col-xs-2;col-md-2">

<a href="/delete-cms-preview-email-alert?id='.$alert->id.'&emailId='.$_REQUEST["emailId"].'" class="btn btn-info" role="button">Delete</a></span>';

}
$response .= '
                     </td>
                   </tr>';

	return $response;
    }

    public function getHiringManagerSection($certainty, $name, $position, $linkedin, $phone, $email) {


		$response = "";

		if ($name && $position) {

			$response .= "<hr>";
			$response.= '<div class="row"> <span style="padding:10px 5px 5px 15px;display:inline-block" class="col-xs-12;col-md-12"><b>'.$certainty.'</b> </span></div>';

			$response.= '<div class="row"> <span style="padding:10px 5px 5px 15px;display:inline-block" class="col-xs-12;col-md-12"><i>LinkedIn: </i> <a href="'.$linkedin.'">'.$name.'</a> <i>'.$position.'</i></span>
			</div>
			';
			
			if ($phone) {
			    $response .='<div class="row"><span style="padding:10px 5px 5px 15px;display:inline-block" class="col-xs-12;col-md-12"><i>Phone Number: </i>'.$phone.'</span></div>';
			}

			if ($email) {
			    $response .='<div class="row"><span style="padding:10px 5px 5px 15px;display:inline-block" class="col-xs-12;col-md-12"><i>Email: </i>'.$email.'</span></div>';
			}
		}

		return $response;

	}

    public function userHasThisPreference($user_id, $location_name, $job_type_name, $location_id = NULL, $job_type_id = NULL) {

	 $location = Location::where('name','=',$location_name)->first();
	 $location_id = $location->id;

	 $job_type = Job_type::where('name','=',$job_type_name)->first();
	if ($job_type === null){
		//check if user has 'all' selected
		return false;
	}
	 $job_type_id = $job_type->id;

	 $preference = User_preference::where('user_id',$user_id)
					->where('location_id', $location_id)
					->where('job_type_id', $job_type_id)->first();	
	 if ($preference === null){
		return false;
	 }
	 else{
		return true;
	 }
    }

   //Edit CMS Email Alert

  public function deleteCmsEmailAlert(Request $request) 
  {

	
	$email_alert = Email_rolesentry_alerts::where("rolesentry_alert_id", $_REQUEST["id"])->delete();
	/*$trying = $email_alert->delete();
	echo $trying;
		echo 'tying';
		if (!$email_alert->delete()){
		echo 'tying';
			if (!$email_alert->delete()){
		echo 'tying';
				if (!$email_alert->delete()){
		echo 'tying';
				}x c
			}
	
		}
	die();
     */
	return redirect("/cms?id=".$_REQUEST["email_id"]);

  }

  public function deleteCMSPreviewEmailAlert() 
  {
        $email_alert = opening::where('id', '=',$_REQUEST["id"])->delete();	
	return redirect("/cms/previewSummaryAlert?emailId=".$_REQUEST["emailId"]);
  }

   //Edit CMS Email Alert

   public function editCmsEmailAlert(Request $request) 
   {
        $id = $_REQUEST["id"];
		$alert = Rolesentry_alert::find($_REQUEST["id"]);
		$job = Rolesentry_job::find($alert->job->id);
			
		$job->title = $_REQUEST["title_".$id];
		$job->hiring_manager = $_REQUEST["hiring_manager_".$id];
		$job->job_description_link = $_REQUEST["job_description_link_".$id];
		$job->job_type_id = $_REQUEST["job_type_".$id];
		$job->hiring_manager_linkedin  = $_REQUEST["hiring_manager_linkedin_".$id];
		$job->notes  = $_REQUEST["notes_".$id];
		$job->job_description_summary = $_REQUEST["job_description_summary_".$id];
		$job->save();

		$company = Rolesentry_company::find($job->company->id);
		$company->name = $_REQUEST["company_".$id];
		$company->save();

		$alert->hiring_manager = $_REQUEST["hiring_manager_".$id];
		$alert->title = $_REQUEST["title_".$id];
		$alert->link_to_job_description = $_REQUEST["job_description_link_".$id];
		$alert->save();

		return response()->json([ 'success' => '1']);
   }

       public function getBanStatus($url, $job_title, $company)
        {

		$ban_terms_url = Ban_url::all()->pluck('term')->toArray();
		$ban_terms_search_array = array_map('strtolower', $ban_terms_url);

                foreach($ban_terms_search_array as $ban_word){
                        if (stripos(strtolower($url), $ban_word) != "") {
                                return false;
                        }
                }

		$ban_terms_job_title = Ban_job_title::all()->pluck('term')->toArray();
		$ban_terms_search_array = array_map('strtolower', $ban_terms_job_title);

                foreach($ban_terms_search_array as $ban_word){
	
                        if (stripos(strtolower($job_title), $ban_word) != "") {
                                return false;
                        }
                }

		$ban_terms_company_name = Ban_company_name::all()->pluck('term')->toArray();
		$ban_terms_search_array = array_map('strtolower', $ban_terms_company_name);

                foreach($ban_terms_search_array as $ban_word){
                        if (stripos(strtolower($company), $ban_word) != "") {
                                return false;
                        }
                }

                return true;

		}
		
	
		public function sendEmailToOneUser() 
		{

			$data["success"] = false;
			$email_opening = EmailOpening::where("opening_id", $_REQUEST["opening_id_email"])->first();
			
			if($email_opening == null) 
			{
					if(isset($_REQUEST["opening_item_email"]) 
							&& !empty($_REQUEST["opening_item_email"]) 
							&& !empty($_REQUEST["opening_id_email"])) 
					{
						$newEmail = new Email();
						$newEmail->save();

						EmailOpening::create([
							"email_id" => $newEmail->id,
							"opening_id" => $_REQUEST["opening_id_email"]
						]);
						
						$location_id = 1;
						$this->sendMail((Auth::user()->name ? ucfirst(Auth::user()->name) : false),[$_REQUEST["opening_item_email"]], $_REQUEST["opening_item_email"], 1, $location_id, $newEmail->id);
						$data["message"] = "Successfully added!!!";			
					$data["success"] = true;
					}
			} else $data["message"] = "This Opening ID already exists!!!";		
			return response()->json($data);
		}

}
