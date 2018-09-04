<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\RobotHelper;
use App\Robot_company;	
use App\Robot_log;
use App\Rolesentry_company;
use App\Robot_company_progression_status;
use App\Positions_found_with_key_selector;
use App\Rapid_approve_log;
use App\Job_board_alert;

use App\Approval_status;
use App\Location;
use DB;
use Auth;




class RobotController extends Controller
{
    
    
    public function __construct()
    {
        $this->middleware('auth');
        $this->common = new CommonController;
    }

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function addRobotCompanyByName()
    {
        //
        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;
        //print  "Test";
        return view("add-robot-company-by-name", $data);
    }

    public function addRobotCompanyByWebsite()
    {
        //
        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;
        //print  "Test";
        return view("add-robot-company-by-website", $data);
    }

    
    
    
    public function addRobotCompanyByCareerPage()
    {
        //
        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;
        //print  "Test";
        return view("add-robot-company-by-career-page", $data);
    }



    public function saveRobotCompanyByName(Request $request)
    {

        $company_name = $request->get("name");
	    $source_id = 2;

        $rh = new RobotHelper;
        $newly_added_robot_company_id = $rh->saveCompany($company_name, $source_id);
        if ($newly_added_robot_company_id){
			
            $career_page_url = $rh->addCareerPageToRobotCompany($newly_added_robot_company_id);
            if ($career_page_url){ 
			$ks = $rh->findKeySelector($career_page_url);
			if ($ks){
				$RobotCompany = Robot_company::find($newly_added_robot_company_id);
				$RobotCompany->key_selector = $ks;
				$RobotCompany->save();
//				echo 'got ks';
			}else{
//				echo 'no ks';
			}
//			die();
		    }
        }
 	if ($newly_added_robot_company_id){
        	return redirect("/robot-company?id=" . $newly_added_robot_company_id); 
	}
	else{
		return redirect("/add-robot-company-by-name");
	}
    }

    public function saveRobotCompanyByWebsite(Request $request)
    {

        $company_name = $request->get("name");
        $website = $request->get("website");
	    $source_id = 2;

        $rh = new RobotHelper;
        $newly_added_robot_company_id = $rh->addCompanyByWebsite($company_name, $source_id, $website);
        return redirect("/home"); 
    }


    public function saveRobotCompanyByCareerPage(Request $request)
    {

        $company_name = $request->get("name");
        $website = $request->get("website");
        $career_page = $request->get("career_page");

	    $source_id = 2;

        $rh = new RobotHelper;
        $newly_added_robot_company_id = $rh->addCompanyByCareerPage($company_name, $source_id, $website, $career_page);
        return redirect("/home"); 
    }



    public function getRobotCompanies()     
    {
       $type =  $_REQUEST["type"];
       $type_name = "";
       if ($type == 1) {
        $robot_companies = Robot_company::where("website_status_id", 2)->get();
        $data["type_name"] = "without website";

       }

       if ($type == 2) {
        $robot_companies = Robot_company::where("career_page_status_id", 2)->get();
        $data["type_name"] = "without career page";

       }

       if ($type == 3) {
        $robot_companies = Robot_company::all();
        $data["type_name"] = "All";

       }

       if ($type == 4) {
        $robot_companies = Robot_company::all();
        $data["type_name"] = "All";

       }


       $companies = $this->common->getRightSidebarCompanies();
       $data["companies"] = $companies;

       $data["robot_companies"] = $robot_companies;
       return view("robot-company", $data);
    }


    public function getRobotCompanyProgressionStatus()     
    {
        $id =  isset($_REQUEST["id"])?$_REQUEST["id"]:0;
        
        $robot_companies = Robot_company::where("robot_company_progression_status_id", $id)->get();
        $data["robot_companies"] = $robot_companies;
       $companies = $this->common->getRightSidebarCompanies();
       $data["companies"] = $companies;
       $data["robot_company_progression_status"] = Robot_company_progression_status::all();

       return view("robot-company-progression-status", $data);
    }

    public function getRobotCompanyDetail() 
    
    {

        $company = Robot_company::find($_REQUEST["id"]);

        $companies = $this->common->getRightSidebarCompanies();
        
        $data["companies"] = $companies;
 
        
        $data["company"] = $company;
        $logs = Robot_log::where("robot_company_id", $company->id)->get();
        $positions = Positions_found_with_key_selector::where("robot_company_id", $company->id)->get();
        //print_r($logs);
        $data["logs"] = $logs;
        
        $data["positions"] = $positions;
        return view("robot-company-detail", $data);

    }

    public function setRapidApproveMode(Request $request,$modeId){

	$request->session()->put('mode', $modeId);
	return redirect('robot-company-approval');

    }

    public function getRobotCompanyApproval(Request $request) 
    
    {
        //print  session('approval_mode')."-session";
        //for normal mode
	
	if ($request->session()->has('mode')) {
		$mode = $request->session()->get('mode'); 	    
	}
	else{
		$mode = false;
	}

        if(!$mode || $mode == 2){
        	$company = Robot_company::has("positions", ">", 1)->where("approval_status_id", 0)->where("career_page","!=", "")->first();
	}
        else {
		$company = Robot_company::where("approval_status_id", $mode)->first();
        }


        $companies = $this->common->getRightSidebarCompanies();
        
        $data["companies"] = $companies;
        $data["approval_statuses"] = Approval_status::all();
        $data["locations"] = Location::all();
        
	$data["manager"] = Auth::user()->id == 2 || Auth::user()->id == 6;

        if ($company){
		$data["notempty"] = true;
		$data["company"] = $company;
		$positions = Positions_found_with_key_selector::where("robot_company_id", $company->id)->get();
		//print_r($logs);
		
		$data["positions"] = $positions;
	}
	else{
		$data["notempty"] = false;
	}
        return view("robot-company-approval", $data);

    }

    public function updateRobotCompanyApprovalStatus(Request $request) 
    {

        //print_r($_REQUEST);
        $data["success"] = 0;
        $rh = new RobotHelper;
        if(isset($_REQUEST["id"]) && isset($_REQUEST["actionid"]))
        {
            $actionid = $_REQUEST["actionid"];
            $id = $_REQUEST["id"];
            $robot_company = Robot_company::find($id);
            if($actionid == 2){
            //	$rh->convertRobotCompanyToRoleSentryCompany($id);
	    }
            $robot_company->approval_status_id = $actionid;
            $robot_company->approver_user_id = Auth::id();
            $robot_company->save();
            $data["success"] = 1;
           
            //session(['approval_mode' => $_REQUEST["actionid"]]);


        }

        return response()->json($data);

    }


    public function editRobotCompany() 
    
    {

         $data["success"] = 0;
         $company = Robot_company::find($_REQUEST["id"]);

         if (isset($_REQUEST["website"])) {
            $company->website = $_REQUEST["website"];
           
         }

         if (isset($_REQUEST["career_page"])) {
            $company->career_page = $_REQUEST["career_page"];
           
        }

         
         if (isset($_REQUEST["key_selector"])) {
            $company->key_selector = $_REQUEST["key_selector"];
           
        } 

          if($company->save()) $data["success"] = 1;

          
          return response()->json($data);

      //print_r($_REQUEST);  

    }

    public function editRobotCompanyForApproval() 
    
    {

         $data["success"] = 0;
         $rh = new RobotHelper;
         $company = Robot_company::find($_REQUEST["id"]);


	 //User changing website

         if (isset($_REQUEST["website"])&&$_REQUEST["action"]=="website") {
            $company->website = $_REQUEST["website"];
            $company->save();
            $this->saveRapidApproveLog("website");

            $career_page_url = $rh->addCareerPageToRobotCompany($company->id);
            //add ks
            if ($career_page_url){ 
                $ks = $rh->findKeySelector($career_page_url);
                if ($ks){
                    $RobotCompany = Robot_company::find($company->id);
                    $RobotCompany->key_selector = $ks;
                    $RobotCompany->save();
                    $rh->updateRobotCompanyProgressionStatus($company->id, 4);
                }
            }


         } //end of webiste

         if (isset($_REQUEST["career_page"])&&$_REQUEST["action"]=="career_page") {
            $company->career_page = $_REQUEST["career_page"];
            $company->save();

            $this->saveRapidApproveLog("career_page");

            $ks = $rh->findKeySelector($_REQUEST["career_page"]);
            if ($ks){
                $RobotCompany = Robot_company::find($company->id);
                $RobotCompany->key_selector = $ks;
                $RobotCompany->save();
                $rh->updateRobotCompanyProgressionStatus($company->id, 4);
            }

        } //career_page

         
         if (isset($_REQUEST["key_selector"])&&$_REQUEST["action"]=="key_selector") {
            $company->key_selector = $_REQUEST["key_selector"];
            $company->save();

            $this->saveRapidApproveLog("key_selector");

            $ks = $rh->findKeySelector($_REQUEST["career_page"]);
        }

          
         if (isset($_REQUEST["location_id"])&&$_REQUEST["action"]=="location_id") {
            $company->location_id = $_REQUEST["location_id"];
            $company->save();
            $this->saveRapidApproveLog("location_id");
        }
 

          if($company->save()) $data["success"] = 1;

          
          return response()->json($data);

      //print_r($_REQUEST);  

    }

    public function saveRapidApproveLog($field) 
    {
        Rapid_approve_log::create([
            "field_changed" => $field,
            "old_value" => $_REQUEST[$field."_old"],
            "new_value" => $_REQUEST[$field],
            "user_id" => Auth::id(),
            "time_changed" => date("Y-m-d H:i:s", time())
        ]);
    }

    public function findAutoInput()
    {
        
        $company = Robot_company::find($_REQUEST["id"]);
        $rh = new RobotHelper;
        $data["success"] = 0;
        $data["website"] = "";

        if (isset($_REQUEST["auto_input_name"])) {
            //website
            if ($_REQUEST["auto_input_name"] == "website") {
                $website = $rh->nameToDomain($company->company_name);
                if($website) {
                        $data["success"] = 1;
                        $data["website"] = $website;
                }
            }
            //career_page
            if ($_REQUEST["auto_input_name"] == "career_page") {
                $career_page = $rh->addCareerPageToRobotCompany($_REQUEST['id']);
                if($career_page) {
                        $data["success"] = 1;
                        $data["career_page"] = $career_page;
                }
            }
            //career_page
            if ($_REQUEST["auto_input_name"] == "key_selector") {
                $key_selector = $rh->findKeySelector($company->career_page);
                if($key_selector) {
                        $data["success"] = 1;
                        $data["key_selector"] = $key_selector;
                }
            } 
        }

        return response()->json($data);

    }

    public function addLocation() 
    {
        
       // $companies = $this->common->getRightSidebarCompanies();
        //$data["companies"] = $companies;

        if(isset($_REQUEST["name"]))
            Location::create(["name" => $_REQUEST["name"]]);

        $data["locations"] = Location::all();

       return redirect("/locations") ;    
    }


    public function getLocations() 
    {
        
        $data["locations"] = Location::all();

        return view("locations", $data) ;    
    }

    public function getLocationDetail() 
    {
        
        $data["location"] = Location::find($_REQUEST["id"]);
        $data["alerts"] = Job_board_alert::where("location_id", $_REQUEST["id"])->take(10)->orderBy("id", "DESC")->get();



        return view("location-detail", $data) ;    
    }

    public function runManualScrapeLocation() 
    {
        
        $data["success"] = false;
        \Artisan::call('callscraper:jobboard',[ '--location' => $_REQUEST["location_id"] ]); 

        $data["success"] = true;



        return response()->json($data);
    }

    public function saveLocationDetail() 
    {
        
        $location = Location::find($_REQUEST["id"]);

        $location->name = $_REQUEST["name"];
        $location->careerjet_location_name = $_REQUEST["careerjet_location_name"];
        $location->dice_location_name = $_REQUEST["dice_location_name"];
        $location->nytimes_location_name = $_REQUEST["nytimes_location_name"];
        $location->indeed_location_name = $_REQUEST["indeed_location_name"];
        $location->careerbuilder_location_name = $_REQUEST["careerbuilder_location_name"];
        $location->simplyhired_location_name = $_REQUEST["simplyhired_location_name"];
        $location->monster_location_name = $_REQUEST["monster_location_name"];
        $location->stackoverflow_location_name = $_REQUEST["stackoverflow_location_name"];
        $location->hiring_manager_report_time = $_REQUEST["hiring_manager_report_time"]?$_REQUEST["hiring_manager_report_time"]:0;
        $location->show_in_preferences = $_REQUEST["show_in_preferences"]?$_REQUEST["show_in_preferences"]:0;
        $location->priority = $_REQUEST["priority"]?$_REQUEST["priority"]:0;
        $location->latitude = $_REQUEST["latitude"];
        $location->longitude = $_REQUEST["longitude"];

        $location->save();   

        return redirect("/locations") ;    
    }

    public function test1() 
    {
        //$rh = new RobotHelper;
        /*$url = "https://integralads.com/careers/";
        $html = file_get_contents($url);

        $link_text = ["w Opening", "View All Openings", "View Jobs", "Openings", "Jobs", "Careers"];
        $link = "";
        $text_string = "";

        foreach($link_text as $text) {
            
            $text_string = $text;
            $link = $rh->getLinkFromText($html, $text);
            if (!empty($link)) break;

        }
       
        print $text_string."<br>";
        print $link;
        */
        //$rh->QACareerPage(625);
       // $rh->convertRobotCompanyToRoleSentryCompany(625);
        //print env('CLOUDAMQP_PORT');
        //dd(config('app.env'));
       // print $_ENV["CLOUDAMQP_HOST"];
       print config("app.cloudampq_host");
       print config("app.cloudampq_user");
       print config("app.cloudampq_pw");
       print config("app.cloudampq_port");


    }

}
