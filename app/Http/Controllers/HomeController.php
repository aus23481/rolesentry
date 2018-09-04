<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DOMDocument;
use App\Rolesentry_company;
use App\Rolesentry_job;
use App\Rolesentry_alert;
use App\Ban_url;
use App\Ban_job_title;
use App\Ban_company_name;
use App\Job_type_word;
use App\Job_type;
use App\Job_subtype;
use App\Job_type_word_job_subtype;

use DB;
use App\Mail\AlertEmail;
use App\Location;
use Mail;
use Slack;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->common = new CommonController;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       // $alerts = Rolesentry_alert::orderBy('created_at', 'DESC')->get();
        $alerts = DB::table("rolesentry_jobs")
        ->join("rolesentry_alerts", 'rolesentry_jobs.id','=','rolesentry_alerts.rolesentry_job_id')
        ->join("rolesentry_companies", 'rolesentry_jobs.rolesentry_companies_id','=','rolesentry_companies.id')
        ->select('rolesentry_alerts.id as sl','rolesentry_companies.name as company','rolesentry_jobs.title as title','rolesentry_alerts.created_at as created_at')
        ->orderBy('created_at', 'DESC')->paginate(50, ['*'], 'alert');

        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;
        $data['alerts'] = $alerts;

	return redirect('/platform');
        return view('home', $data);
    }    

    public function showCompanyForm() 
    {
        $locations = Location::all();
        $data['locations'] = $locations;
        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;

        return view('add-company', $data);
    }

    public function getCompanyData(Request $request) {
        $data['name'] = $request->get("name");
        $data['career_page_url'] = $request->get("career_page_url");
        $data['xpath'] = $request->get("xpath");
        $data['location_id'] = $request->get("location_id");
        $locations = Location::all();
        $data['locations'] = $locations;

        $data1 = file_get_contents(config('app.scraperurl') . '?page=' . urlencode($request->get("career_page_url")) . '&selector=' . urlencode($request->get("xpath")));
	
	$data['node_server_error'] = false;

	if ($data1 == "Error getting page" || $data1 == "Error looking for selector"){
		$data['error'] = $data1;
	}

        $datas = json_decode($data1);
        $data["roles"] = $datas;
        //foreach($datas as $dt){//print $dt."<br>";}
        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;

        return view('add-company', $data);
        
    }

    public function saveCompanyData(Request $request) {
        
                $data['name'] = $request->get("name");
                $data['career_page_url'] = $request->get("career_page_url");
                $data['xpath'] = $request->get("xpath");
                $data['location_id'] = $request->get("location_id");
                $company_exists = Rolesentry_company::where('career_page_url', '=', addslashes($request->get("career_page_url")))->first();  
        
                if($company_exists === null) {
                    $rec = new Rolesentry_company([
                        //name, angellist_url, career_page_url, xpath  
                                    "name" => str_replace("https://angel.co/","",$request->get("name")),
                                    "angellist_url" => $request->get("name"),
                                    "career_page_url" => $request->get("career_page_url"),
                                    'xpath' => $request->get("xpath"),
                                    'first_scrape' => 1,
                                    'location_id' => $request->get("location_id"),
                                    'created_at' => date("Y-m-d H:i:s",time()),
                                    'updated_at' => date("Y-m-d H:i:s",time()),
                       		    'user_id' => Auth::user()->id 
                                ]);
                //unset($data); 
                $rec->save();  

                $data['company'] = Rolesentry_company::find(Rolesentry_company::max('id'));                
               
                               

            }

                /*$data1 = file_get_contents("http://18.216.123.223/xpath/?url=".$request->get("career_page_url")."&xpath=".$request->get("xpath"));
                $datas = json_decode($data1);
                $data["roles"] = $datas;
                foreach($datas as $dt){
                    //print $dt."<br>";
                }
                */
                $locations = Location::all();
                $data['locations'] = $locations;
                $companies = $this->common->getRightSidebarCompanies();
                $data['companies'] = $companies;
                $data['company_exists'] = $company_exists; 

                return view("/add-company", $data);
                
    }
        
    
    function extractByTag($html,$query)
    {
        if ($html){
            print $query;
            $dom = new \DOMDocument();
             @$dom->loadHTML($html);
            //$dom = new \DOMDocument();
            $xpath = new \DOMXpath($dom);
            $links = $xpath->query( $query );
            return $links;
        }
        else{
            return false;
        }
    }    

    function getNewAlerts() 
    {

        $alerts = Rolesentry_alert::orderBy('created_at', 'DESC')->get();
        $alerts = DB::table("rolesentry_jobs")
        ->join("rolesentry_alerts", 'rolesentry_jobs.id','=','rolesentry_alerts.rolesentry_job_id')
        ->select('rolesentry_companies.name as company','rolesentry_jobs.title as title','rolesentry_alerts.created_at as created_at')
        ->select('rolesentry_alerts.id','rolesentry_jobs.title as title','rolesentry_alerts.created_at as created_at')
        ->orderBy('created_at', 'DESC')->get();

                
        $data["alerts"] = $alerts;
        //print_r($alerts);
        return redirect("/");
    }
    
    public function getLogout() 
    {       
        Auth::logout();        
        return redirect("/")->with('registered_status', "You have been successfully logged out");
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

    public function test() 
    {
        ///print "Hello-";
        print  $this->getIp();
        $json = file_get_contents("https://ipapi.co/".$this->getIp()."/json");
        $data = json_decode($json, TRUE);
        //echo "<pre>";
        print_r($data);
        //echo "</pre>";
        //https://ipapi.co/8.8.8.8/json/


        //Slack::to('#trial_form_submission')->send('Test :New User Created!!!');

       /* $data['test'] = 'Testvar';
        //die();
        Mail::send('alert-mail', $data, function (Message $message) {
            $message
                ->to('aus234@yahoo.com', 'Abbas')
                ->from('info@rolesentry.com', 'RoleSentry')
                ->embedData([
                    'categories' => ['user_group1'],
                    'send_at' => $send_at->getTimestamp(),
                ], 'sendgrid/x-smtpapi');
        });
        */
        /*
        //$data = ['message' => 'This is a test!'];
        
        //Mail::to('aus234@yahoo.com')->send(new AlertEmail($data));
        $data['test'] = "test";
        Mail::send ( 'alert-mail', $data, function ($message) {
            
            $message->from ( 'donotreply@demo.com', 'Just Laravel' );
            
            $message->to ( 'aus234@yahoo.com' )->subject ( 'Just Laravel demo email using SendGrid' );
        } );
        */
        // the message

    }


    public function getBanText() 
    {
        $banurls = Ban_url::all();
        $banjobtitles = Ban_job_title::all();
        $bancompanynames = Ban_company_name::all();
        $data["banurls"] = $banurls;
        $data["banjobtitles"] = $banjobtitles;
        $data["bancompanynames"] = $bancompanynames;
        return view("/ban-text", $data);
    }

    public function addBanText() 
    {
       
       if(isset($_REQUEST["bantype"]) && $_REQUEST["bantype"] == "url") {
        $banurls = Ban_url::create([
            "term" => $_REQUEST["term"],
            "created_at" => date("Y-m-d H:i:s",time()),
            "updated_at" => date("Y-m-d H:i:s",time())  
            ]);
        }  
        
        if(isset($_REQUEST["bantype"]) && $_REQUEST["bantype"] == "job_title") {
            $banurls = Ban_job_title::create([
                "term" => $_REQUEST["term"],
                "created_at" => date("Y-m-d H:i:s",time()),
                "updated_at" => date("Y-m-d H:i:s",time())  
                ]);
        } 
        
        if(isset($_REQUEST["bantype"]) && $_REQUEST["bantype"] == "company_name") {
            $banurls = Ban_company_name::create([
                "term" => $_REQUEST["term"],
                "created_at" => date("Y-m-d H:i:s",time()),
                "updated_at" => date("Y-m-d H:i:s",time())  
                ]);
        } 
        
        return redirect("/bantext");
    }

    public function deleteBanText($id, $type) 
    {
       if($type == "url") 
        $banurls = Ban_url::find($id)->delete();

        if($type == "job_title") 
        $banurls = Ban_job_title::find($id)->delete();

        if($type == "company_name") 
        $banurls = Ban_company_name::find($id)->delete();
           
        
        return redirect("/bantext");
    }



    
    public function getJobTypeDefiner() 
    {
        $job_types = Job_type::all();       
        $words = Job_type_word::orderBy("job_type_id")->get();
        $job_type_word_job_subtypes = Job_type_word_job_subtype::all();
        $job_type_word_job_subtype = [];

        foreach($job_type_word_job_subtypes as $jtws) {
            $job_type_word_job_subtype[$jtws->job_type_word_id."_".$jtws->job_subtype_id] = 1;
        }

       // print_r( $job_type_word_job_subtype);
        //die();
        $data["job_type_word_job_subtypes"] = $job_type_word_job_subtype;
        $data["job_types"] = $job_types;
        $data["words"] = $words;

        return view("/jobtype-definer", $data);
    }

    public function addJobSubTypeDefiner() 
    {    
 
        $data["success"] = false;
        Job_type_word_job_subtype::create([
            "job_type_word_id" => $_REQUEST["word_id"],
            "job_subtype_id" => $_REQUEST["job_subtype_id"]            
        ]);

        $data["success"] = true; 

        return response()->json($data);
    }


    public function addJobTypeDefiner() 
    {
            
        if(isset($_REQUEST["word"])) 
        {            
               Job_type_word::create([
                   "word" => $_REQUEST["word"],
                   "job_type_id" => $_REQUEST["job_type_id"]            
               ]);
        } //isset word          
        
        return redirect("/jobtype-definer");
    }


    public function deleteJobTypeDefiner($id) 
    {
        
        $banurls = Job_type_word::find($id)->delete();
      
        
        return redirect("/jobtype-definer");
    }


}
