<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DOMDocument;
use App\Rolesentry_company;
use App\Rolesentry_job;
use App\Email;
use App\Email_rolesentry_alerts;
use App\Rolesentry_alert;
use App\Job_type;
use App\Location;
use DB;
use App\User;
use Response;
use Excel;
use Carbon\Carbon;
use App\User_preference;

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
		
			Email_rolesentry_alerts::where('emails_id', '=',$emailId)->delete();

			foreach($reader->all() as $row){

				$alert = Rolesentry_alert::find($row->id);
				
				$alert->title = $row->title;
				$alert->save();

				$alert->job->hiring_manager = $row->hiring_manager;
				$alert->job->hiring_manager_linkedin = $row->hiring_manager_linkedin;
				$alert->job->notes = $row->notes;
				$alert->job->job_description_link = $row->job_description_link;
				
				if ($row->job_type){
				$job_type = Job_type::where('name','=',$row->job_type)->first();
					$alert->job->job_type_id = $job_type->id;
					}
				$alert->job->save();
				$location = Location::where('name','=',$row->location)->first();

				$company = Rolesentry_company::find($alert->job->rolesentry_companies_id);
				if ($row->company_name){
					$company->name = $row->company_name;
				//	$company->career_page_url = $row->career_page;
					$company->location_id = $location->id;
					$company->save();
				}
	
				$EmailAlert = new Email_rolesentry_alerts();
				$EmailAlert->emails_id = $emailId;
				$EmailAlert->rolesentry_alert_id = $alert->id;

				$EmailAlert->save();
			}
	
		});

		return redirect()->action('CMSController@index',['id'=>$emailId]);

	}
/*
 	public function downloadAlert(Request $request){
	    $headers = [
		    'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0'
		,   'Content-type'        => 'text/csv'
		,   'Content-Disposition' => 'attachment; filename=galleries.csv'
		,   'Expires'             => '0'
		,   'Pragma'              => 'public'
	    ];

	$hours = $request->get('hours');	
	    
	$email_alerts = Rolesentry_alert::select(['rolesentry_alerts.id as id', 'rolesentry_jobs.title as title', 'rolesentry_companies.name as company_name', 'rolesentry_jobs.notes as notes','rolesentry_jobs.hiring_manager as hiring_manager', 'rolesentry_alerts.recent_funding as recent_funding', 'rolesentry_jobs.job_description_link as job_description_link', 'locations.name as location', 'rolesentry_alerts.created_at as created_at', 'rolesentry_companies.career_page_url as career_page'])
						->join('rolesentry_jobs','rolesentry_jobs.id', '=','rolesentry_alerts.rolesentry_job_id')
						->join('rolesentry_companies','rolesentry_companies.id','=', 'rolesentry_jobs.rolesentry_companies_id')
						->join('locations','locations.id','=','rolesentry_companies.location_id')	
						->where('rolesentry_alerts.created_at', '>',Carbon::now()->subHours($hours)->toDateTimeString())
						->orderBy('rolesentry_companies.location_id','DESC')
						->get();


	Excel::create('alerts', function($excel) use($email_alerts) {
	   $excel->sheet('Sheet 1', function($sheet) use($email_alerts) {
		$sheet->fromArray($email_alerts);
	   });
	})->export('csv');

    }
*/
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
	    
			$email_alerts = $this->getEditableAlerts(2)
						->where('rolesentry_alerts.created_at', '>',$start_time)
						->orderBy('rolesentry_companies.location_id','DESC')
						->get();




	foreach($email_alerts as $email_alert) {
		$email_alert_title = "- " . $email_alert->title;
		if (stripos($email_alert_title, 'Sales')) {
			$email_alert->job_type = "Sales";
		}
		else if (stripos($email_alert_title, 'engineer')){
				$email_alert->job_type = "Tech";
		}
		else if (stripos($email_alert_title, 'software')){
				$email_alert->job_type = "Tech";
		}
		else if (stripos($email_alert_title, 'product manager')){
				$email_alert->job_type = "Tech";
		}
		else if (stripos($email_alert_title, 'lawyer')){
				$email_alert->job_type = "Legal";
		}
		else if (stripos($email_alert_title, 'complian')){
				$email_alert->job_type = "Compliance";
		}
		else if (stripos($email_alert_title, 'ccounting')){
				$email_alert->job_type = "Compliance";
		}

	}

	Excel::create('alerts', function($excel) use($email_alerts) {
	   $excel->sheet('Sheet 1', function($sheet) use($email_alerts) {
		$sheet->fromArray($email_alerts);
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
	
	$email_alerts = $this->getEditableAlerts(1)
				->orderBy('rolesentry_companies.location_id','DESC')
				->where('emails_id', '=',$email_id)
				->get();

	Excel::create('alerts', function($excel) use($email_alerts) {
	   $excel->sheet('Sheet 1', function($sheet) use($email_alerts) {
		$sheet->fromArray($email_alerts);
	   });
	})->export('csv');

    }

     public function getEditableAlerts($type = NULL){
	
	//DOWNLOAD TYPE 1 -- Download CSV (email alerts exist)
	//DOWNLOAD TYPE 3 -- Prince Charles??
	//Download Typw 2 -- DOWNLOAD ALERTS SINCe LAST  (email alerts dont exist)
if ($type == 1 || $type == 3){
 	$alerts = Email_rolesentry_alerts::select(['rolesentry_alerts.id as id', 'rolesentry_alerts.title as title', 'rolesentry_companies.name as company_name', 'rolesentry_jobs.job_description_link as job_description_link', 'locations.name as location','job_types.name as job_type', 'rolesentry_jobs.hiring_manager as hiring_manager', 'rolesentry_jobs.hiring_manager_linkedin',  'rolesentry_jobs.notes as notes']);

						$alerts->join('rolesentry_alerts', 'rolesentry_alerts.id','=','email_rolesentry_alerts.rolesentry_alert_id');
}
else if ($type == 2) {

	$alerts = Rolesentry_alert::select(['rolesentry_alerts.id as id', 'rolesentry_alerts.title as title', 'rolesentry_companies.name as company_name', 'rolesentry_jobs.job_description_link as job_description_link', 'locations.name as location','job_types.name as job_type', 'rolesentry_jobs.hiring_manager as hiring_manager', 'rolesentry_jobs.hiring_manager_linkedin',  'rolesentry_jobs.notes as notes']);

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
   
    public function sendMail($emails, $message, $type=1) 
    {
        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => $emails 
        ,
        'category' => 'Recruiter Intel Alert'
        );

        if($type == 2) $subject = "Preliminary Admin Alert";
        else $subject = "Recruiter Intel Job Openings Summary " . date('Y-m-d');

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
            'from'      => config('app.api_email')
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

   public function sendSummaryAlert(Request $request) {

	$emailId = $request->get('emailId');

	$email = Email::find($emailId);
	if ($email->published_at){
       		return redirect("/cms");
		
	}

	$alertLocations = $this->getAlertLocationsForEmail($email);

	if (!$alertLocations){
		return redirect('/home');
	}

        $subscribers = User::all();
        foreach($subscribers as $subscriber) {
		$uniqueEmail = $this->getTemplateForSummaryEmail($alertLocations, $subscriber->id);
		if ($uniqueEmail){
			$this->sendMail([$subscriber->email], $uniqueEmail);
		}
        }

	$email->published_at = date("Y-m-d H:i:s");
	$email->save();
       		return redirect("/cms");
   }

   public function previewSummaryAlert(Request $request) {

	$emailId = $request->get('emailId');

        $email = Email::find($emailId);
        $alertLocations = $this->getAlertLocationsForEmail($email);

	echo $this->getTemplateForSummaryEmail($alertLocations, Auth::user()->id); 

   }



   public function getAlertLocationsForEmail($email){
	$locations = Location::all();
	$alertLocations = [];
	$not_empty = false;

	foreach($locations as $location) {
				$email_alerts = $this->getEditableAlerts()		
	
							->where('rolesentry_companies.location_id','=',$location->id)
							->where('email_rolesentry_alerts.emails_id', '=',$email->id)
							->orderBy('rolesentry_jobs.job_type_id','DESC')
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

       public function getTemplateForSummaryEmail($alertLocations, $user_id) {

	$exists = false;

       $template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
       <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
         <title>Recruiter Intel</title>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
         
         <link href="http://fonts.googleapis.com/css?family=Raleway:600,700,400" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> 
         <style type="text/css">
           html{
               width: 100%; 
           }
       
           body{
             width: 100%;  
             margin:auto !important; 
             padding:0; 
             -webkit-font-smoothing: antialiased;
             mso-margin-top-alt:0px; 
             mso-margin-bottom-alt:0px; 
             mso-padding-alt: 0px 0px 0px 0px;
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
         <style type="text/css">
           @media only screen and (max-width: 800px) {
             body[yahoo] .quote_full_width {width:100% !important;}
             body[yahoo] .quote_content_width {width:90% !important;}
           }
       
           @media only screen and (max-width: 640px) {
             body[yahoo] .full_width {width:100% !important;}
             body[yahoo] .content_width{width:80% !important;}
             body[yahoo] .center_txt {text-align: center!important;}
             body[yahoo] .post_sep {width:100% !important; height:60px !important;}
             body[yahoo] .gal_sep {width:100% !important; height:40px !important;}
             body[yahoo] .gal_img {width:100% !important;}
             body[yahoo] .bb_space {height:90px !important;}
           
           }  
         </style>
       </head>
       <body style="margin: 0; padding: 0;" yahoo="fix">
       
         <table border="0" cellpadding="0" cellspacing="0" width="100%" background="http://recruiterintel.com/fr-images/billboard.jpg" bgcolor="#2a3647" style="background-image:url(\'http://recruiterintel.com/fr-images/billboard.jpg\'); background-size: cover; -webkit-background-size: cover; -moz-background-size: cover -o-background-size: cover; background-position: bottom center; background-repeat: no-repeat; background-color:#2a3647;">
       
           <!--  header  -->
           <tr>
             <td>
                 <table width="100%" cellpadding="0" cellspacing="0" align="center" border="0" style="border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; border:0; text-align:center;" class="content_width">    
                   <!--  Logo  -->
                   <tr> 
                     <td>
                       <a href="'.config('app.url').'"><img style="padding:10px" src="http://recruiterintel.com/top-logo2.png" width="195" height="75" alt="" title="" border="0" style="border:0; display:inline_block;"/></a>
                     </td>
                   </tr>
		   <tr>
                     <td style="padding:10px;display: block; width: 100%; color: #fff; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; text-decoration:none; letter-spacing:1px; text-transform:uppercase;">Recruiter Intel Job Openings Summary<br> <font color="#c7feff">'.date('l M j, ga', strtotime('-5 hours')).' </font></td>

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

		 $location_baner = '<tr><td><table style="margin-top:10px;" border="0" cellpadding="0" cellspacing="0" width="100%" background="http://recruiterintel.com/fr-images/billboard.jpg" bgcolor="#2a3647" style="margin-top:10px; margin-bottom:10px;background-image:url(\'http://recruiterintel.com/fr-images/billboard.jpg\'); background-size: cover; -webkit-background-size: cover; -moz-background-size: cover -o-background-size: cover; background-position: bottom center; background-repeat: no-repeat; background-color:#2a3647;"><tr><td style="padding:10px;display: block; width: 100%; color: #fff; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; text-decoration:none; letter-spacing:1px; text-transform:uppercase;">'.$alertLocation['name'].'</td></tr></table></td></tr>';
			
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
				$template .= $location_baner;
				$banner_exists = true;
			}
	
		if ($last_job_type != $alert['job_type']) {
			$last_job_type = $alert['job_type'];

			$template .= '<tr> <td style="padding:10px;display: block; width: 100%; color: #fff; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700;     background-color: #9ac0f9;color:#2b3647; text-align:left;text-decoration:none; letter-spacing:1px; text-transform:uppercase;">'.(($alert['job_type'] == "" ) ? "Professional" : $alert['job_type']).'</td></tr>';
		}
				$num++;
				$template .= '<tr><td>';
				$template .= $this->getSection($alert, $num);
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
                     <td style="line-height:13px;color: #fff; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 30px; letter-spacing:1px; text-align:center; font-weight: 300; font-size:10px;padding:18px;"> The information contained in this publication is provided by Recruiter Intel, Inc. for informational purposes only and should never be construed as professional services advice. Recruiter Intel obtains information from a wide variety of publicly available sources and does not certify or guarantee the accuracy or completeness of the information discussed in this publication. Recruiter Intel and it\'s employees disclaim all liability in respect to actions taken based on any or all of the contents of this publication. Copyright 2018 Recruiter Intel, Inc. All rights reserved. <a href="http://recruiterintel.com/unsubscribe">Unsubscribe from all emails</a></td>
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

 	
    public function getSection($alert, $num) {
	return '
                   <tr>
                     <td style="'.($num % 2 == 0 ? 'background-color:#f0f5fd;' : '' ).'padding:15px;color: #414d5f; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 300; letter-spacing:.5px; text-align:justify;    border-bottom: 0.5px dotted black; ">
			<div class="row">
                     <span style="padding:15px;display:inline-block;width:200px;" class="col-xs-2;col-md-2"><table><tr><td><img height=16 width=16 src="'.$alert->logo_url.'"></td><td><i>'.$alert->company_name.'</i></td><tr></table></span>
		     <span style="padding:15px;display:inline-block;width:450px;" class="col-xs-2;col-md-2"><a href="'.$alert->job_description_link.'">'.$alert->title.'</a></span>
		     <span style="padding:15px;display:inline-block;width:450px;" class="col-xs-2;col-md-2">'.($alert->hiring_manager_linkedin ? '<b>Hiring Manager: </b> '.$alert->hiring_manager.' <a href="'.$alert->hiring_manager_linkedin.'"><img height="16" width="16" src="http://recruiterintel.com/Linkedin.png"></a> ' : '') .'</span>
                     <span style="padding:15px;display:inline-block;width:170px;" class="col-xs-2;col-md-2"><img height="16" width="16" src="http://recruiterintel.com/clock.png"> '.date('l M j', strtotime('-5 hours', strtotime($alert->created_at))).'</span>
			</div>
                     </td>
                   </tr>';

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
  //Delete CMS Email Alert

  public function deleteCmsEmailAlert(Request $request) 
  {

	//print_r($_REQUEST);
	Email_rolesentry_alerts::where("rolesentry_alert_id", $_REQUEST["id"])->first()->delete();
	
	return redirect("/cms?id=".$_REQUEST["email_id"]);

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

}
