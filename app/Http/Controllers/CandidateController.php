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
use App\Resume;
use App\Prospect;
use App\Candidate_subtype;
use Excel;

use DB;
use Auth;

class CandidateController extends Controller
{
   
   
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
       // $this->common = new CommonController;
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

    public function getCandidates()
    {
        //
        $data["success"] = false;
        $data['candidates'] = Candidate::with("job_type")->with("location")->get();
        $data["success"] = true;

        return response()->json($data);
    }

    public function getFavoriteCandidates()
    {
        //
        $data["success"] = false;
        $favoritecandidates = DB::table("user_favorites")
                                        ->join("candidates", "candidates.id", "=", "user_favorites.favoriteable_item_id")
                                        ->leftJoin("resumes", "candidates.id",'=',"resumes.candidate_id")
                                        ->join("job_types", "job_types.id", "=", "candidates.job_type_id")
                                        ->join("locations", "locations.id", "=", "candidates.location_id")
                                         ->join("prospects", "prospects.id",'=',"candidates.prospect_id")
                                         
                                        ->select(DB::raw("resumes.*, prospects.reachable as reachable, candidates.id as id, candidates.prospect_id as prospect_id, candidates.first_name as first_name, candidates.last_name as last_name, job_types.name as job_type, locations.name as location"))                                        
                                        ->where("user_favorites.user_id", Auth::id())
                                        ->where("user_favorites.table_id", 5);

        $data["sql"] =  $favoritecandidates->toSql();
        $data["favoritecandidates"] = $favoritecandidates->get();
        $data["success"] = true;

        return response()->json($data);
    }

    public function getCandidate()
    {
        //
        $data["success"] = false;
        $data['candidate'] = Candidate::find($_REQUEST["id"]);
        $data["resumes"] = Resume::where("candidate_id", $_REQUEST["id"])->get();
        $data["subtypes"] = Candidate_subtype::where("candidate_id", $_REQUEST["id"])->get();
        $data["success"] = true;

        return response()->json($data);
    }
   
   
    public function addCandidate(Request $request)
    {
        //
        $data["success"] = false;
        
        $prospect = Prospect::create([
            "type_id" => 2
            ]);
        
            $candidate = Candidate::create([
            "first_name"=> $_REQUEST["first_name"],
            "last_name"=> $_REQUEST["last_name"],
            "job_type_id"=> $_REQUEST["cd_job_type_id"],
            "location_id"=> $_REQUEST["cd_location_id"],
            "email"=> $_REQUEST["cd_email"],
            "linkedin_url"=> $_REQUEST["cd_linkedin_url"],
            "prospect_id" => $prospect->id
        ]);
        $data["prospect"] = $prospect;        
        $data["candidate"] = $candidate;
        $data["success"] = true;

        return response()->json($data);
    }

    function get_extension($file) {
        $extension = end(explode(".", $file));
        return $extension ? $extension : false;
    }

    
    public function importCandidates(Request $request) {

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
                                    "type_id" => 2,
                                    "reachable"=>$start_automation?1:0
                                    ]);
                                
                                    $candidate = Candidate::create([
                                    "first_name"=> $row->first_name,
                                    "last_name"=> $row->last_name,
                                    "job_type_id"=> 1,
                                    "location_id"=> 1,
                                    "email"=> $row->email,
                                    "linkedin_url"=> "",
                                    "prospect_id" => $prospect->id
                                ]);

                                User_favorite::create([
                                    "favoriteable_item_id" => $candidate->id,
                                    "table_id" => 5,
                                    "user_id" => Auth::id()
                                ]);
                        
                            }
                        });
        
                    } //
        
        
            }

            
            return response()->json($data);
        
    }
    
    
    public function uploadFile(Request $request) 
    {
        $data["success"] = false;

        if(isset($_REQUEST["import_csv"])) {

            $sourcePath = $_FILES['file']['tmp_name']; 
            $targetPath = public_path('') ."/resumes/".$_FILES['file']['name']; 
            if( move_uploaded_file($sourcePath,$targetPath) ) { 
                $destinationPath = public_path('/resumes');
                $this->importCandidates($destinationPath."/".$_FILES['file']['name']);
            }


        }


        if(isset($_FILES['file']['name']) && !isset($_REQUEST["import_csv"])) {
            
                        $sourcePath = $_FILES['file']['tmp_name']; 
                              // Storing source path of the file in a variable
                     //$_FILES['file']['name']   
                    // $resume_id = DB::table('resumes')->max('id');
                     //$resume_id = $resume_id + 1;
                     $resume = Resume::create([
                        "candidate_id" => $_REQUEST["candidate_id"],
                        "extension" => substr(strrchr($_FILES['file']['name'],'.'),1),
                        'created_at' => date("Y-m-d H:i:s", time()),
                        'updated_at' => date("Y-m-d H:i:s", time())
                    ]);   


                     $targetPath = public_path('') ."/resumes/".$resume->id.".".substr(strrchr($_FILES['file']['name'],'.'),1); // Target path where file is to be stored
                       if( move_uploaded_file($sourcePath,$targetPath) ) {
                           //candidate_id
                           if(!empty($_REQUEST["candidate_id"])) {
                              
                              $data["resumes"] = Resume::where("candidate_id", $_REQUEST["candidate_id"])->get();
                                
                            }
                       }
                       // Moving Uploaded file                    
                    }
        $data["success"] = true;    
        
        return response()->json($data);
    }

    public function getResumes() {

        $data["success"] = false;
        $data["resumes"] = Resume::where("candidate_id", $_REQUEST["candidate_id"])->get();
        $data["success"] = true;
        return response()->json($data);    
    }

    public function editCandidate(Request $request)
    {
        //
        $data["success"] = false;
       
        $candidate = Candidate::find($_REQUEST["candidate_id"]);
        $candidate->first_name = $_REQUEST["first_name"];
        $candidate->last_name = $_REQUEST["last_name"];
        $candidate->job_type_id = $_REQUEST["cd_job_type_id"];
        $candidate->location_id = $_REQUEST["cd_location_id"];
        $candidate->email = $_REQUEST["cd_email"];
        $candidate->linkedin_url = $_REQUEST["cd_linkedin_url"];
       $candidate->save();   
       
        Candidate_subtype::where("candidate_id", $candidate->id)->delete();

        //$data["request"] = $_REQUEST;
       if(!isset($_REQUEST["cd_job_subtype_id"])) $_REQUEST["cd_job_subtype_id"] = [1];
       // /*
        $sub_types = [];
        $sub_types = $_REQUEST["cd_job_subtype_id"];
        
        $data["sub_types"] = $sub_types;
        $data['candidate_id'] = $_REQUEST["candidate_id"];
        if (!empty($sub_types)) {
            foreach ($sub_types as $sub_type) {
                Candidate_subtype::create([
               "candidate_id" => $_REQUEST["candidate_id"],
               "job_subtype_id" => $sub_type
            ]);
            
            }
        }
        //*/
        $data["success"] = true;

        return response()->json($data);
    }

    public function deleteCandidate()
    {
        //
        $data["success"] = false;
        $data['candidates'] = Candidate::find($_REQUEST["id"])->delete();
        $data["success"] = true;

        return response()->json($data);
    }

    public function deleteResume()
    {
        //
        $data["success"] = false;
        $resume = Resume::find($_REQUEST["id"]);
        $resume->delete();
        //file delete
        $targetFile = public_path('') ."/resumes/".$_REQUEST["id"].".".$resume->extension; 

        if (!unlink($targetFile))
          {
            $data["file"] = false;
          } else $data["file"] = true;
          
        $data["success"] = true;

        return response()->json($data);
    }

   
}
