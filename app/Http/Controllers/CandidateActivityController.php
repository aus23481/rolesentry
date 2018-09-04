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
use App\CandidateActivity;
use App\Resume;
use App\Prospect;
use App\Candidate_subtype;


use DB;
use Auth;

class CandidateActivityController extends Controller
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

    public function getCandidateActivities()
    {
        //
        $data["success"] = false;
        $data['candidateActivities'] = CandidateActivity::join('candidates', 'candidates.id', '=','candidate_activity.candidate_id')->with("candidate")->with("candidate.job_type")->with('candidate.location')->with('candidate.resume')->with('candidateActivityType')->get();
        $data["favoriteCandidates"] = DB::table("user_favorites")
        ->join("candidates", "candidates.id", "=", "user_favorites.favoriteable_item_id")
        ->leftJoin("resumes", "candidates.id",'=',"resumes.candidate_id")
        ->join("candidate_activity", "candidates.id", "=", "candidate_activity.candidate_id")
         ->select(DB::raw("candidate_activity.id as id"))
        ->where("user_favorites.user_id", Auth::id())
        ->where("user_favorites.table_id", 5)->get()->toArray();

        $data["success"] = true;

        return response()->json($data);
    }

    public function getCandidateActivity()
    {
        //
        $data["success"] = false;
        $ca = CandidateActivity::where("id", $_REQUEST["id"])->with('candidate')->with('candidate.resume')->get();
        $data['candidateActivity'] = $ca;
        //$data["subtypes"] = Candidate_subtype::where("candidate_id", $ca->candidate_id)->get();
        $data["subtypes"] = Candidate_subtype::where("candidate_id", $_REQUEST["id"])->get();
        $data["success"] = true;

        return response()->json($data);
    }
   
   
    public function addCandidateActivity(Request $request)
    {
        //
        $data["success"] = false;
        
        $prospect = Prospect::create([
            "type_id" => 2
            ]);

        $candidate = Candidate::create([
            "first_name"=> $_REQUEST["cda_first_name"],
            "last_name"=> $_REQUEST["cda_last_name"],
            "job_type_id"=> $_REQUEST["cda_job_type_id"],
            "location_id"=> $_REQUEST["cda_location_id"],
            "email"=> $_REQUEST["cda_email"],
            "linkedin_url"=> $_REQUEST["cda_linkedin_url"],
            "prospect_id" => $prospect->id
        ]);
        $data["prospect"] = $prospect;         
	$candidateActivity = CandidateActivity::create([
	    "candidate_id"=> $candidate->id,
	    "candidate_activity_type_id"=> $_REQUEST["candidate_activity_type_id"]
    ]);
    
    $sub_types = $_REQUEST["cda_job_subtype_id"];
    
    foreach($sub_types as $sub_type ){
        Candidate_subtype::create([
           "candidate_id" => $_REQUEST["candidate_id"],
           "job_subtype_id" => $sub_type
        ]);
    }

	$candidateActivity = $candidateActivity::find($candidateActivity->id)->with('candidate');

        $data["candidateActivity"] = $candidateActivity;
        $data["candidate"] = $candidate;
        $data["success"] = true;

        return response()->json($data);
    }

    function get_extension($file) {
        $extension = end(explode(".", $file));
        return $extension ? $extension : false;
    }

    public function uploadFile(Request $request) 
    {
        $data["success"] = false;
        if(isset($_FILES['file']['name'])) {
            
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

    public function editCandidateActivity(Request $request)
    {
        //
        $data["success"] = false;
        $candidate_activity = CandidateActivity::find($_REQUEST["candidate_activity_id"]);
        if(empty($_REQUEST["candidate_activity_type_id"])) $_REQUEST["candidate_activity_type_id"] = 1;
        $candidate_activity->candidate_activity_type_id = $_REQUEST["candidate_activity_type_id"];
        $candidate_activity->save();

        $candidate = Candidate::find($candidate_activity->candidate_id);
        $candidate->first_name = $_REQUEST["cda_first_name"];
        $candidate->last_name = $_REQUEST["cda_last_name"];
        $candidate->job_type_id = $_REQUEST["cda_job_type_id"];
        $candidate->location_id = $_REQUEST["cda_location_id"];
        $candidate->email = $_REQUEST["cda_email"];
        $candidate->linkedin_url = $_REQUEST["cda_linkedin_url"];
        $candidate->save();

        $sub_types = $_REQUEST["cda_job_subtype_id"];
        Candidate_subtype::find("candidate_id", $candidate->id)->delete();
        foreach($sub_types as $sub_type ){
            Candidate_subtype::create([
               "candidate_id" => $_REQUEST["candidate_id"],
               "job_subtype_id" => $sub_type
            ]);
        }

        

        $data["success"] = true;

        return response()->json($data);
    }

    public function deleteCandidateActivity()
    {
        //
        $data["success"] = false;
        $data['candidateActivity'] = CandidateActivity::find($_REQUEST["id"])->delete();
        
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
