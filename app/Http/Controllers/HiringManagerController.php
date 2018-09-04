<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_type;
use App\Job_subtype;
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
use App\Customization_variable;
use App\Hiring_manager_opening;
use App\Candidate;
use App\Prospect;
use App\ProspectingAction;
use Excel;


use DB;
use Auth;

class HiringManagerController extends Controller
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

    public function getHiringManagers()
    {
        //
        $data["success"] = false;
        //$data['hiringmanagers'] = Hiring_manager::join('prospects', 'prospects.id', '=','hiring_managers.prospect_id')->get();
        $hms = DB::table("hiring_managers")        
        ->leftJoin("rolesentry_companies", "hiring_managers.company_id", "=", "rolesentry_companies.id")
        ->join("job_types", "job_types.id", "=", "hiring_managers.job_type_id")
        ->join("locations", "locations.id", "=", "hiring_managers.location_id")    
        ->join("prospects", "prospects.id",'=',"hiring_managers.prospect_id")
       // ->leftJoin("prospecting_actions", "prospects.id",'=',"prospecting_actions.prospect_id")
         
        ->select(DB::raw("hiring_managers.id as id, hiring_managers.job_type_id as job_type_id, hiring_managers.location_id as location_id, rolesentry_companies.name as company, hiring_managers.prospect_id as prospect_id, hiring_managers.name as name,  job_types.name as job_type, locations.name as location, prospects.reachable as reachable"));

        //->where("user_favorites.user_id", Auth::id())
       // ->where("user_favorites.table_id", 2);

       if(!isset($_REQUEST["location"])) $_REQUEST["location"] = 1; 
      
       if(isset($_REQUEST["job_type"])) $job_type_ids = $_REQUEST["job_type"];
      
       if(isset($_REQUEST["location"])) $location_ids = $_REQUEST["location"];    

       if(!empty($job_type_ids)) $hms->whereIn('hiring_managers.job_type_id', $job_type_ids);
       if(!empty($location_ids)) $hms->whereIn('hiring_managers.location_id', $location_ids);
       $prospect_actions = ProspectingAction::select(DB::raw("prospect_id, count(*) as count"))->groupBy("prospect_id")->get();
       $actions = []; 
       $hmss = $hms->get();
       foreach($prospect_actions as $pa) {
         $actions[$pa->prospect_id] = $pa->count;
        }

        $data["sql"] =  $hms->toSql();
        $data["hiringmanagers"] = $hmss;
        $data["prospect"] = $actions;
        $data["success"] = true;

        return response()->json($data);
    }

    public function getHiringManager()
    {
        //
        $data["success"] = false;
        $data['hm'] = Hiring_manager::with("company")->where("id", $_REQUEST["id"])->get();
        $data["success"] = true;

        return response()->json($data);
    }
   
   
    public function addHiringManager()
    {
        //
        $data["success"] = false;

        $prospect = Prospect::create([
            "type_id" => 1
            ]);

        $company_id = 0;    
        if (isset($_REQUEST["company"])&& !empty($_REQUEST["company"])) {
            $company = Rolesentry_company::create([
                "name" => $_REQUEST["company"],
                "angellist_url" => "",
                "career_page_url" => 'legacy',
                'xpath' => "",
                'first_scrape' => 1,
                'location_id' => $_REQUEST["location_id"],
                'scrapeable' => false,
                'created_at' => date("Y-m-d H:i:s", time()),
                'updated_at' => date("Y-m-d H:i:s", time())
            ]);

           $company_id = $company->id;
        }       
        $hm = Hiring_manager::create([
		    "name" =>$_REQUEST["name"],
		    "intel" =>"",
		    "phone" =>$_REQUEST["phone"],
		    "email" =>$_REQUEST["email"],
		    "title" => $_REQUEST["title"],
		    "linkedin_url" => $_REQUEST["linkedin_url"],
            "prospect_id" => $prospect->id,
            "job_type_id" => $_REQUEST["job_type_id"],
            "location_id" => $_REQUEST["location_id"],
            "company_id" => $company_id
            ]);

        $data["hiringManager"] = $hm;
        $data["success"] = true;

        return response()->json($data);
    }

    public function editHiringManager()
    {
        //
        $data["success"] = false;

        $company_id = 0;    
        if (isset($_REQUEST["company"])&& !empty($_REQUEST["company"])) {
            $company = Rolesentry_company::create([
                "name" => $_REQUEST["company"],
                "angellist_url" => "",
                "career_page_url" => 'legacy',
                'xpath' => "",
                'first_scrape' => 1,
                'location_id' => $_REQUEST["location_id"],
                'scrapeable' => false,
                'created_at' => date("Y-m-d H:i:s", time()),
                'updated_at' => date("Y-m-d H:i:s", time())
            ]);

           $company_id = $company->id;
        }


        $hm = Hiring_manager::find($_REQUEST["hiring_manager_id"]);
        $hm->name = $_REQUEST["name"];
        $hm->phone = $_REQUEST["phone"];
        $hm->email = $_REQUEST["email"];
        $hm->title = $_REQUEST["title"];
        $hm->linkedin_url = $_REQUEST["linkedin_url"];
        $hm->job_type_id = $_REQUEST["job_type_id"];
        $hm->location_id = $_REQUEST["location_id"];
        $hm->company_id = $company_id;
        $hm->save();
        $data["success"] = true;
        $data["hiringManager"] = $hm;

        return response()->json($data);
    }

    public function deleteHiringManager()
    {
        //
        $data["success"] = false;
        $data['hiringManager'] = Hiring_manager::find($_REQUEST["id"])->delete();
        $data["success"] = true;

        return response()->json($data);
    }

    public function importHiringManagers(Request $request) {
        
                $data["success"] = false;
               // $data["file_name"] = $file;
               $data["request"] = $_REQUEST;
               $start_automation = ($_REQUEST["start_automation"])?1:0;
                
                        if(isset($_REQUEST["import_csv"])) {
                
                            $sourcePath = $_FILES['file']['tmp_name']; 
                            $targetPath = public_path('') ."/resumes/".$_FILES['file']['name']; 
                            if( move_uploaded_file($sourcePath,$targetPath) ) { 
        
                                $data["success"] = true;
                                Excel::load($targetPath, function($reader) use ($start_automation){
                                    foreach ($reader->all() as $row) {
                                         
        
                                         $prospect = Prospect::create([
                                            "type_id" => 1,
                                            "reachable"=>$start_automation?1:0
                                            ]);
                                        
                                            $hm = Hiring_manager::create([
                                            "name"=> $row->first_name." ".$row->last_name ,                                            
                                            "job_type_id"=> 1,
                                            "location_id"=> 1,
                                            "email"=> $row->email,
                                            "linkedin_url"=> "",
                                            "prospect_id" => $prospect->id
                                        ]);
        
                                        User_favorite::create([
                                            "favoriteable_item_id" => $hm->id,
                                            "table_id" => 2,
                                            "user_id" => Auth::id()
                                        ]);
                                
                                    }
                                });
                
                            } //
                
                
                    }
        
                    
                    return response()->json($data);
                
            }
            
}
