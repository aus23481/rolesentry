<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DOMDocument;
use App\Rolesentry_company;
use App\Rolesentry_job;
use App\Rolesentry_alert;
use App\Email_rolesentry_alerts;
use App\Location;
use App\Email;
use DB;
use App\User;

class AlertController extends Controller
{
    
    
    public function __construct()
    {
        $this->common = new CommonController;
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

	$this->sendAlert($this->getTemplateForSummaryEmail($alertLocations));
	$email->published_at = date("Y-m-d H:i:s");
	$email->save();
       		return redirect("/cms");
   }

   public function previewSummaryAlert(Request $request) {

	$emailId = $request->get('emailId');

        $email = Email::find($emailId);
        $alertLocations = $this->getAlertLocationsForEmail($email);

	echo $this->getTemplateForSummaryEmail($alertLocations); 

   }

   public function getAlertLocationsForEmail($email){
	$locations = Location::all();
	$alertLocations = [];
	$not_empty = false;

	foreach($locations as $location) {

		$email_alerts = Email_rolesentry_alerts::select(['rolesentry_alerts.id as id', 'rolesentry_jobs.title as title', 'rolesentry_companies.name as company_name', 'rolesentry_alerts.hiring_manager as hiring_manager', 'rolesentry_alerts.recent_funding as recent_funding', 'rolesentry_jobs.job_description_link as job_description_link','rolesentry_companies.career_page_url as career_page','rolesentry_jobs.created_at as created_at'])
							->join('rolesentry_alerts', 'rolesentry_alerts.id','=','email_rolesentry_alerts.rolesentry_alert_id')
							->join('rolesentry_jobs','rolesentry_jobs.id', '=','rolesentry_alerts.rolesentry_job_id')
							->join('rolesentry_companies','rolesentry_companies.id','=', 'rolesentry_jobs.rolesentry_companies_id')
							->where('rolesentry_companies.location_id','=',$location->id)
							->where('emails_id', '=',$email->id)
							->orderBy('rolesentry_companies.name')->get();

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

    public function getTemplateForSummaryEmail($alertLocations) {

       $template = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
       <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
         <title>Recruiter Intel</title>
         <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
         <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
         
         <link href="http://fonts.googleapis.com/css?family=Raleway:600,700,400" rel="stylesheet" type="text/css">
         
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

		if (sizeof($alertLocation['alerts'])){

		 $template .= '<tr><td><table style="margin-top:10px;" border="0" cellpadding="0" cellspacing="0" width="100%" background="http://recruiterintel.com/fr-images/billboard.jpg" bgcolor="#2a3647" style="margin-top:10px; margin-bottom:10px;background-image:url(\'http://recruiterintel.com/fr-images/billboard.jpg\'); background-size: cover; -webkit-background-size: cover; -moz-background-size: cover -o-background-size: cover; background-position: bottom center; background-repeat: no-repeat; background-color:#2a3647;">';
		
			$template .= '<tr> <td style="padding:10px;display: block; width: 100%; color: #fff; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 28px; font-weight: 700; text-decoration:none; letter-spacing:1px; text-transform:uppercase;">'.$alertLocation['name'].'</td></tr></table></td></tr>';

			$num = 0;
			foreach($alertLocation['alerts'] as $alert){
				$num++;
				$template .= '<tr><td>';
				$template .= $this->getSection($alert, $num);
				$template .= '</td></tr>';
			}
			$num = 0;
		
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
	
	return $template;


    }

         
	
    public function getSection($alert, $num) {
dd($alert);	
	return '
                   <tr>
                     <td style="'.($num % 2 == 0 ? 'background-color:#c9dffd;' : '' ).'padding:10px;color: #414d5f; font-family: \'Raleway\', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 300; letter-spacing:.5px; text-align:justify; ">
			<div class="row">
                     <span style="padding:10px;display:inline-block;width:200px;" class="col-xs-2;col-md-2"><i>'.$alert->company_name.'</i></span>
		     <span style="padding:10px;display:inline-block;width:500px;" class="col-xs-4;col-md-4">('.$alert->job_type.')<b>'.$alert->title.'</b></span>
		     <span style="padding:10px;display:inline-block;width:200px;" class="col-xs-4;col-md-2"><a href="'.$alert->job_description_link.'">Full Job Description</a></span>
                     <span style="padding:10px;display:inline-block;width:300px;" class="col-xs-2;col-md-2">Opened '.date('l M j, g:ia', strtotime('-5 hours', strtotime($alert->created_at))).'</span>
			</div>
                     </td>
                   </tr>';

    }

    public function sendAlert($msg) 
    {

/*
	if ($locations == NULL || $locations == array(1,2)) {
        	$user_emails = User::all()->pluck("email");
		echo "all";
	}
	else {
	

		if ($locations == [1]) {
			$user_emails = User::where('has_new_york','=',1)->pluck('email');
			echo "just ny";
		}
		else if ($locations == [2]) {
			$user_emails = User::where('has_california','=',1)->pluck('email');
			echo "just CA";
		}
	}
        $emails = [];
*/
        $user_emails = User::all()->pluck("email");
        foreach($user_emails as $email) {
            $emails[] = $email;
        }

        $this->sendMail($emails, $msg);
    }

    public function sendAlertAdmin($msg="") 
    {
        //type 1 for admin
        $users_emails = User::where("type",1)->pluck("email");
        $emails = [];
        foreach($users_emails as $email) {
            $emails[] = $email;
        }
        
        $this->sendMail($emails,  $msg);
    }

    public function sendPreliminaryEmailAlert($alertid) 
    {
        //type 1 for admin
        $users_emails = User::where("type",1)->pluck("email");
        $emails = [];
        foreach($users_emails as $email) {
            $emails[] = $email;
        }
        $alert = Rolesentry_alert::find($alertid);
        if($alert && (stripos($alert->job->title, 'Engineer') 
		   ||stripos($alert->job->title, 'Development')	
		   ||stripos($alert->job->title, 'Engineering')	
		   ||stripos($alert->job->title, 'Director')	
		   ||stripos($alert->job->title, 'Manager')	
		   ||stripos($alert->job->title, 'Front')	
		   ||stripos($alert->job->title, 'Andriod')	
		   ||stripos($alert->job->title, 'Project')	
		   ||stripos($alert->job->title, 'Senior')	
		   ||stripos($alert->job->title, 'Executive')	
		   ||stripos($alert->job->title, 'VP')	
		   ||stripos($alert->job->title, 'Vice President')	
		   ||stripos($alert->job->title, 'Lead')	
		   ||stripos($alert->job->title, 'QA')	
		   ||stripos($alert->job->title, 'Quality Assurance')	
		   ||stripos($alert->job->title, 'Software')	
		   ||stripos($alert->job->title, 'Full Stack')	
		   ||stripos($alert->job->title, 'Head')	
		   ||stripos($alert->job->title, 'Product')	
		   ||stripos($alert->job->title, 'Sales')	
		   ||stripos($alert->job->title, 'Architect')	
		   ||stripos($alert->job->title, 'Data')	
	
		))
	{

         $msg = "New Role Created<br>Title: ".$alert->job->title."<br>Company: ".ucfirst($alert->job->company->name) ."<br>Link to add information and send Alert:<a href=\"".config('app.url')."\alert?id=$alertid\">Alert Sender</a> Location_id: ";

	$location = Location::find($alert->job->company->location_id);

	$msg .= $location->name;


	$job = Rolesentry_job::find($alert->rolesentry_job_id);	

	$company = Rolesentry_company::find($job->rolesentry_companies_id);

	 $msg .= "<br><br>" . $company->name . " <a href=\"".$company->career_page_url."\">Career Page</a> <br><br> <a href=\"".$company->angellist_url."\">Angel list page</a>";
         $this->sendMail($emails,  $msg, 2);
        }    
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
            print_r($response);

    }

   
}
