<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Prospect;
use App\ProspectingAction;
use App\Hiring_manager;
use App\Candidate;
use App\User;
use Auth;
use DB;
use App\Customization_variable;
use App\Job_type;
use App\Location;
use App\Job_subtype;



class ProspectController extends Controller
{
  
   public function changeProspectReachable() {
	   $data["success"] = false;
	   $prospect = Prospect::find($_REQUEST["prospect_id"]);
	   
	   if(empty($prospect->reachable)) $prospect->reachable = 1;
	   else $prospect->reachable = 0;

	   $prospect->save();
	   $data["prospect"] = Prospect::find($_REQUEST["prospect_id"]);
	   $data["success"] = true;
	   return response()->json($data);

   }
	
	public function getProspectingActions(Request $request){

	$prospecting_actions = ProspectingAction::select(['created_at as time','prospecting_action_types.name as event_name',DB::raw('(CASE WHEN prospecting_action_types.id = 3 THEN "Inbound" ELSE "Outbound" END) as direction'), 'subject','message'])
							->join('prospecting_action_types', 'prospecting_actions.prospecting_action_type_id', '=','prospecting_action_types.id')
							->where('prospect_id', $request->prospect_id)
							->orderBy('created_at', 'DESC');
	
	if($request->prospecting_action_type_id ==3) $prospecting_actions->where("prospecting_action_type_id", $request->prospecting_action_type_id);
    $prospecting_actions =  $prospecting_actions->get();
	$data['prospecting_actions'] = $prospecting_actions;
	$data['success'] = $prospecting_actions ? true : false;
	return $data;
   }
 
   public function getProspect(Request $request){

	$data = array();

	$data["customization_variables"] = Customization_variable::where('prospecting_type_id',1)->get();
	$prospect = Prospect::find($request->id);
	$data['prospect_id'] = $prospect->id;
	$data["prospect_type_id"] = $prospect->type_id;
	$data["id"] = 0;
	$data["job_type_id"] = 1;
	$data["location_id"] = 1;
	$data["prospecting_type_id"] = 1;
	
	if ($prospect){
		if ($prospect->type_id == 1) {	

			$hiring_manager_prospect = Hiring_manager::where('prospect_id', $prospect->id)->with("job_type")->with("location")->first();

			$first_space = strpos($hiring_manager_prospect->name, " ");
			$hiringManagerNameBeforeSpace = substr($hiring_manager_prospect->name, 0, $first_space);
			$data['first_name'] = $hiringManagerNameBeforeSpace;

			$first_space = strpos($hiring_manager_prospect->name, " ");
			$hiringManagerNameAfterFirstSpace = substr($hiring_manager_prospect->name,$first_space+1);
			$data['last_name'] = $hiringManagerNameAfterFirstSpace;

			$data['title'] = $hiring_manager_prospect->title;
			$data['linkedin_url'] = $hiring_manager_prospect->linkedin_url;
			$data['email'] = $hiring_manager_prospect->email;
			$data["id"] = $hiring_manager_prospect->id;
		

			$data["job_type"] = isset($hiring_manager_prospect->job_type->name) ? $hiring_manager_prospect->job_type->name : NULL;
			$data["location"] = isset($hiring_manager_prospect->location->name) ? $hiring_manager_prospect->location->name : NULL;

			$data["job_type_id"] = isset($hiring_manager_prospect->job_type->id) ? $hiring_manager_prospect->job_type->id : NULL;
			$data["location_id"] = isset($hiring_manager_prospect->location->id) ? $hiring_manager_prospect->location->id : NULL;

			

		} else if ($prospect->type_id == 2) {

			$candidate_prospect = Candidate::where('prospect_id', $prospect->id)->with("job_type")->with("location")->first();
			
			$data['first_name'] = $candidate_prospect->first_name;
			$data['last_name'] = $candidate_prospect->last_name;
			$data['email'] = $candidate_prospect->email;
			$data["id"] = $candidate_prospect->id;
			$data['linkedin_url'] = $candidate_prospect->linkedin_url;
			$data["job_type"] = $candidate_prospect->job_type->name;
			$data["location"] = $candidate_prospect->location->name;

			$data["job_type_id"] = $candidate_prospect->job_type->id;
			$data["location_id"] = $candidate_prospect->location->id;
		
		}
	}else{
		$data['error'] = "Prospect not found";
	}

        $data["user"] = User::find(Auth::id());
		$data['platform'] = "opening";
		$data['job_types_all'] = Job_type::all();       
		$data['locations_all_for_dropdown'] = Location::all();
		$data["job_subtypes"] = Job_subtype::where("job_type_id", 1)->get();
		$data['prospecting_action_type_id'] = @$_REQUEST["prospecting_action_type_id"];
		
	   
        return view("prospect", $data);
   }
   /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->common = new CommonController;
    }

} 
