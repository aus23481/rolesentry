<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DOMDocument;
use App\Rolesentry_company;
use App\Rolesentry_job;
use App\Rolesentry_alert;
use DB;
use App\Mail\AlertEmail;
use App\Location;
use App\Job_type;
use App\Job_subtype;
use App\User_preference;
use App\Contact;
use App\Unsubscribe;
use App\Location_autoselect;
use App\User_preferences_subtype;

use App\Home_page_form_submission;
use App\User;
use Slack;

class WelcomeController extends Controller
{
    
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function unsubscribe(){
	return view("unsubscribe");
    }

    public function about() {


        $data['locations'] = Location::whereIn('id',[1,3,5,14])->get();
        $data['locations_all'] = Location::where("show_in_preferences", 1)->orderBy('priority')->get();
        $data['job_types'] = Job_type::all();
 

        return view("about", $data);
    }

    public function faq() {

        $data['locations'] = Location::whereIn('id',[1,3,5,14])->get();
        $data['locations_all'] = Location::where("show_in_preferences", 1)->orderBy('priority')->get();
        $data['job_types'] = Job_type::all();
 

        return view("faq", $data);
    }

    public function unsubscribeAction()
    {
        Unsubscribe::create([
            "email" => $_REQUEST["email"],
            "reason" => $_REQUEST["reason"],
            'created_at' => date("Y-m-d H:i:s",time()),
            'updated_at' => date("Y-m-d H:i:s",time()),
        ]); 
        
        $user = User::where("email", $_REQUEST["email"])->first();
        
        if($user != null)    
        User_preference::where('user_id', $user->id)->delete();

         $message = '
        Hi Admin, <br><br>
        Here is new user unsubscribe request with following information<br><br>        
        Email:'.$_REQUEST["email"].' <br>
        Reason: '.$_REQUEST["reason"].' <br>
        
       <br><br>
From Recruiter Intel Web Desk!';   
$this->sendMail(["tanner@recruiterintel.com","nick@varzay.com"], $message, $_REQUEST["email"]);
      return  redirect("/");
    }

    public function sendEmailForgotPassword(Request $request) 
    {

        //print_r($_REQUEST);
        $user = User::where("email", $request->get("email"))->first();
        if ($user != null) {
            $message = '
            Hi '.ucfirst($user->name).', 
        <br><br>
            We received a password reset request for your account.  Please <a href="'.url("/").'/change-password?user_id='.$user->id.'">click here</a> to confirm your email address and reset your password.
            <br><br>
            -Recruiter Intel
            ';

            $this->sendMail([$request->get("email")], $message, $request->get('name'));
            $request->session()->flash('registered_status', "We sent an email to ".$request->get("email")." with instructions to reset your password");
        } else $request->session()->flash('registered_status', "cannot find that account");
            return redirect("/");

    }

    public function new($location_id = 1) 
    {

        ///*
        $alerts = DB::table("rolesentry_jobs")
        ->select('*', 'date_format(timediff(current_timestamp,created_at),\'%k hours, %i minutes, %s seconds ago\') as time_ago',  'rolesentry_jobs.hiring_manager as hiring_manager')
            ->join("rolesentry_alerts", 'rolesentry_jobs.id','=','rolesentry_alerts.rolesentry_job_id')
        ->join("email_rolesentry_alerts", 'rolesentry_alerts.id','=','email_rolesentry_alerts.rolesentry_alert_id')
        ->join("emails", 'email_rolesentry_alerts.emails_id', '=', 'emails.id')
            ->join("rolesentry_companies", 'rolesentry_jobs.rolesentry_companies_id','=','rolesentry_companies.id')
            ->join("locations", 'locations.id','=','rolesentry_companies.location_id')
            ->select(DB::raw('rolesentry_alerts.id as sl, rolesentry_jobs.job_description_link as job_description_link, rolesentry_companies.name as company,rolesentry_alerts.title as title, rolesentry_alerts.created_at as created_at,locations.name as location, date_format(timediff(current_timestamp,rolesentry_alerts.created_at),\'%k hours, %i minutes ago\') AS time_ago, rolesentry_jobs.hiring_manager as hiring_manager, rolesentry_jobs.hiring_manager_linkedin as hiring_manager_linkedin'))
        ->distinct()
        ->whereNotNull('emails.published_at')
	->whereNotNull('rolesentry_jobs.hiring_manager')
        ->where('locations.id', '=', $location_id)
            ->orderBy('email_rolesentry_alerts.created_at', 'DESC')->offset(0)->take(9)->get();
        //*/
            $data['today'] = date('M j');
            
            $data['alerts'] = $alerts;
            $data['location'] = Location::find($location_id);
            $data['location_name'] = Location::find($location_id)->name;
       
 
        return view("welcome-new", $data);
    }

    public function iindex($location_id = 1)
    {
        // $alerts = Rolesentry_alert::orderBy('created_at', 'DESC')->get();
       // $location_id = 1;
       $data['locations'] = Location::whereIn('id',[1,3,5,14])->get();
         $alerts = DB::table("rolesentry_jobs")
	->select('*', 'date_format(timediff(current_timestamp,created_at),\'%k hours, %i minutes, %s seconds ago\') as time_ago',  'rolesentry_jobs.hiring_manager as hiring_manager')
        ->join("rolesentry_alerts", 'rolesentry_jobs.id','=','rolesentry_alerts.rolesentry_job_id')
	->join("email_rolesentry_alerts", 'rolesentry_alerts.id','=','email_rolesentry_alerts.rolesentry_alert_id')
    ->join("emails", 'email_rolesentry_alerts.emails_id', '=', 'emails.id')
    ->join("job_types", 'rolesentry_jobs.job_type_id', '=', 'job_types.id')
        ->join("rolesentry_companies", 'rolesentry_jobs.rolesentry_companies_id','=','rolesentry_companies.id')
        ->join("locations", 'locations.id','=','rolesentry_companies.location_id')
        ->select(DB::raw('rolesentry_alerts.id as sl, job_types.name as job_type, rolesentry_jobs.job_description_link as job_description_link, rolesentry_companies.name as company,rolesentry_alerts.title as title, rolesentry_alerts.created_at as created_at,locations.name as location, date_format(timediff(current_timestamp,rolesentry_alerts.created_at),\'%k hours, %i minutes ago\') AS time_ago, rolesentry_jobs.hiring_manager as hiring_manager, rolesentry_jobs.hiring_manager_linkedin as hiring_manager_linkedin'))
	->distinct()
	->whereNotNull('emails.published_at')
	->whereNotNull('rolesentry_jobs.hiring_manager')
	->where('locations.id', '=', $location_id)
        ->orderBy('email_rolesentry_alerts.created_at', 'DESC')->offset(0)->take(9)->get();

    $data['today'] = date('M j');
        

        $data['alerts'] = $alerts;
        $data['location'] = Location::find($location_id);
        $data['location_name'] = Location::find($location_id)->name;

        return view('welcome-new-latest', $data);
    }


    public function automatedEmailDemo($location_id = 1)
    {
        if($user = Auth::user())
        {
            // do what you need to do
        }

       // print $location_id."";
       $data["location_id"] = $location_id;
       //$data["job_type_id"] = $job_type_id;
        //die();
       $locationId = $this->getLocationIdFromIP();
       
       if (!empty($locationId)) {
           $location_id = $locationId;
           //autoselect
           $auto_locations = Location_autoselect::where("closest_location_id", $location_id)->get()->pluck('autoselect_location_id')->toArray();
          // print_r($auto_locations);
           $data["location_auto_selects"] = $auto_locations;
           $data["location_auto_selects"] = [1,5];
       }
       //print $locationId."-ok";  
        // $alerts = Rolesentry_alert::orderBy('created_at', 'DESC')->get();
       // $location_id = 1;
       $data['locations'] = Location::whereIn('id',[1,3,5,14])->get();
       $data['locations_all'] = Location::where("show_in_preferences", 1)->orderBy('priority')->get();
       $data['job_types'] = Job_type::all();
       
    $alerts = DB::table("openings")
	->select('*', 'date_format(timediff(current_timestamp,created_at),\'%k hours, %i minutes, %s seconds ago\') as time_ago',  'hiring_managers.name as hiring_manager_name', 'hiring_managers.hiring_manager_position as hiring_manager_position', 'hiring_managers.linkedin_url as hiring_manager_linkedin')
    ->join("hiring_managers", 'hiring_managers.id','=','openings.hiring_manager_id')
    ->join("email_openings", 'email_openings.opening_id','=','openings.id')
//    ->join("emails", 'email_openings.email_id', '=', 'emails.id')
    ->join("job_types", 'openings.job_type_id', '=', 'job_types.id')
        ->join("rolesentry_companies", 'openings.rolesentry_company_id','=','rolesentry_companies.id')
        ->join("locations", 'locations.id','=','openings.location_id')
        ->select(DB::raw('openings.id as sl, job_types.name as job_type, openings.job_description_url as job_description_link, rolesentry_companies.name as company,openings.title as title, openings.created_at as created_at,locations.name as location, date_format(timediff(current_timestamp,openings.created_at),\'%k hours, %i minutes ago\') AS time_ago, hiring_managers.name as hiring_manager_name, hiring_managers.title as hiring_manager_position, hiring_managers.linkedin_url as hiring_manager_linkedin'))
	->distinct()
//	->whereNotNull('emails.published_at')
	->where('openings.job_type_id','=', 1)
	->whereNotNull('hiring_managers.linkedin_url')
	->where('locations.id', '=', $location_id)
        ->orderBy('email_openings.id', 'DESC')->offset(0)->take(9)->get();


    $data['today'] = date('M j');

        $data['alerts'] = $alerts;
        $data['location'] = Location::find($location_id);
        $data['location_name'] = Location::find($location_id)->name;

        return view('automated-email-demo', $data);
    }



    public function index($location_id = 1)
    {
        if($user = Auth::user())
        {
            return redirect("/platform");	
            // do what you need to do
        }

       // print $location_id."";
       $data["location_id"] = $location_id;
       //$data["job_type_id"] = $job_type_id;
        //die();
       $locationId = $this->getLocationIdFromIP();
       
       if (!empty($locationId)) {
           $location_id = $locationId;
           //autoselect
           $auto_locations = Location_autoselect::where("closest_location_id", $location_id)->get()->pluck('autoselect_location_id')->toArray();
          // print_r($auto_locations);
           $data["location_auto_selects"] = $auto_locations;
           $data["location_auto_selects"] = [1,5];
       }
       //print $locationId."-ok";  
        // $alerts = Rolesentry_alert::orderBy('created_at', 'DESC')->get();
       // $location_id = 1;
       $data['locations'] = Location::whereIn('id',[1,3,5,14])->get();
       $data['locations_all'] = Location::where("show_in_preferences", 1)->orderBy('priority')->get();
       $data['job_types'] = Job_type::all();
       
    $alerts = DB::table("openings")
	->select('*', 'date_format(timediff(current_timestamp,created_at),\'%k hours, %i minutes, %s seconds ago\') as time_ago',  'hiring_managers.name as hiring_manager_name', 'hiring_managers.hiring_manager_position as hiring_manager_position', 'hiring_managers.linkedin_url as hiring_manager_linkedin')
    ->join("hiring_managers", 'hiring_managers.id','=','openings.hiring_manager_id')
    ->join("email_openings", 'email_openings.opening_id','=','openings.id')
//    ->join("emails", 'email_openings.email_id', '=', 'emails.id')
    ->join("job_types", 'openings.job_type_id', '=', 'job_types.id')
        ->join("rolesentry_companies", 'openings.rolesentry_company_id','=','rolesentry_companies.id')
        ->join("locations", 'locations.id','=','openings.location_id')
        ->select(DB::raw('openings.id as sl, job_types.name as job_type, openings.job_description_url as job_description_link, rolesentry_companies.name as company,openings.title as title, openings.created_at as created_at,locations.name as location, date_format(timediff(current_timestamp,openings.created_at),\'%k hours, %i minutes ago\') AS time_ago, hiring_managers.name as hiring_manager_name, hiring_managers.title as hiring_manager_position, hiring_managers.linkedin_url as hiring_manager_linkedin'))
	->distinct()
//	->whereNotNull('emails.published_at')
	->where('openings.job_type_id','=', 1)
	->whereNotNull('hiring_managers.linkedin_url')
	->where('locations.id', '=', $location_id)
        ->orderBy('email_openings.id', 'DESC')->offset(0)->take(9)->get();


    $data['today'] = date('M j');

        $data['alerts'] = $alerts;
        $data['location'] = Location::find($location_id);
        $data['location_name'] = Location::find($location_id)->name;

        return view('welcome-new-latest', $data);
    }



    public function loadCarouselRequestedAlert()
    {

      
        $location_id = isset($_REQUEST["location_id"])?$_REQUEST["location_id"]:1; 
        $job_type_id = isset($_REQUEST["job_type_id"])?$_REQUEST["job_type_id"]:1;

         $alerts = DB::table("openings")
	->select('*', 'date_format(timediff(current_timestamp,created_at),\'%k hours, %i minutes, %s seconds ago\') as time_ago',  'hiring_managers.name as hiring_manager_name', 'hiring_managers.hiring_manager_position as hiring_manager_position', 'hiring_managers.linkedin_url as hiring_manager_linkedin')
	->join("hiring_managers","hiring_managers.id","=","openings.hiring_manager_id")
	->join("email_openings", 'email_openings.opening_id','=','openings.id')
//    ->join("emails", 'email_openings.email_id', '=', 'emails.id')
    ->join("job_types", 'openings.job_type_id', '=', 'job_types.id')
        ->join("rolesentry_companies", 'openings.rolesentry_company_id','=','rolesentry_companies.id')
        ->join("locations", 'locations.id','=','openings.location_id')
        ->select(DB::raw('openings.id as sl, job_types.name as job_type, openings.job_description_url as job_description_link, rolesentry_companies.name as company,openings.title as title,DATE_FORMAT(openings.created_at,"%m/%d/%Y") as created_at,locations.name as location, date_format(timediff(current_timestamp,openings.created_at),\'%k hours, %i minutes ago\') AS time_ago, hiring_managers.name as hiring_manager_name, hiring_managers.hiring_manager_position as hiring_manager_position, hiring_managers.linkedin_url as hiring_manager_linkedin'))
	->distinct()
//	->whereNotNull('emails.published_at')
	->where('openings.job_type_id','=', $job_type_id)
	->whereNotNull('hiring_managers.linkedin_url')
	->where('locations.id', '=', $location_id)
        ->orderBy('email_openings.id', 'DESC')->offset(0)->take(9)->get();

        $data['alerts'] = $alerts;

        return response()->json($data);
    }




    public function requestTrial(Request $request)    
    {        
           
       $data["success"] = false; 

       $user = User::where("email",'=',$request->get("email"))->first();
       
                   if ($user){
                           $request->session()->flash('registered_status', "It appears that an account already exists for ".$request->get("email").". Please reach out to info@recruiterintel.com for further assistance");
                       //return redirect("/");
                       $data["status"] = "It appears that an account already exists for ".$request->get("email").". Please reach out to info@recruiterintel.com for further assistance";
               $data["already_exists"] = true;
                           return response()->json($data);
                   }
                       
                           $data["user"] = User::create([
                               'name' => "",
                               'email' => $request->get("email"),
                               'password' => bcrypt('123456'),
                               'type' => 2,
                               'is_confirmed'=> 0 
                           ]);

                    User_preference::where('user_id',$data["user"]->id)->delete();

                    $locations_auto_signup = Location::where('auto_signup','=','1')->get();
                    $job_types_auto_signup = Job_type::where('auto_signup','=','1')->get();
                    foreach($locations_auto_signup as $location){
                        foreach($job_types_auto_signup as $job_type){
                                User_preference::create([
                                        'user_id' => User::max('id'),
                                        'location_id' => $location->id,
                                        'job_type_id' => $job_type->id
                                ]);
                        }
                    }
 
                $message = '
                Hi Admin, <br><br>
                Here is new user request with following information<br><br>
                Email:'.$request->get("email").' <br>
                <a href="'.url('/').'/lock-user/'.User::max('id').'">Lock this user</a> <br><br>
                <br><br>
        From Recruiter Intel Web Desk!';   
                $this->sendMail(["tanner@recruiterintel.com","nick@varzay.com"], $message, $request->get('name'));
                //Slack::to('#trial_form_submission')->send($message);
                
                $confirm_url = url("/").'/confirm?user_id='.User::max('id');
                           $message = '
                           Thanks for registering an account with Recruiter Intel!<br><br>Please <a href="'.$confirm_url.'">click here to confirm your email address</a> and complete your account setup.
                   <br><br>
                   Feel free to respond directly to this email with any questions about our platform or email alerts.<br><br>-Recruiter Intel';   
                $this->sendMail([$request->get("email")], $message, $request->get('email'));
                $data["success"] = true;                 
        //return redirect("/");
        return response()->json($data);
    }

    public function addSubscriberEmail(Request $request)    
    {       
       $data["success"] = false;
       $user = User::where("email", $request->get("trial_email"))->first();
       $closest_location = Location::where("name", session('closest_location'))->first(); 
       
       //print_r($user);
        if ($user != null) {
            $user->name = $request->get("name")." ". $request->get("last_name");
            $user->save();

	    if (!isset($_REQUEST['job_type'])){
			$_REQUEST['job_type'] = [1,2,4];
	    }


	    if (!isset($_REQUEST['location'])){
			$_REQUEST['location'] = [1,5];
	    }


            if (isset($_REQUEST['job_type']) && isset($_REQUEST['location'])) {
                User_preference::where('user_id', User::max('id'))->delete();

                $job_types_input = isset($_REQUEST["job_type"]) ? $_REQUEST["job_type"] : [];
                $locations_input = isset($_REQUEST["location"]) ? $_REQUEST["location"] : [];
                $subtypes_input = isset($_REQUEST["subtype"]) ? $_REQUEST["subtype"] : [];
                   
                   $locationsa = [];
                   $jobtypesa = [];
                   $subtypesa = [];

                    foreach ($locations_input as $location) {
                     
                        $loc = Location::find($location);
                        $locationsa[] = $loc->name;

	                foreach ($job_types_input as $job_type) {
                        
                        
                        
                        $jtype = Job_type::find($job_type);
                        $jobtypesa[] = $jtype->name;

                        User_preference::create([
                        'user_id' => User::max('id'),
                        'location_id' => $location,
                        'job_type_id' => $job_type
                        ]);
                    }

                        foreach($subtypes_input as $subtype) {
                            $jstype = Job_subtype::find($subtype);
                            $subtypesa[] = $jstype->name;
                            User_preferences_subtype::create([
                                'user_id' => User::max('id'),
                                'location_id' => $location,
                                'job_subtype_id' => $subtype
                                ]);
                        }    
                    } 

                    $rec = new Home_page_form_submission([
                        //name, angellist_url, career_page_url, xpath  
                                    "email" => $request->get("trial_email"),
                                    "ip_address" => $_SERVER['REMOTE_ADDR'],                           
                        "name" => $request->get("name"),
                        "last_name" => $request->get("last_name"),	
                        "phone" => $request->get("phone"),
                                    'created_at' => date("Y-m-d H:i:s",time()),
                                    'updated_at' => date("Y-m-d H:i:s",time()),
                        
                                ]);
                    $rec->save(); 
          
                $message = '
                Hi Admin, <br><br>
                Here is new user request with following information<br><br>
                <a href="'.url('/').'/lock-user/'.$user->id.'">Lock this user</a> <br><br>
                Name:'.ucfirst($request->get("name"))." ".ucfirst($request->get("last_name")).' <br>
                Email:'.$request->get("trial_email").' <br>
                Phone: '.$request->get("phone").' <br>
                Locations: '.implode (", ", $locationsa).' <br>
                Job Types: '.implode (", ", array_unique($jobtypesa)).'  <br>
                Sub Types: '.implode (", ", array_unique($subtypesa)).'  <br>
                Closest Location: '.session('closest_location').' <br>

                <br><br>
        From Recruiter Intel Web Desk!';   
                $this->sendMail(["tanner@recruiterintel.com","nick@varzay.com"], $message, $request->get('name'));
                //$this->sendMail(["abbas@varzay.com"], $message, $request->get('name'));
                if(!empty($closest_location->slack_channel)) Slack::to('#'.trim($closest_location->slack_channel))->send($message);
                else Slack::to('#trial_form_submission')->send($message);
                //  
                //$request->session()->flash('registered_status', "Successfully Registered! Please check your email at ".$request->get("email")." to confirm your free account.");
                //$data["status"] = "Successfully Registered! Please check your email at ".$request->get("email")." to confirm your free account and go ahead with step 2";
               // $data["status"] = "Successfully completed Step1 and pls go ahead with step2";
                $data["success"] = true;
                $request->session()->flash('registered_status', "Successfully Registered! Please confirm your email at ".$request->get("trial_email")." to activate your account");
				
		$data["follow_url"] = '/confirm?user_id='.User::max('id') . '&new=1';
                return response()->json($data);
            } //isset
        }  //if user exists
	else{
         
       $name = "";         
       if(isset($_REQUEST["name"])) 
            $name =  ucfirst($request->get("name"))." ".ucfirst($request->get("last_name"));
        
                    $data["user"] = User::create([
                               'name' => $name,
                               'email' => $request->get("trial_email"),
                               'password' => bcrypt('123456'),
                               'type' => 2,
                               'is_confirmed'=> 0 
                    ]);




	                User_preference::where('user_id', User::max('id'))->delete();

                $job_types_input = isset($_REQUEST["job_type"]) ? $_REQUEST["job_type"] : [];
                $locations_input = isset($_REQUEST["location"]) ? $_REQUEST["location"] : [];
                $subtypes_input = isset($_REQUEST["subtype"]) ? $_REQUEST["subtype"] : [];

                   $locationsa = [];
                   $jobtypesa = [];
                   $subtypesa = [];

                    foreach ($locations_input as $location) {

                        $loc = Location::find($location);
                        $locationsa[] = $loc->name;

                        foreach ($job_types_input as $job_type) {



                        $jtype = Job_type::find($job_type);
                        $jobtypesa[] = $jtype->name;

                        User_preference::create([
                        'user_id' => User::max('id'),
                        'location_id' => $location,
                        'job_type_id' => $job_type
                        ]);
                    }

                        foreach($subtypes_input as $subtype) {
                            $jstype = Job_subtype::find($subtype);
                            $subtypesa[] = $jstype->name;
                            User_preferences_subtype::create([
                                'user_id' => User::max('id'),
                                'location_id' => $location,
                                'job_subtype_id' => $subtype
                                ]);
                        }
                    }

                    $rec = new Home_page_form_submission([
                        //name, angellist_url, career_page_url, xpath
                                    "email" => $request->get("trial_email"),
                                    "ip_address" => $_SERVER['REMOTE_ADDR'],
                        "name" => $request->get("name"),
                        "last_name" => $request->get("last_name"),
                        "phone" => $request->get("phone"),
                                    'created_at' => date("Y-m-d H:i:s",time()),
                                    'updated_at' => date("Y-m-d H:i:s",time()),

                                ]);
                    $rec->save();	







        $message = '
                Hi Admin, <br><br>
                Here is new user request with following information<br><br>
                <a href="'.url('/').'/lock-user/'.User::max('id').'">Lock this user</a> <br><br>
                Email:'.$request->get("trial_email").' <br>
                <br><br>
        From Recruiter Intel Web Desk!';   
                $this->sendMail(["tanner@recruiterintel.com","nick@varzay.com"], $message, $request->get('name'));
                //Slack::to('#trial_form_submission')->send($message);     
                if(!empty($closest_location->slack_channel)) Slack::to('#'.trim($closest_location->slack_channel))->send($message);
                else Slack::to('#trial_form_submission')->send($message);   

			   $confirm_url = url("/").'/confirm?user_id='.User::max('id');

                           $message = '
                           Thanks for registering an account with Recruiter Intel!<br><br>Please <a href="'.$confirm_url.'">click here to confirm your email address</a> and complete your account setup.
                   <br><br>
                   Feel free to respond directly to this email with any questions about our platform or email alerts.<br><br>-Recruiter Intel';   
                $this->sendMail([$request->get("trial_email")], $message, $request->get('trial_email'));
                $data["success"] = true;                 
                $request->session()->flash('registered_status', "Successfully Registered! Please confirm your email at ".$request->get("trial_email")." to activate your account");

		$data["follow_url"] = '/confirm?user_id='.User::max('id').'&new=1';

                return response()->json($data);
 
	}


        //return redirect("/");
    }

    public function confirmAccount(Request $request) 
    {
        $user = User::find($_REQUEST["user_id"]);
	
	if (isset($_REQUEST['new'])){
	
		$isFreshLead = $_REQUEST["new"];
		
		if ($isFreshLead){
			Auth::login($user);
			return redirect('/platform?new=1');
		}
	   
	}
 
        $data["valid_user"] = 0;
        if($user !== null)  { 
            $user->is_confirmed = 1;
            $user->save();
            $data["valid_user"] = $user->id;
        }    

        return redirect()->route('welcome')->with('registered_status', "Thank you for confirming your email! You will start to receive our daily email alerts to " . $user->email)->with("user_id", $user->id);
        return view("confirm-account", $data);
    }

    


    public function getAlertDetail() 
    {
        $alert = Rolesentry_alert::find($_REQUEST["id"]);
        $data["valid_alert"] = 0;

        if($alert !== null)  { 
            $data["alert"] = $alert;    
        }    

        return view("alert-id", $data);
    }

    public function getLocationAlert($location) 
    {
        $location = DB::TABLE("locations")->where("name",ucwords(str_replace("-"," ",$location)))->first();        
        if($location === null) $location_id = 1;
        else $location_id = $location->id;

        return $this->index($location_id);

    }

     public function contactUs(Request $request) {

        $message = '
        Hi Admin, <br><br>
        Here is new contact with following information<br><br>
        Name:'.ucfirst($request->get("name")).' <br>
        Email:'.$request->get("email").' <br>
        Message: '.$request->get("describe").' <br>        

       <br><br>
From Recruiter Intel Web Desk!';   
$this->sendMail(["tanner@recruiterintel.com","nick@varzay.com"], $message, $request->get('name'));
        //  
       $request->session()->flash('registered_status', "Thanks for reaching out! We'll get back to you as soon as possible.");
        Contact::create([
            "name"=> $request->get("name"),
            "email"=> $request->get("email"),
            "message"=> $request->get("message"),
            'created_at' => date("Y-m-d H:i:s",time()),
            'updated_at' => date("Y-m-d H:i:s",time())
            ]);

       return redirect()->action('WelcomeController@index');
     }

    public function sendMail($emails, $message, $name, $type=1) 
    {
        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => $emails 
        ,
        'category' => 'Recruiter Intel Registration'
        );

        
        $subject = "Please confirm your email for your Recruiter Intel account";

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


    public function changePassword() 
    {
        $data["test"] = 1;
        
        if(isset($_REQUEST["user_id"])) {
            $user = User::find($_REQUEST["user_id"]);
            Auth::login($user);
        }

        return view("change-password", $data);

    }

    public function updateAccount(Request $request) 
    {
        $user = User::find(Auth::id());

        if(isset($_REQUEST["name"])) {
            $user->name = trim($_REQUEST["name"])." ".trim($_REQUEST["name2"]);
        }

        if(isset($_REQUEST["email"])) {
            $user->email = trim($_REQUEST["email"]);
        }

        if(isset($_REQUEST["company"])) {
            $user->company = trim($_REQUEST["company"]);
        }

        if(isset($_REQUEST["receive_newsletter"])) {
            $user->receive_newsletter = 1;
        } else $user->receive_newsletter = 0;


        if(isset($_REQUEST["new_pw"]) && !empty(trim($_REQUEST["new_pw"])) && ($request->get("new_pw") == $request->get("new_pw_retype"))) {        
           //print "ffffffff";
            $user->password = bcrypt(trim($_REQUEST["new_pw"]));
        }
        //print_r($_REQUEST);
        //die();

        $user->save();
        return redirect("/platform");
            //return redirect("/change-password");

    }


    public function updateUserAccount() 
    {
        //print "hello";
        //die();
        $user = User::find(Auth::id());
        $data["user"] = $user;

        
        if(isset($_REQUEST["submit"])) {

            $user->email = $_REQUEST["email"];
            $user->name = $_REQUEST["name"];
            $user->save();
            $user = User::find(Auth::id());
            $data["user"] = $user;
            return redirect("/platform");
    
        }
        if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "sendcode") {
        
           $data['code'] = time();
           $message = '
           Hi '.ucfirst($user->name).', <br><br>
           Pls confirm you own this email with this code mentioned here. <br> Code::<strong>'.$data['code'].' <br><br>        
           
           
          <br><br>
            From Recruiter Intel Web Desk!';   
            $this->sendMail([$user->email], $message, $user->email);
                return response()->json($data); 
        } 

        return view("/account-info", $data);

    }

    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }

   public function getLocationIdFromIP() {
       //.$this->getIp()."
       //71.183.115.3
        $json = file_get_contents("https://ipapi.co/".$this->getIp()."/json");
        $data = json_decode($json, TRUE);
        $lat1 = $data["latitude"];
        $lon1 = $data["longitude"];
        $locations = Location::all();
        $distance = 99999;
        $location_id = 0;
        $location = ""; 
        $distances = [];
        foreach($locations as $location) {

         if(!empty($location->latitude) && !empty($location->longitude))   
           $mile =   $this->distance($lat1, $lon1, $location->latitude, $location->longitude, "N");
           
           $mile = round($mile);
           $distances[$location->id] = $mile;

           if($mile < $distance ) {
               $distance = $mile;
               $location_id = $location->id;
               session(["closest_location" => $location->name]);
           }
        }

        //print_r($distances);
        //die();

        return $location_id;
   }
   
   function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    
      $theta = $lon1 - $lon2;
      $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
      $dist = acos($dist);
      $dist = rad2deg($dist);
      $miles = $dist * 60 * 1.1515;
      $unit = strtoupper($unit);
    
      if ($unit == "K") {
        return ($miles * 1.609344);
      } else if ($unit == "N") {
          return ($miles * 0.8684);
        } else {
            return $miles;
          }
    }

}
