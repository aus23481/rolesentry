<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Track_user_interaction;
use App\UserEmailInteractions;
use App\Rolesentry_alert;
use App\User;
use DB;

use Auth;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function trackUserAction() 
    {
        //$user_interaction_id
        $tracking_user_interaction =  Track_user_interaction::where("user_id", Auth::user()->id);
        
        $count = $tracking_user_interaction->count();
        $data["success"] = false;
        $data["count"] = $count;

        if(Auth::user()->is_locked) return response()->json($data);
        //$count = 101;
        if($count>100000 && empty(Auth::user()->is_paid))
            return response()->json($data);
        else {
            Track_user_interaction::create(
            [
            'user_id'=>Auth::user()->id,
            'interaction_id'=> $_REQUEST["user_interaction_id"],
            'created_at' => date("Y-m-d H:i:s", time())
            ]
        );
        $data["success"] = true; 
        }

        return response()->json($data);
    }

    public function trackAutomatedMarketing() {

        
        
        $user_email_interactions = UserEmailInteractions::with("user")->select("user_id")->groupBy("user_id")->take(5)->get();
        $users_uei = [];
        foreach($user_email_interactions as $user){
            $users_uei[$user->user_id] = $user->user->name;
        }
        
        $data["users_uei"] = $users_uei;

        //print_r($users_uei);

        $days = [];        
        for ($day = 0; $day <= 5; $day++) {
            $interval = $day*24;
            $sql = "SELECT COUNT(*) as count, user_id FROM user_email_interactions WHERE  DATE(created_at) = CURRENT_DATE() - INTERVAL ".$interval." HOUR GROUP BY user_id, DATE(created_at)";
            $actions = DB::select($sql);
            foreach ($actions as $action) {
                $days[$day][$action->user_id] = $action->count?$action->count:0;
            }
        }

        //platform action counts
        $tracking_user_interaction = Track_user_interaction::with("user")->select("user_id")->groupBy("user_id")->take(5)->get();
        
                $days_platform = [];        
                for ($day = 1; $day <= 5; $day++) {
                    $interval = $day*24;
                    $sql = "SELECT COUNT(*) as count, user_id FROM track_user_interactions WHERE  DATE(created_at) = CURRENT_DATE() - INTERVAL ".$interval." HOUR GROUP BY user_id, DATE(created_at)";
                    $actions = DB::select($sql);
                    foreach ($actions as $action) {
                        $days_platform[$day][$action->user_id] = $action->count?$action->count:0;
                    }
                }

        $user_not_in_platform_but_in_email_action = DB::select("SELECT COUNT(*) AS COUNT, user_id FROM user_email_interactions WHERE user_id NOT IN(SELECT user_id FROM track_user_interactions WHERE DATE(created_at) >= CURRENT_DATE() - INTERVAL 120 HOUR ) AND  DATE(created_at) >= CURRENT_DATE() - INTERVAL 120 HOUR  GROUP BY user_id HAVING COUNT(*) >= 5");
        
        $uei_count_total = [];
        $uei_counts = DB::select("SELECT user_id, COUNT(*) as count  FROM user_email_interactions  GROUP BY user_id");
        foreach($uei_counts as $count){
            $uei_count_total[$count->user_id] = $count->count;

        }

        $data["uei_count_total"] = $uei_count_total;

        $tui_count_total = [];
        $tui_counts = DB::select("SELECT user_id, COUNT(*) as count  FROM track_user_interactions  GROUP BY user_id ");
        foreach($tui_counts as $count){
            $tui_count_total[$count->user_id] = $count->count;
        }
        
        
        $data["tui_count_total"] = $tui_count_total;

        //print_r($user_not_in_platform_but_in_email_action);

        //die();
        $data["tracking_user_interaction"] = $tracking_user_interaction;
        $data["user_email_interactions"] = $user_email_interactions;
        $data["days"] = $days;
        $data["user_not_in_platform_but_in_email_action"] = $user_not_in_platform_but_in_email_action;
        $data["days_platform"] = $days_platform;


        return view("auto-marketing", $data);

    }

    public function sendMailToInteractionUser() {
        $user = User::find($_REQUEST["user_id"]);
        $data["success"] = false;

        $message = '
                    Dear '.ucfirst($user->name).'<br><br>    
                    Recruiter Intel reminds you about feedback on the usage of Recruiter Intel.
                    <br><br>
                    Thank you!<br>
                    -Recruiter Intel';   
             
                    $subject = "Recruiter Intel Usage Feedback";
                    $this->sendMail([$user->email], $message, $user->email, 1, $subject);
                    $data["success"] = true;

       return response()->json($data);             
    }

    public function sendMail($emails, $message, $name, $type=1, $subject=null) 
    {
        $url = 'https://api.sendgrid.com/';

        $json_string = array(

        'to' => $emails 
        ,
        'category' => 'Recruiter Intel Registration'
        );

        if ($subject==null){
        	$subject = $name . ", please confirm your email for your Recruiter Intel account";
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


}
