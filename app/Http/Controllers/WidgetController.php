<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_type;
use App\Location;
use App\Search_term;
use App\Rolesentry_company;
use App\opening;
use App\SavedSearch;
use App\NextEmail;
use App\SavedSearchJobType;
use App\SavedSearchLocation;
use App\SavedSearchOpening;
use App\User_hidden_opening;
use App\Ban_job_title;
use App\Ban_company_name;
use App\Ban_url;
use App\User_favorite;
use App\Hiring_manager;
use App\User_invite;
use App\User;
use App\User_preference;
use App\Opening_subtype;
use App\Recruiting_firm;

use DB;
use Auth;

class WidgetController extends Controller
{
    
    
    public function __construct()
    {
       // $this->middleware('auth');
       // $this->common = new CommonController;
    }
    
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
           

    public function recruiterWidget($recruiter_firm_id, $job_type_ids, $job_subtype_ids, $location_ids) {
        
        $alerts = DB::table("openings")
            ->join("job_types", 'openings.job_type_id', '=', 'job_types.id')
            ->join("job_subtypes", 'job_subtypes.job_type_id', '=', 'job_types.id')
            ->join("rolesentry_companies", 'openings.rolesentry_company_id','=','rolesentry_companies.id')
            ->join("locations", 'locations.id','=','openings.location_id')
            ->select(DB::raw('openings.job_description_url, openings.job_description_url_on_job_board,rolesentry_companies.logo_url, openings.id as sl, job_types.name as job_type, job_types.id as job_type_id,  rolesentry_companies.name as company, rolesentry_companies.id as rolesentry_company_id, case when length(openings.title) > 50 then concat(LEFT(openings.title, 50), "...") else openings.title end as title, openings.created_at as created_at,locations.name as location, locations.id as location_id,date_format(openings.created_at, "%b %d") AS time_ago, hiring_managers.name as hiring_manager_name, hiring_managers.hiring_manager_position as hiring_manager_position, hiring_managers.linkedin_url as hiring_manager_linkedin, openings.manager_auto_detect as manager_auto_detect, hring_manager_openings.hiring_manager_id as hiring_manager_id'))
        ->distinct()
        ->where('openings.is_deleted', 0)->take(10)->orderByRaw('DATE(openings.created_at) DESC');
        

         if(!empty($job_type_ids)) $alerts->whereIn('openings.job_type_id', explode(',', $job_type_ids));
         if(!empty($job_subtype_ids)) $alerts->whereIn('job_subtypes.id', explode(',', $job_subtype_ids));
         if(!empty($location_ids)) $alerts->whereIn('openings.location_id', explode(',', $location_ids));
         
         //$data["sql"] = $alerts->toSql(); 
         $alerts = $alerts->get();

          $html = "";
          foreach($alerts as $alert) {

            $html .= '<div style="width:400;text-align:center;font-size:15px">';  
            $html .= '<div><a href="'.$alert->job_description_url_on_job_board.'">'.$alert->title.'</a></div>';
            $html .= '<div>This is job posting for '.$alert->title.'. Come work for us in our office at '.$alert->location.' or choose the Sanfrancisco office.</div>';
            $html .= '<div><a onclick="document.getElementById(\'email-container-'.$alert->sl.'\').style.display = \'block\';" href="#">Click here to apply</a></div>';
            $html .= '<div id="email-container-'.$alert->sl.'" style="display:none" class="hide"><form method=get action="'.url("/widget-send-email").'"> <input type="hidden" name="recruiter_firm_id" value="'.$recruiter_firm_id.'"> <input type="hidden" name="link" value="'.$alert->job_description_url_on_job_board.'"> <input type="text" name="email" type="email" id="email" placeholder="type your email"><input type="submit" name="submit" value="submit"> </form> </div>';
            $html .= '<br> <br> <hr></div>';
          }

          $data["html"] = $html;


         return response()->json($data); 

    }



    public function sendWidgetEmail() {

        $recruiter_firm = Recruiting_firm::find($_REQUEST["recruiter_firm_id"]); 

        $message = '
        Hi, <br><br>
        Here is  Widget user request with following information<br><br>        
        Email:'.$_REQUEST["email"].' <br>
        name:'.$recruiter_firm->name.' <br>
        Job Description Link: '.$_REQUEST["link"].' <br>
        
       <br><br>
From Recruiter Intel Web Desk!';   


        $this->sendMail([$_REQUEST["email"], $recruiter_firm->email], $message, $_REQUEST["email"]);

        return redirect(url("/")."/widget");
        
    }

    public function sendMail($emails, $message, $name, $type=1) 
    {
        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => $emails 
        ,
        'category' => 'Recruiter Intel Registration'
        );

        
        $subject = "Please Check your email for your Recruiter Intel account";

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

}
