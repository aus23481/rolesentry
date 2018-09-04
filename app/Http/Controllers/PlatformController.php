<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job_type;
use App\SchemeStep;
use App\SchemeStepLockType;
use App\SchemeStepLockTypeDetail;
use App\Scheme;
use App\Job_subtype;
use App\Location;
use App\Search_term;
use App\Approval;
use App\Rolesentry_company;
use App\opening;
use App\ProspectSavedSearchProgress;
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
use App\Prospect;
use App\Candidate;

use DB;
use Auth;

class PlatformController extends Controller
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
    public function index()
    {


	if (!Auth::user()) {
		return redirect("/");
	}

        //
        $job_types = Job_type::all();
        $data["customization_variables"] = Customization_variable::where('prospecting_type_id',1)->get();
        $data['search_terms'] = Search_term::all();
        $data['companies'] = Rolesentry_company::all();
        $data["job_types"] = $job_types;
        $data["user"] = User::find(Auth::id());
        $data["job_subtypes"] = Job_subtype::where("job_type_id", 1)->get();

	if (isset($_REQUEST['new'])){
		$alert = "Please confirm your email at " . Auth::user()->email . " to enjoy all features of Recruiter Intel";
		$conversion_pixel = "<img height=\"1\" width=\"1\" style=\"display:none;\" alt=\"\" src=\"https://dc.ads.linkedin.com/collect/?pid=242484&conversionId=375876&fmt=gif\" />"; 
		$data["alert"] = $alert;
		$data["conversion_pixel"] = $conversion_pixel;
	}


	if (Auth::user()->type == 3){
        	$data['locations'] = Location::whereIn('id',[22,40,32,39,37,31,34,15,16, 42,43,44])->get();
	}
	else{
        	$data['locations'] = Location::whereIn('id',[22,5,1,31,36,33,38,30,34,37,39,40])->get();
	}

        $data['locations_all'] = Location::where('show_in_preferences', 1)->paginate(12, ['*'], 'location');
	$data['locations_all_for_dropdown'] = Location::all();
        $data['job_types_all'] = Job_type::all();
        //saved search
        $data["saved_search_terms"] = SavedSearch::where('user_id', Auth::user()->id)->orderBy("id", "DESC")->take(2)->get();
        $user_favorites = User_favorite::where("table_id", 3)->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
        $favorites = [];

        foreach($user_favorites as $user_favorite) {
           $favorites[$user_favorite] = $user_favorite;
        }
        $data["favorites"] =  $favorites;

        //saved search favorites

        $job_type = [];
        $location_id = [];
        if(isset($_REQUEST["job_type"])) $job_type = $_REQUEST["job_type"];
        
        if(isset($_REQUEST["location"])) $location_id = $_REQUEST["location"];

        if(isset($_REQUEST["search"])) $search = $_REQUEST["search"];
        else $search = "";
        
        //$opening_location_counts =  DB::select("SELECT COUNT(*) AS counts, location_id FROM `openings` INNER JOIN `locations` ON `locations`.`id` = `location_id` WHERE `openings`.`created_at` > NOW() - INTERVAL 24 HOUR GROUP BY `location_id`");
       
       $data["location_counts"] = [];  
       $data["alerts"] = [];
       $data["invoice_type"] = 1;
       $data['stripe_publishable_secret'] = env('STRIPE_PUBLISHABLE_SECRET');
       $data['platform'] = "opening";
//$alerts->paginate(50, ['*'], 'alert'); 
//	foreach($data['alerts'] as $alert){
//		$alert->time_ago = str_replace('0 hours, ', '',$alert->time_ago);
//		$alert->time_ago = str_replace('1 hours, ', '1 hour, ',$alert->time_ago);
		
//	}

	$data['prospecting_type_id'] = 1;

        return view("platform", $data);
    }

    //HiringManager Platform

    public function indexHiringManager()
    {

	if (!Auth::user()) {
		return redirect("/");
	}
        //
        $job_types = Job_type::all();
        $data["customization_variables"] = Customization_variable::where('prospecting_type_id', 8)->get();
        $data['search_terms'] = Search_term::all();
        $data['companies'] = Rolesentry_company::all();
        $data["job_types"] = $job_types;
        $data["user"] = User::find(Auth::id());
        $data["job_subtypes"] = Job_subtype::where("job_type_id", 1)->get();

	if (isset($_REQUEST['new'])){
		$alert = "Please confirm your email at " . Auth::user()->email . " to enjoy all features of Recruiter Intel";
		$conversion_pixel = "<img height=\"1\" width=\"1\" style=\"display:none;\" alt=\"\" src=\"https://dc.ads.linkedin.com/collect/?pid=242484&conversionId=375876&fmt=gif\" />"; 
		$data["alert"] = $alert;
		$data["conversion_pixel"] = $conversion_pixel;
	}


	if (Auth::user()->type == 3){
        	$data['locations'] = Location::whereIn('id',[22,40,32,39,37,31,34,15,16, 42,43,44])->get();
	}
	else{
        	$data['locations'] = Location::whereIn('id',[22,5,1,31,36,33,38,30,34,37,39,40])->get();
	}

        $data['locations_all'] = Location::where('show_in_preferences', 1)->paginate(12, ['*'], 'location');
	$data['locations_all_for_dropdown'] = Location::all();
        $data['job_types_all'] = Job_type::all();
        //saved search
        $data["saved_search_terms"] = SavedSearch::where('user_id', Auth::user()->id)->orderBy("id", "DESC")->take(2)->get();
        $user_favorites = User_favorite::where("table_id", 3)->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
        $favorites = [];

        foreach($user_favorites as $user_favorite) {
           $favorites[$user_favorite] = $user_favorite;
        }
        $data["favorites"] =  $favorites;

        //saved search favorites

        $job_type = [];
        $location_id = [];
        if(isset($_REQUEST["job_type"])) $job_type = $_REQUEST["job_type"];
        
        if(isset($_REQUEST["location"])) $location_id = $_REQUEST["location"];

        if(isset($_REQUEST["search"])) $search = $_REQUEST["search"];
        else $search = "";
        
        //$opening_location_counts =  DB::select("SELECT COUNT(*) AS counts, location_id FROM `openings` INNER JOIN `locations` ON `locations`.`id` = `location_id` WHERE `openings`.`created_at` > NOW() - INTERVAL 24 HOUR GROUP BY `location_id`");
       
       $data["location_counts"] = [];  
       $data["alerts"] = [];
       $data["invoice_type"] = 1;
       $data['stripe_publishable_secret'] = env('STRIPE_PUBLISHABLE_SECRET');
       $data['platform'] = "hiring-manager";
//$alerts->paginate(50, ['*'], 'alert'); 
//	foreach($data['alerts'] as $alert){
//		$alert->time_ago = str_replace('0 hours, ', '',$alert->time_ago);
//		$alert->time_ago = str_replace('1 hours, ', '1 hour, ',$alert->time_ago);
		
//	}

	$data['prospecting_type_id'] = 8;

        return view("platform-hiring-manager", $data);
    }

    //candidate

    public function indexPlatformCandidateActivity()
    {


	if (!Auth::user()) {
		return redirect("/");
	}

        //
        $job_types = Job_type::all();
        $data["customization_variables"] = Customization_variable::where('prospecting_type_id', 6)->get();
        $data['search_terms'] = Search_term::all();
        $data['companies'] = Rolesentry_company::all();
        $data['platform'] = "candidate-activity";
        $data["job_types"] = $job_types;
        $data["user"] = User::find(Auth::id());
        $data["job_subtypes"] = Job_subtype::where("job_type_id", 1)->get();

	if (isset($_REQUEST['new'])){
		$alert = "Please confirm your email at " . Auth::user()->email . " to enjoy all features of Recruiter Intel";
		$conversion_pixel = "<img height=\"1\" width=\"1\" style=\"display:none;\" alt=\"\" src=\"https://dc.ads.linkedin.com/collect/?pid=242484&conversionId=375876&fmt=gif\" />"; 
		$data["alert"] = $alert;
		$data["conversion_pixel"] = $conversion_pixel;
	}


	if (Auth::user()->type == 3){
        	$data['locations'] = Location::whereIn('id',[22,40,32,39,37,31,34,15,16, 42,43,44])->get();
	}
	else{
        	$data['locations'] = Location::whereIn('id',[22,5,1,31,36,33,38,30,34,37,39,40])->get();
	}

        $data['locations_all'] = Location::where('show_in_preferences', 1)->paginate(12, ['*'], 'location');
	$data['locations_all_for_dropdown'] = Location::all();
        $data['job_types_all'] = Job_type::all();
        //saved search
        $data["saved_search_terms"] = SavedSearch::where('user_id', Auth::user()->id)->where('prospecting_type_id', 4)->orderBy("id", "DESC")->take(2)->get();
        $user_favorites = User_favorite::where("table_id", 3)->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
        $favorites = [];

        foreach($user_favorites as $user_favorite) {
           $favorites[$user_favorite] = $user_favorite;
        }
        $data["favorites"] =  $favorites;

        //saved search favorites

        $job_type = [];
        $location_id = [];
        if(isset($_REQUEST["job_type"])) $job_type = $_REQUEST["job_type"];
        
        if(isset($_REQUEST["location"])) $location_id = $_REQUEST["location"];

        if(isset($_REQUEST["search"])) $search = $_REQUEST["search"];
        else $search = "";
        
        //$opening_location_counts =  DB::select("SELECT COUNT(*) AS counts, location_id FROM `openings` INNER JOIN `locations` ON `locations`.`id` = `location_id` WHERE `openings`.`created_at` > NOW() - INTERVAL 24 HOUR GROUP BY `location_id`");
       
       $data["location_counts"] = [];  
       $data["alerts"] = [];
       $data["invoice_type"] = 1;
       $data['stripe_publishable_secret'] = env('STRIPE_PUBLISHABLE_SECRET');
//$alerts->paginate(50, ['*'], 'alert'); 
//	foreach($data['alerts'] as $alert){
//		$alert->time_ago = str_replace('0 hours, ', '',$alert->time_ago);
//		$alert->time_ago = str_replace('1 hours, ', '1 hour, ',$alert->time_ago);
		
//	}

	$data['prospecting_type_id'] = 6;
        return view("platform-candidate-activity", $data);
    }


    public function indexCandidate()
    {


	if (!Auth::user()) {
		return redirect("/");
	}

        //
        $job_types = Job_type::all();
        $data["customization_variables"] = Customization_variable::where('prospecting_type_id', 2)->get();
        $data['search_terms'] = Search_term::all();
        $data['companies'] = Rolesentry_company::all();
        $data['platform'] = "candidate";
        $data["job_types"] = $job_types;
        $data["user"] = User::find(Auth::id());
        $data["job_subtypes"] = Job_subtype::where("job_type_id", 1)->get();

	if (isset($_REQUEST['new'])){
		$alert = "Please confirm your email at " . Auth::user()->email . " to enjoy all features of Recruiter Intel";
		$conversion_pixel = "<img height=\"1\" width=\"1\" style=\"display:none;\" alt=\"\" src=\"https://dc.ads.linkedin.com/collect/?pid=242484&conversionId=375876&fmt=gif\" />"; 
		$data["alert"] = $alert;
		$data["conversion_pixel"] = $conversion_pixel;
	}


	if (Auth::user()->type == 3){
        	$data['locations'] = Location::whereIn('id',[22,40,32,39,37,31,34,15,16, 42,43,44])->get();
	}
	else{
        	$data['locations'] = Location::whereIn('id',[22,5,1,31,36,33,38,30,34,37,39,40])->get();
	}

        $data['locations_all'] = Location::where('show_in_preferences', 1)->paginate(12, ['*'], 'location');
	$data['locations_all_for_dropdown'] = Location::all();
        $data['job_types_all'] = Job_type::all();
        //saved search
        $data["saved_search_terms"] = SavedSearch::where('user_id', Auth::user()->id)->where('prospecting_type_id', 4)->orderBy("id", "DESC")->take(2)->get();
        $user_favorites = User_favorite::where("table_id", 3)->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
        $favorites = [];

        foreach($user_favorites as $user_favorite) {
           $favorites[$user_favorite] = $user_favorite;
        }
        $data["favorites"] =  $favorites;

        //saved search favorites

        $job_type = [];
        $location_id = [];
        if(isset($_REQUEST["job_type"])) $job_type = $_REQUEST["job_type"];
        
        if(isset($_REQUEST["location"])) $location_id = $_REQUEST["location"];

        if(isset($_REQUEST["search"])) $search = $_REQUEST["search"];
        else $search = "";
        
        //$opening_location_counts =  DB::select("SELECT COUNT(*) AS counts, location_id FROM `openings` INNER JOIN `locations` ON `locations`.`id` = `location_id` WHERE `openings`.`created_at` > NOW() - INTERVAL 24 HOUR GROUP BY `location_id`");
       
       $data["location_counts"] = [];  
       $data["alerts"] = [];
       $data["invoice_type"] = 1;
       $data['stripe_publishable_secret'] = env('STRIPE_PUBLISHABLE_SECRET');
//$alerts->paginate(50, ['*'], 'alert'); 
//	foreach($data['alerts'] as $alert){
//		$alert->time_ago = str_replace('0 hours, ', '',$alert->time_ago);
//		$alert->time_ago = str_replace('1 hours, ', '1 hour, ',$alert->time_ago);
		
//	}
	
	$data['prospecting_type_id'] = 2;
        return view("platform-candidate", $data);
    }


    public function platformApi()  
    {
        //

        //print "TEst";
        $job_type = [];
        $location_id = [];
        $query_string_for_location_count = "";
        $searchtext_subtypes = [];

        

        if(isset($_REQUEST["job_type"])) { 
            $job_type = $_REQUEST["job_type"];
            $query_string_for_location_count .= " and openings.job_type_id IN(".implode(",", $job_type  ).")";           
        }   
        
        if(isset($_REQUEST["location"])) {
             $location_id = $_REQUEST["location"];
             $query_string_for_location_count .= " and openings.location_id IN(".implode(",", $location_id  ).")";
        }   

        if(isset($_REQUEST["search"])) {

            $search = $_REQUEST["search"];
            //check search text if it exists in job_subtypes table
            //subtypes
                $data["sub_query"] = "SELECT os.opening_id as id FROM opening_subtypes os JOIN job_subtypes js ON(js.id=os.subtype_id) WHERE js.name LIKE('".$_REQUEST["search"]."')";

                $opening_ids_matched_with_subtype = DB::select("SELECT os.opening_id as id FROM opening_subtypes os JOIN job_subtypes js ON(js.id=os.subtype_id) WHERE js.name LIKE('".$_REQUEST["search"]."')");
                if ($opening_ids_matched_with_subtype) {                   
                    foreach ($opening_ids_matched_with_subtype as $opening_id) {
                        
                        $searchtext_subtypes[] = $opening_id->id;
                    }
                 $search = "";
                 
                 //$alerts->whereIn('openings.id', $subtypes);   
                 //$data["subtypes_1"] = $_REQUEST["subtype"];
                }
                //subtypes           
             if (empty($_REQUEST["subtype"])) {                
                 $query_string_for_location_count .= " and openings.title like('%".$search."%')";
             }   
        }    
        else { 
            $search = "";
        }   
        
        $user_hidden_opening_ids = User_hidden_opening::where("user_id", Auth::id())->pluck("opening_id")->toArray();
        
        //location count
        $opening_location_count = []; 
/*        $opening_location_count =  DB::select("SELECT COUNT(*) AS counts, location_id FROM `openings` INNER JOIN `locations` ON `locations`.`id` = `location_id` WHERE `openings`.`created_at` > NOW() - INTERVAL 72 HOUR  ".$query_string_for_location_count." GROUP BY `location_id`");        
        foreach($opening_location_count as $count){
            $opening_location_count[$count->location_id] = $count->counts;
        }
*/
        $favorites = [];
        $favorites_order = [];
        
        //opening favorites
        $opening_ids_favorites = DB::select("SELECT id FROM openings WHERE id IN(SELECT favoriteable_item_id FROM user_favorites WHERE table_id=4 and user_id=".Auth::user()->id.")");
       
        if($opening_ids_favorites !== null) {
            foreach($opening_ids_favorites as $id){
                $favorites[$id->id] = $id->id;
                $favorites_order[] = $id->id;
            }   
        }

        $data["favorites"] = $favorites;
        //opening favorites ends
        //company favorites
        $user_favorites = User_favorite::where("table_id", 1)->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
        $favorites_company = [];

        foreach($user_favorites as $user_favorite) {
           $favorites_company[$user_favorite] = $user_favorite;
        }
        $data["favorites_company"] =  $favorites_company;
        //company favorites

        $user_favorites = User_favorite::where("table_id", 2)->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
        $favorites_hiring_manager = [];

        foreach($user_favorites as $user_favorite) {
           $favorites_hiring_manager[$user_favorite] = $user_favorite;
        }
        $data["favorites_hiring_manager"] =  $favorites_hiring_manager;

        //hiring manager favorites

        $data['opening_location_counts'] = []; 
        //location count

        $data["saved_search_terms"] = SavedSearch::where('user_id', Auth::user()->id)->orderBy("id", "DESC")->take(2)->get();

        $alerts = DB::table("openings")
        ->join("job_types", 'openings.job_type_id', '=', 'job_types.id')
            ->join("rolesentry_companies", 'openings.rolesentry_company_id','=','rolesentry_companies.id')
            ->join("locations", 'locations.id','=','openings.location_id')
            ->leftJoin("hiring_managers", 'hiring_managers.id','=','openings.hiring_manager_id')
            ->select(DB::raw('openings.job_description_url, openings.job_description_url_on_job_board,rolesentry_companies.logo_url, openings.id as sl, job_types.name as job_type, job_types.id as job_type_id,  rolesentry_companies.name as company, rolesentry_companies.id as rolesentry_company_id, case when length(openings.title) > 50 then concat(LEFT(openings.title, 50), "...") else openings.title end as title, openings.created_at as created_at,locations.name as location, locations.id as location_id,date_format(openings.created_at, "%b %d") AS time_ago, hiring_managers.name as hiring_manager_name,hiring_managers.title as hiring_manager_position, hiring_managers.linkedin_url as hiring_manager_linkedin, openings.manager_auto_detect as manager_auto_detect, hiring_managers.id as hiring_manager_id'))
        ->distinct();

	if (Auth::user()->type == 3){
        	$alerts->whereNotIn('openings.location_id',[1,5]);
    }

    if(isset($_REQUEST["favorites_only"])) {
        $favoriteable_item_ids = User_favorite::pluck("favoriteable_item_id")->toArray();        
        $alerts->where(function($query) use ($favoriteable_item_ids) {
            if(!empty($favoriteable_item_ids)) {  
                $query->whereIn('openings.rolesentry_company_id', $favoriteable_item_ids); 
                $query->orWhereIn('hiring_manager_openings.hiring_manager_id', $favoriteable_item_ids);      
            }
        });        
    }
    
    //subtypes
    if(isset($_REQUEST["subtype"])) {
        $subtypes = $_REQUEST["subtype"];
        $opening_ids_matched_with_subtype = Opening_subtype::whereIn("subtype_id",  $subtypes)->pluck("opening_id")->toArray();        
        $data["subtypes"] = $opening_ids_matched_with_subtype;

	//DISAVLING SUBTYPES
        //$alerts->whereIn('openings.id', $opening_ids_matched_with_subtype);
         
    }

    if(!empty($searchtext_subtypes))
    $alerts->whereIn('openings.id', $searchtext_subtypes);


    if($user_hidden_opening_ids !== null) {
        $data["user_hidden_opening_ids"] = $user_hidden_opening_ids;
        $alerts->whereNotIn('openings.id', $user_hidden_opening_ids);
    }


            if(!empty($job_type)) $alerts->whereIn('openings.job_type_id', $job_type);
            if(!empty($location_id)) $alerts->whereIn('openings.location_id', $location_id);
 

        $alerts->where(function($query) use ($job_type, $location_id, $search) {
           if(!empty($search)) $query->where('openings.title', 'LIKE', '%'.$search.'%');        
           if(!empty($search)) $query->orWhere('rolesentry_companies.name', 'LIKE', '%'.$search.'%'); 
           if(!empty($search)) $query->orWhere('hiring_managers.name', 'LIKE', '%'.$search.'%');
           if(!empty($search)) $query->orWhere('locations.name', 'LIKE', '%'.$search.'%');                              
          
        }) 
        ->where('openings.is_deleted', 0)
        //->orderBy('hiring_managers.linkedin_url', 'DESC')

        ->take(500);
    
        if(isset($_REQUEST["has_hiring_manager"])) {
		$alerts->whereNotNull("hiring_managers.name");
    	}

	if(isset($_REQUEST["is_banned"])){
		$alerts->where('is_banned',"=",1);
	}
	else{
		$alerts->where('is_banned',"!=",1);
	}

        if(isset($_REQUEST["editor_mode"])) {
		$alerts->orderByRaw('openings.manager_auto_detect != "" DESC');
		$alerts->orderByRaw('DATE(openings.created_at) DESC');
		$alerts->orderBy('locations.priority', 'ASC');
	}

	if (isset($_REQUEST["next_email"])) {
		$alerts->join('next_email', 'next_email.opening_id','=','openings.id');
	}

	if (isset($_REQUEST["needs_approval"])) {
		$alerts->where('openings.needs_approval', '=', 1);
	}

	$alertsHiringManagers = clone $alerts;

//	$hms = $alertsHiringManagers->where('hiring_manager_linkedin' , "!=", "")->where('openings.created_at', '>', '(NOW() - INTERVAL 144')->orderBy('created_at', "DESC")->get();
//	$alerts->orderBy('has_hiring_manager', 'DESC');
	//$alerts->orderBy('openings.created_at > (NOW() - INTERVAL 144)','DESC');
        
  //      if(!empty($favorites_order))
//        $alerts->orderByRaw("FIELD(openings.id, ".implode(",", $favorites_order).") DESC");        
        
        $alerts->orderBy('openings.id', 'DESC');

        if(isset($_REQUEST["editor_mode"])) {


/*
	  $alerts->join(DB::raw('

(select location_id lli,MAX(openings.created_at) as lti from locations join openings on locations.id = openings.location_id 
					    join email_openings on email_openings.opening_id = openings.id
					    group by location_id) as t


'), function ($join) {
		$join->on ( 't.lli', '=', 'openings.location_id' );
		$join->on ( 't.lti', '=', 'openings.location_id' );
	    });
*/

	   $openings_in_next_email = NextEmail::select('opening_id')->get()->pluck('opening_id');
	   $alerts->whereNotIn('openings.id',$openings_in_next_email);	

		 if(!empty($location_id)) {
			$locations_last_email_times = Location::getLastEmailTimesAllLocations(array_shift($location_id));
			$alerts->where('openings.created_at','>',$locations_last_email_times);	
		 } 
		 else{
			$locations_last_email_times = Location::getLastEmailTimesAllLocations();
			$locationOpeningIds = [];


	if (Auth::user()->type == 3){
        	$locations = Location::whereIn('id',[22,40,32,39,37,31,34,15,16,42,43,44])->get();
	}
	else{
        	$locations = Location::whereIn('id',[22,5,1,31,36,33,38,30,34,37,39,40])->get();
	}

			foreach($locations as $location) {
				if (isset($locations_last_email_times[$location->id])){
					$alertsForThisLocationSinceLastLocationEmail = opening::where('location_id', $location->id)->where('openings.created_at','>',$locations_last_email_times[$location->id])->pluck('id')->toArray();
					$locationOpeningIds = array_merge($locationOpeningIds,$alertsForThisLocationSinceLastLocationEmail);
				}
				else{
	                                $alertsForThisLocationSinceLastLocationEmail = opening::where('location_id', $location->id)->pluck('id')->toArray();
                                        $locationOpeningIds = array_merge($locationOpeningIds, $alertsForThisLocationSinceLastLocationEmail);
				}
			}	


			$alerts->whereIn('openings.id', $locationOpeningIds);	
	
	
	//get openingIds of all locations 
		 }


/*
		foreach($data['alerts'] as $k=>$alert) {
			if (isset($locations_last_email_times[$alert->location->id])){
				if ($alert->created_at < $locations_last_email_times[$alert->location_id]){
					$data['alerts']->forget($k);
				}				
			}
		}
*/
	}


//	$alerts->orderBy('openings.id','IN',$hms);
        $data["sql"] = $alerts->toSql(); 
        $data["alerts"] = $alerts->paginate(50, ['*'], 'alert'); 
		$data['user_type'] = Auth::user()->type;

	

        return response()->json($data);
    }
/*
    public function ban() 
    {
        $data["success"] = false;        
        $opening = opening::find($_REQUEST["id"]);

	$url = $opening->;
       // $opening->is_deleted = 1;
        //$opening->save();

	

	if ($url){
		$banUrl = new Ban_url();
		$positionOfEnd = strpos($url,'/', 8);
		$banUrl->term = substr($opening->job_description_url, 0,$positionOfEnd);
		$banUrl->save();
	}

        $data["success"] = true;
        return response()->json($data);
    }
*/

    public function getJobSubTypes() {

        $data["success"] = false;
        $data["job_subtypes"] = Job_subtype::where("job_type_id", $_REQUEST["job_type_id"])->get();
        $data["success"] = true;

        return response()->json($data);
    }
    public function deletePlatformItem() 
    {
        $data["success"] = false;
        
        $opening = opening::find($_REQUEST["id"]);
        $opening->is_deleted = 1;
        $opening->save();

        $data["success"] = true;
        return response()->json($data);
    }

    public function hidePlatformItem() 
    {
        $data["success"] = false;
        
        $opening = opening::find($_REQUEST["id"]);
        $user_hidden_opening = User_hidden_opening::create([
            "user_id" => Auth::id(),
            "opening_id" => $opening->id
        ]);

        $data["success"] = true;
        return response()->json($data);
    }

    public function approvePlatformItem() 
    {
        $data["success"] = false;
        
        $opening = opening::find($_REQUEST["id"]);
        
        //approve
        if(isset($_REQUEST["action"]) && $_REQUEST["action"] == 1) {
            $opening->approved = 1;
            $opening->needs_approval = 0;
            $opening->save();

            NextEmail::create([            
                "opening_id" => $opening->id
            ]);
        }
        
        //reject  
        if(isset($_REQUEST["action"]) && $_REQUEST["action"] == 2) {
            //$opening->approved = 0; //Not sure // if deleted from next email while reject
            $opening->skipped = 1;
            $opening->needs_approval = 0;
            $opening->save();            
        }

        $data["success"] = true;
        return response()->json($data);
    }


    public function removeFromNextEmail() 
    {
        $data["success"] = false;
        
        $opening = NextEmail::where("opening_id", $_REQUEST["id"])->delete();
        $data["success"] = true;
        return response()->json($data);
    }

    public function banPlatformItem() 
    {
        $data["success"] = true;
        $opening = Opening::find($_REQUEST["opening_id_ban"]);

	$data['error_message'] = "none";
        
        if($_REQUEST["action"] === "title" && isset($_REQUEST["title_ban"]) && !empty($_REQUEST["title_ban"])) {

            Ban_job_title::create([
                "term" => strtolower($_REQUEST["title_ban"]),
                "created_at" => date("Y-m-d H:i:s",time()),
                "updated_at" => date("Y-m-d H:i:s",time()),

            ]);

        }

        if($_REQUEST["action"] === "company" && isset($_REQUEST["company_ban"]) && !empty($_REQUEST["company_ban"])) {
            
            Ban_company_name::create([
                "term" => strtolower($_REQUEST["company_ban"]),
                "created_at" => date("Y-m-d H:i:s",time()),
                "updated_at" => date("Y-m-d H:i:s",time()),

            ]);
        }

        if($_REQUEST["action"] === "url" && isset($_REQUEST["url_ban"])  && !empty($_REQUEST["url_ban"])) {            
            
             if ( stripos(strtolower($_REQUEST['url_ban']), 'indeed')
		  || stripos(strtolower($_REQUEST['url_ban']), 'simplyhired')
		  || stripos(strtolower($_REQUEST['url_ban']), 'careerjet')
		){
		$data["error_message"] = "Can't ban ". $_REQUEST['url_ban'];
		$data["success"] = false;
	     }

            $banUrl = new Ban_url();
            $positionOfEnd = strpos($opening->job_description_url,'/', 8);
            $banUrl->term = substr($opening->job_description_url, 0,$positionOfEnd);
            $banUrl->created_at = date("Y-m-d H:i:s",time());
            $banUrl->updated_at = date("Y-m-d H:i:s",time());
            $banUrl->save();
        }
	
	if ($data["error_message"] == "none"){

		self::runBanning();
        	$data["success"] = true;
	}
	else{
        	$data["success"] = false;
	}
        return response()->json($data);
    }

        public static function runBanning($id = NULL){

		if ($id == NULL){	
			DB::statement(DB::raw("UPDATE openings set is_banned = 0"));
			DB::statement(DB::raw("UPDATE openings o join rolesentry_companies rc on o.rolesentry_company_id = rc.id join ban_company_names bcn on lower(rc.name) = lower(bcn.term) set o.is_banned = 1"));
			DB::statement(DB::raw("UPDATE openings o join ban_urls bu on lower(o.job_description_url) LIKE CONCAT('%', lower(bu.term), '%') set o.is_banned = 1"));
			DB::statement(DB::raw("UPDATE openings o join ban_job_titles bjt on lower(o.title) LIKE CONCAT('%', lower(bjt.term), '%') set o.is_banned = 1"));

		}
		else{

			DB::statement(DB::raw("UPDATE openings o set is_banned = 0 where o.id = " . $id));
			DB::statement(DB::raw("UPDATE openings o join rolesentry_companies rc on o.rolesentry_company_id = rc.id join ban_company_names bcn on lower(rc.name) = lower(bcn.term) set o.is_banned = 1 where o.id = ". $id));
			DB::statement(DB::raw("UPDATE openings o join ban_urls bu on lower(o.job_description_url) LIKE CONCAT('%', lower(bu.term), '%') set o.is_banned = 1 where o.id = " . $id));
			DB::statement(DB::raw("UPDATE openings o join ban_job_titles bjt on lower(o.title) LIKE CONCAT('%', lower(bjt.term), '%') set o.is_banned = 1 where o.id = " .$id));
		}
	}

    public function editPlatformItem() 
    {
        $data["success"] = false;
        
        $opening = opening::find($_REQUEST["id"]);
        $opening->title = $_REQUEST["title"];

        $opening->human_readable_job_title = $_REQUEST["human_readable_job_title"];
        $opening->human_readable_company_name = $_REQUEST["human_readable_company_name"];
        $opening->rolesentry_company_id = $_REQUEST["rolesentry_company_id"];
        $opening->location_id = $_REQUEST["location_id"];
	    //$opening->hiring_manager_percent = $_REQUEST["hiring_manager_percent"];
        $opening->job_type_id = $_REQUEST["job_type_id"];
       // $opening->hiring_manager_name = $_REQUEST["hiring_manager_name"];
        $opening->intel = $_REQUEST["intel"];
        //$opening->hiring_manager_phone = $_REQUEST["hiring_manager_phone"];
        //$opening->hiring_manager_email = $_REQUEST["hiring_manager_email"];
        //$opening->hiring_manager_position = $_REQUEST["hiring_manager_position"];
        //$opening->hiring_manager_linkedin = $_REQUEST["hiring_manager_linkedin"];

        $opening->save();

        /*if(!empty($_REQUEST["hiring_manager_name"])) {
            
            
            $hiring_manager = Hiring_manager::where("linkedin_url", $opening->hiring_manager_linkedin)->first();
            
            if($hiring_manager == null) {
                $hiring_manager =  Hiring_manager::create([
                    "name" =>$_REQUEST["hiring_manager_name"],
                    "intel" =>$opening->intel,
                    "phone" =>$_REQUEST["hiring_manager_phone"],
                    "email" =>$_REQUEST["hiring_manager_email"],
                    "title" => $_REQUEST["hiring_manager_position"],
                    "linkedin_url" => $_REQUEST["hiring_manager_linkedin"],
                    "prospect_id" => 1
                ]);
            }

           // $opening->hiring_manager_id = $hiring_manager->id;
            $opening->save();
        } //hm
        */

	//if ($opening->hiring_manager_linkedin) {
    if(isset($_REQUEST["hiring_manager_linkedin"])) {   
		if (Auth::user()->type == 1) {

		       $next_email_rec = NextEmail::where("opening_id", $opening->id)->first();
		       if($next_email_rec === null) {
				
				$next_email = NextEmail::create([
				    "opening_id" => $opening->id,
				]);
			}
		}
		else{
			$opening->needs_approval = 1;
			$opening->save();
		}

	}

        $data["success"] = true;
        return response()->json($data);
    }

    public function loadEditPlatformItem() 
    {        
        
        $opening = opening::with("company")->with("hiring_manager_openings.hiring_manager")->find($_REQUEST["id"]);
	if ($opening->job_description_url){
		$positionOfEnd = strpos($opening->job_description_url,'/', 8);
		$url_for_ban = substr($opening->job_description_url, 0, $positionOfEnd);
	}
	else{
		$url_for_ban = "";
	}
	

        $opening->url_for_ban = $url_for_ban; 
       // $openings_for_this_company = opening::where("rolesentry_company_id", $opening->rolesentry_company_id)->whereNotNull("hiring_manager_name")->distinct()->get(['hiring_manager_name','hiring_manager_position','hiring_manager_linkedin']);

        /*if ($opening->hiring_manager_percent < 80){
            $opening->hiring_manager_percent = 80;
        }

        if ($opening->hiring_manager_percent > 100){
            $opening->hiring_manager_percent = 100;
        }
        */

        $data["opening"] = $opening;
        //$data["openings"] = $openings_for_this_company;

        return response()->json($data);
    }


    public function getScheme() {
	$scheme = Scheme::find($_REQUEST['id']);
	$scheme_steps = SchemeStep::where('scheme_id', '=', $scheme->id)->get();

	$newly_created_scheme_steps_for_this_saved_search = [];

	SchemeStep::where('saved_search_id', '=', $_REQUEST['saved_search_id'])->delete();

	foreach ($scheme_steps as $scheme_step) {

				$new_step = SchemeStep::create(['saved_search_id'=>$_REQUEST['saved_search_id'], 
				    'email_body'=>$scheme_step->email_body,
				    'email_subject'=>$scheme_step->email_subject,
				    'scheme_steps_wait_id'=>$scheme_step->scheme_steps_wait_id
				  ]);

		                $scheme_step_lock_types = SchemeStepLockType::where('scheme_step_id', $scheme_step->id)->get();

				foreach($scheme_step_lock_types as $scheme_step_lock_type) {

					$new_scheme_step_lock_type = SchemeStepLockType::create(['scheme_step_id'=>$new_step->id,
								    'lock_type_id'=>$scheme_step_lock_type->lock_type_id]);

					
				

					$scheme_step_lock_type_detail = SchemeStepLockTypeDetail::where('scheme_step_lock_types_id', $scheme_step_lock_type->id)->first();

	
					$new_scheme_step_lock_type_detail = SchemeStepLockTypeDetail::create(['scheme_step_lock_types_id'=>$new_scheme_step_lock_type->id,
													      'value'=>$scheme_step_lock_type_detail->value]);

					$locks[] = ['scheme_step_lock_type_id'=>$new_scheme_step_lock_type->id,
								     'scheme_step_lock_type_detail_value'=>$new_scheme_step_lock_type_detail->value];
				}

				$newly_created_scheme_steps_for_this_saved_search[] = ['step'=>$new_step, 'locks'=>(isset($locks) ? $locks : [])];
	}

	$data["scheme_steps"] = $newly_created_scheme_steps_for_this_saved_search;
        return response()->json($data);    
    }

    public function getSchemes() {

	$schemes = Scheme::where('prospecting_type_id','=',$_REQUEST['prospecting_type_id'])->get();
	$data["schemes"] = $schemes;	

        return response()->json($data);    

    }

    public function saveSchemeStep(Request $request) {

        $data["success"] = false;

        $scheme_step = SchemeStep::where("id", $_REQUEST["scstid"])->first();
	    $saved_search = SavedSearch::where("id", $_REQUEST["ssid"])->first();

	if ($scheme_step == null) {

			$scheme_step = SchemeStep::create([
				"email_subject"=>str_replace("hcard widget","", $this->transformComplexTextToVariableNames($_REQUEST["sses"])),
				"email_body"=> str_replace("hcard widget","", $this->transformComplexTextToVariableNames($_REQUEST["sseb"])),
				"scheme_steps_wait_id"=>$_REQUEST["sswi"],
				"saved_search_id"=>$_REQUEST["ssid"]
			]);			

			if (isset($_REQUEST['lock_type_1'])){

				$scheme_step_lock_type = new SchemeStepLockType();
				$scheme_step_lock_type->lock_type_id = 1;
				$scheme_step_lock_type->scheme_step_id = $scheme_step->id;
				$scheme_step_lock_type->save();

				$scheme_step_lock_type_details = new SchemeStepLockTypeDetail();
				$scheme_step_lock_type_details->scheme_step_lock_types_id = $scheme_step_lock_type->id;
				$scheme_step_lock_type_details->value = $_REQUEST['lock_type_1'];
				$scheme_step_lock_type_details->save();

				$data['scheme_step_locks'][] = ['scheme_step_lock_type_details_id'=>$scheme_step_lock_type_details->id, 'scheme_step_lock_type_details_value'=>$scheme_step_lock_type_details->value];
			}

			if (isset($_REQUEST['lock_type_2'])){

				$scheme_step_lock_type = new SchemeStepLockType();
				$scheme_step_lock_type->lock_type_id = 2;
				$scheme_step_lock_type->scheme_step_id = $scheme_step->id;
				$scheme_step_lock_type->save();

				$scheme_step_lock_type_details = new SchemeStepLockTypeDetail();
				$scheme_step_lock_type_details->scheme_step_lock_types_id = $scheme_step_lock_type->id;
				$scheme_step_lock_type_details->value = $_REQUEST['lock_type_2'];
				$scheme_step_lock_type_details->save();

	

				$data['scheme_step_locks'][] = ['scheme_step_lock_type_details_id'=>$scheme_step_lock_type_details->id, 'scheme_step_lock_type_details_value'=>$scheme_step_lock_type_details->value];
			}
	}

	else {
                $_REQUEST["sses"]= str_replace("hcard widget","",$_REQUEST["sses"]);
                $_REQUEST["sseb"] = str_replace("hcard widget","",$_REQUEST["sseb"]);
                
                $scheme_step->email_subject = $this->transformComplexTextToVariableNames($_REQUEST["sses"]);
			    $scheme_step->email_body = $this->transformComplexTextToVariableNames($_REQUEST["sseb"]);
			    //$scheme_step->scheme_steps_wait_id = $_REQUEST["sswi"];

			    $scheme_step->save();

				//if ($_REQUEST['lock_type_1']) {

				if (isset($_REQUEST['lock_type_1'])){

			  	    $scheme_step_lock_type = SchemeStepLockType::where('lock_type_id', 1)
				 	->where('scheme_step_id', $scheme_step->id)->first();


				    if ($scheme_step_lock_type != null) {
					$scheme_step_lock_type_detail = SchemeStepLockTypeDetail::where('scheme_step_lock_types_id', $scheme_step_lock_type->id)->first();
		    
					$scheme_step_lock_type_detail->value = $_REQUEST['lock_type_1'];
					$scheme_step_lock_type_detail->save();

					$data['scheme_step_locks'][] = ['scheme_step_lock_type_details_id'=>$scheme_step_lock_type_detail->id, 'scheme_step_lock_type_details_value'=>$scheme_step_lock_type_detail->value];
				    }
				}


				if (isset($_REQUEST['lock_type_2'])){

			  	    $scheme_step_lock_type = SchemeStepLockType::where('lock_type_id', 2)
				 	->where('scheme_step_id', $scheme_step->id)->first();

				    if ($scheme_step_lock_type != null) {
					$scheme_step_lock_type_detail = SchemeStepLockTypeDetail::where('scheme_step_lock_types_id', $scheme_step_lock_type->id)->first();
		    
					$scheme_step_lock_type_detail->value = $_REQUEST['lock_type_2'];
					$scheme_step_lock_type_detail->save();

					$data['scheme_step_locks'][] = ['scheme_step_lock_type_details_id'=>$scheme_step_lock_type_detail->id, 'scheme_step_lock_type_details_value'=>$scheme_step_lock_type_detail->value];
				    }
				}

               // }

	}

 
            $data["success"] = true;
	    $data["id"] = $scheme_step->id;
	
            
            return response()->json($data);    
    }

    public function transformComplexTextToVariableNames($text) {
	return $text;
       
//        return strip_tags($text);
        //Remove plus with empty text
       // $search_text = '<a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> <span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"></span></span></span></a>';
        //$replace_text = '';
        //$text = str_replace($search_text, $replace_text, $text);

        //Hiring Manager First Name
        //$search_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager First Name%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p><p></p>';
        $search_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="1" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1">&nbsp;{%Hiring Manager First Name%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background: rgba(220, 220, 220, 0.5) url(&quot;https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png&quot;) repeat scroll 0% 0%; top: -14px; left: 0px; display: block;"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span>';
        $search_text1 = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="1" role="region" aria-label="hcard widget" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1">&nbsp;{%Hiring Manager First Name%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background: rgba(220, 220, 220, 0.5) url(&quot;https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png&quot;) repeat scroll 0% 0%; top: -14px; left: 0px; display: block;"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span>';    
        $replace_text = '{%Hiring Manager First Name%}';

        if (strpos($text, $replace_text) !== false) {
           // $text = str_replace($search_text, $replace_text, $text);
           // $text = str_replace($search_text1, $replace_text, $text);
            $text = strip_tags($text);
        }
        //Hiring Manager Last Name
        $search_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager Last Name%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p><p></p>';
        $replace_text = '{%Hiring Manager Last Name%}'; 
        if (strpos($text, $replace_text) !== false)                                   
        $text = strip_tags($text); //$text = str_replace($search_text, $replace_text, $text);


        //Hiring Manager Company
        $search_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager Company%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p><p></p>';
        $replace_text = '{%Hiring Manager Company%}'; 
        if (strpos($text, $replace_text) !== false)                                   
        $text = strip_tags($text); //$text = str_replace($search_text, $replace_text, $text);
        
        //Hiring Manager Phone
        $search_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager Phone%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p><p></p>';
        $replace_text = '{%Hiring Manager Phone%}'; 
        if (strpos($text, $replace_text) !== false)                                   
        $text = strip_tags($text);//$text = str_replace($search_text, $replace_text, $text);

        //Job Title
        $search_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Job Title%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p><p></p>';
        $replace_text = '{%Job Title%}'; 
        if (strpos($text, $replace_text) !== false)                                   
        $text = strip_tags($text);//$text = str_replace($search_text, $replace_text, $text);
    
        $clean_string_for_database_storage = $text;

        return $clean_string_for_database_storage;
    }

    public function transformVariablesToComplexText($text) {
     return $text; 
         //Remove plus with empty text
         $search_text = '<a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> <span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"></span></span></span></a>';
         $replace_text = '';
         $text = str_replace($search_text, $replace_text, $text);
        
        //Hiring Manager First Name
        $replace_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager First Name%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p>';
        $search_text = '{%Hiring Manager First Name%}'; 
        if (strpos($text, $search_text) !== false)            
        $text = str_replace($search_text, $replace_text, $text);
        
        //Hiring Manager Last Name
        $replace_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager Last Name%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p>';
        $search_text = '{%Hiring Manager Last Name%}';
        if (strpos($text, $search_text) !== false)                        
        $text = str_replace($search_text, $replace_text, $text);

        //Hiring Manager Company
        $replace_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager Company%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p>';
        $search_text = '{%Hiring Manager Company%}';
        if (strpos($text, $search_text) !== false)                        
        $text = str_replace($search_text, $replace_text, $text);
        
        //Hiring Manager Phone
        $replace_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Hiring Manager Phone%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p>';
        $search_text = '{%Hiring Manager Phone%}'; 
        if (strpos($text, $search_text) !== false)                       
        $text = str_replace($search_text, $replace_text, $text);

        //Job Title
        $replace_text = '<span tabindex="-1" data-cke-widget-wrapper="1" data-cke-filter="off" class="cke_widget_wrapper cke_widget_inline cke_widget_hcard cke_widget_wrapper_h-card" data-cke-display-name="hcard" data-cke-widget-id="0" role="region" aria-label="" contenteditable="false"><span class="h-card cke_widget_element" data-cke-widget-data="%7B%22classes%22%3A%7B%22h-card%22%3A1%7D%7D" data-cke-widget-upcasted="1" data-cke-widget-keep-attr="0" data-widget="hcard" data-cke-widget-white-space="1"><a style="margin:1px 1px 1px 1px !important; padding:2px 8px 4px 8px !important; text-transform:uppercase" class="contact contactList contacts scheme_step"><span style="padding-left:1px" class="glyphicon glyphicon-plus" data-cke-white-space-first="1"> {%Job Title%}</span></a></span><span class="cke_reset cke_widget_drag_handler_container" style="background:rgba(220,220,220,0.5);background-image:url(https://cdn.ckeditor.com/4.10.0/standard-all/plugins/widget/images/handle.png)"><img class="cke_reset cke_widget_drag_handler" data-cke-widget-drag-handler="1" src="data:image/gif;base64,R0lGODlhAQABAPABAP///wAAACH5BAEKAAAALAAAAAABAAEAAAICRAEAOw==" title="Click and drag to move" role="presentation" draggable="true" width="15" height="15"></span></span></p>';
        $search_text = '{%Job Title%}';
        if (strpos($text, $search_text) !== false)                        
        $text = str_replace($search_text, $replace_text, $text);
    
        $not_clean_string_for_CKEditor = $text;

        return $not_clean_string_for_CKEditor; 

     } 

    public function saveOpeningHiringManager() {

        $data["success"] = false;

        $hm = Hiring_manager::where("id", $_REQUEST["hiring_manager_id"])->first();
	$hm_opening = Hiring_manager_opening::where("opening_id", $_REQUEST["opening_id"])->first();
	$hm_opening_both = Hiring_manager_opening::where("opening_id", $_REQUEST["opening_id"])
							->where("hiring_manager_id", $_REQUEST['hiring_manager_id'])
							->first();

	$opening = Opening::find($_REQUEST['opening_id']);


	if ($hm == null) {

        $prospect = Prospect::create([
            "type_id" => 1
            ]);

	

	    $hm = Hiring_manager::create([
		    "name" =>$_REQUEST["hiring_manager_name"],
		    "intel" =>"",
		    "phone" =>$_REQUEST["hiring_manager_phone"],
		    "company_id" =>$opening->rolesentry_company_id,
		    "email" =>$_REQUEST["hiring_manager_email"],
		    "title" => $_REQUEST["hiring_manager_position"],
		    "linkedin_url" => $_REQUEST["hiring_manager_linkedin"],
            "prospect_id" => $prospect->id
            ]);
	} else {

	     //just update this hiring manager
            $hm->name = $_REQUEST["hiring_manager_name"];
            $hm->phone = $_REQUEST["hiring_manager_phone"];
            $hm->email = $_REQUEST["hiring_manager_email"];
            $hm->title = $_REQUEST["hiring_manager_position"];
            $hm->linkedin_url = $_REQUEST["hiring_manager_linkedin"];

            $hm->save();
	}

	if ($hm_opening_both == null) {

		$hm_certainty_value = $_REQUEST['hiring_manager_certainty'] ? $_REQUEST['hiring_manager_certainty'] : 0;
		$hm_certainty_value = is_numeric($hm_certainty_value) ? $hm_certainty_value : 0;

	    $data["hmo"] = Hiring_manager_opening::create([
		    "opening_id" => $_REQUEST["opening_id"],
		    "certainty" => $hm_certainty_value,
		    "hiring_manager_id" => $hm->id
	    ]);
		
	} else {
	     $hm_opening_both->certainty = $_REQUEST["hiring_manager_certainty"] ?  $_REQUEST["hiring_manager_certainty"] : 0;
	     $hm_opening_both->save();
	}	

	    $this->setNewMostLikelyHiringManager($_REQUEST['opening_id']);
 
            $data["success"] = true;
            
            return response()->json($data);    
    }

    public function setNewMostLikelyHiringManager($opening_id) {
	    $most_probable_hiring_manager = Hiring_manager_opening::where('opening_id', $opening_id)->orderBy('certainty','DESC')->first();

			$opening = Opening::find($opening_id);
			$opening->hiring_manager_id = $most_probable_hiring_manager->hiring_manager_id;
			$opening->hiring_manager_certainty = $most_probable_hiring_manager->certainty;
			$opening->save();	
    }

    public function deleteSchemeStep() {
        
        $data["success"] = false;
        $ss = SchemeStep::where("id", $_REQUEST["scheme_step_id"])->first();
        $ss->delete();
        $data["success"] = true;

        return response()->json($data);
    }


    public function deleteOpeningHiringManager() {
        
        $data["success"] = false;
        $hmo = Hiring_manager_opening::where("opening_id", $_REQUEST["opening_id"])->where("hiring_manager_id", $_REQUEST["hiring_manager_id"])->first();
        $hmo->delete();
        $data["success"] = true;

	$this->setNewMostLikelyHiringManager($_REQUEST['opening_id']);

        return response()->json($data);
    }

    public function deleteSavedSearchItem() 
    {
         $data["success"] = false;
        
         SavedSearch::find($_REQUEST["id"])->delete();
         $data["saved_search_terms"] = SavedSearch::orderBy("id", "DESC")->take(2)->get();
        
         $data["success"] = true;
         return response()->json($data);
    }

    public function addUserFavoriteItem() 
    {
         $data["success"] = false;
        
        //table Id 2 Hiring Manager
        if( $_REQUEST["table_id"] == 2) {
            
            //$_REQUEST["favoriteable_item_id"] == opening.id
            
            $user_favorite = User_favorite::where("favoriteable_item_id", $_REQUEST["favoriteable_item_id"])->where("table_id", 2)->where("user_id", Auth::id())->first();          
            //unfavorite if exists
            if($user_favorite != null && $_REQUEST["opening_id"] != -1) { 

            	$user_favorites = User_favorite::where("favoriteable_item_id", $_REQUEST["favoriteable_item_id"])->where("table_id", 2)->where("user_id", Auth::id());
		$user_favorites->delete();
//                User_favorite::find($user_favorite->id)->delete();
                $data["success"] = true;
                $data["message"] = "Successfully Unfavorited";
                return response()->json($data);
            } //unfavorite ends otherwise 
            
            //favorite 

	    if ($_REQUEST["favoriteable_item_id"] != "null") {
		    //$opening = Opening::find($_REQUEST["favoriteable_item_id"]);
            
            	$opening = Opening::where("id", $_REQUEST["opening_id"])->first();
		    if ($opening != null) {
			$hiring_manager = Hiring_manager::where("linkedin_url", $opening->hiring_manager_linkedin)->first();
			//not found so favorite it
			if ($hiring_manager == null) {
			    $prospect = Prospect::create([
				"type_id" => 1
				]);
			    $hiring_manager =  Hiring_manager::create([
			    "name" =>$opening->hiring_manager_name,
			    "title" =>$opening->hiring_manager_position,
			    "linkedin_url" =>$opening->hiring_manager_linkedin,
			    "prospect_id" => $prospect->id
			]);
			}
		    }    
	    }
	    else{
            $opening = Opening::where("id", $_REQUEST["opening_id"])->first();
            //$opening = Opening::find($_REQUEST["favoriteable_item_id"]);
            if ($opening != null) {
            $hiring_manager = Hiring_manager::where("linkedin_url", $opening->hiring_manager_linkedin)->first();
            //not found so favorite it
            if ($hiring_manager == null) {
                $prospect = Prospect::create([
                    "type_id" => 1
                    ]);
                $hiring_manager =  Hiring_manager::create([
                "name" =>$opening->hiring_manager_name,
                "title" =>$opening->hiring_manager_position,
                "linkedin_url" =>$opening->hiring_manager_linkedin,
                "prospect_id" => $prospect->id
            ]);
            }
        }
		   	
	    }

            //if(!empty($hiring_manager->id))
            //$_REQUEST["favoriteable_item_id"] = $hiring_manager->id;
        }

         //opening
        if( $_REQUEST["table_id"] == 4) {
            $user_favorite = User_favorite::where("favoriteable_item_id", $_REQUEST["favoriteable_item_id"])->where("user_id", Auth::id())->first();          
            //unfavorite if exists
            if($user_favorite != null) { 
                User_favorite::find($user_favorite->id)->delete();
                $data["success"] = true;
                $data["message"] = "Successfully Unfavorited";
                return response()->json($data);
            }    
        }
        
        
         //company unfavorite
         // -1 action on click on leftsidebar avoid unfavorite r ather search it
         if( $_REQUEST["table_id"] == 1 && $_REQUEST["opening_id"] != -1) {
            $user_favorite = User_favorite::where("favoriteable_item_id", $_REQUEST["favoriteable_item_id"])->where("table_id", 1)->where("user_id", Auth::id())->first();          
            //unfavorite if exists
            if($user_favorite != null) { 
                $userfavorites = User_favorite::where('favoriteable_item_id',$_REQUEST["favoriteable_item_id"])->where("table_id", 1)->where("user_id", Auth::id());
		$userfavorites->delete();
		foreach($userfavorites as $fav){
			$fav->delete();
		}
                $data["success"] = true;
                $data["message"] = "Successfully Unfavorited";
                return response()->json($data);
            }    
        }

        //saved search unfavorite
        if( $_REQUEST["table_id"] == 3) {
            $user_favorite = User_favorite::where("favoriteable_item_id", $_REQUEST["favoriteable_item_id"])->where("table_id", 3)->where("user_id", Auth::id())->first();          
            //unfavorite if exists
            if($user_favorite != null) { 
                User_favorite::find($user_favorite->id)->delete();
                $data["success"] = true;
                $data["message"] = "Successfully Unfavorited";
                return response()->json($data);
            }    
        }


        //candidate
        if( $_REQUEST["table_id"] == 5) {
            $user_favorite = User_favorite::where("favoriteable_item_id", $_REQUEST["favoriteable_item_id"])->where("table_id", 5)->where("user_id", Auth::id())->first();          
            //unfavorite if exists
            if($user_favorite != null) { 
                User_favorite::find($user_favorite->id)->delete();
                $data["success"] = true;
                $data["message"] = "Successfully Unfavorited";
                return response()->json($data);
            }    
        }

         $data["user_favorite"] = User_favorite::create([
             "favoriteable_item_id" => $_REQUEST["favoriteable_item_id"],
             "table_id" => $_REQUEST["table_id"],
             "user_id" => Auth::id()
         ]);

         $data["success"] = true;
         return response()->json($data);
    }


    public function getSavedSearchItems() 
    {

         $data["saved_search_terms"] = SavedSearch::where('user_id', Auth::user()->id)->orderBy("id", "DESC")->take(2)->get(); 
         $user_favorites = User_favorite::where("table_id", 3)->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
         $favorites = [];

         foreach($user_favorites as $user_favorite) {
            $favorites[$user_favorite] = $user_favorite;
         }
         $data["favorites"] =  $favorites; 
         
         $data["success"] = true;
         return response()->json($data);
    }

    public function updateSavedSearchItem() 
    {
        $data["success"] = false;
       if (isset($_REQUEST["ss_id"]) && !empty($_REQUEST["ss_id"])) {
           $saved_search = SavedSearch::find($_REQUEST["ss_id"]);
           /*
           if(isset($_REQUEST["name"]) && $_REQUEST["name"] == "is_instant") {
               if($_REQUEST["checked"] == "true")
                   $saved_search->is_instant = 1;
               else $saved_search->is_instant = 0;
           }

           if(isset($_REQUEST["name"]) && $_REQUEST["name"] == "is_daily") {
               if($_REQUEST["checked"] == "true")
                   $saved_search->is_daily = 1;
               else $saved_search->is_daily = 0;
           }
           */
           $saved_search->term = $_REQUEST["ss_term"];
           $saved_search->name = $_REQUEST["ss_name"];

           $saved_search->needs_approval = isset($_REQUEST["ss_needs_approval"])? 1:0;
           //        $saved_search->email_subject = $_REQUEST["email_subject"];
           //       $saved_search->email_body = $_REQUEST["email_body"];

           $SavedSearchJobTypes = SavedSearchJobType::where('saved_search_id', $_REQUEST['ss_id'])->delete();
       } //update
       else {
           //create from prospect page for ss
           $saved_search = SavedSearch::create([
            "user_id" => Auth::id(),
            "term" => $_REQUEST["ss_term"],
            "name" => $_REQUEST["ss_name"],
            "needs_approval" => isset($_REQUEST["ss_needs_approval"])? 1:0,
            "time_to_send" => 10,
            "is_instant" => 0,
            "is_daily" => 0,
        "prospecting_type_id" => $_REQUEST["ss_prospecting_type_id"],
        "created_at" => date("Y-m-d H:i:s", time()),
        "updated_at" => date("Y-m-d H:i:s", time())
        ]);
        $saved_search = SavedSearch::find($saved_search->id);
        $data["saved_search"] = $saved_search;
       }
       

	foreach($_REQUEST['ss_job_type_id'] as $job_type_to_set){
		$SavedSearchJobType = new SavedSearchJobType();
		$SavedSearchJobType->saved_search_id = $_REQUEST['ss_id'];
		$SavedSearchJobType->job_type_id = $job_type_to_set;
		$SavedSearchJobType->save();
	}

	foreach($_REQUEST['ss_location_id'] as $location_to_set){
		$SavedSearchLocation = new SavedSearchLocation();
		$SavedSearchLocation->saved_search_id = $_REQUEST['ss_id'];
		$SavedSearchLocation->location_id = $location_to_set;
		$SavedSearchLocation->save();
	}

        $saved_search->save();

        $data["success"] = true;
        return response()->json($data);
    }

    public function saveSearchItem() 
    {
        $data["success"] = false;
        
        $saved_search = SavedSearch::create([
            "user_id" => Auth::id(),
            "term" => $_REQUEST["search"],
            "time_to_send" => 10,
            "is_instant" => 0,
            "is_daily" => 0,
	    "prospecting_type_id" => $_REQUEST["prospecting_type_id"]
        ]);

        $job_types = [];
        $location_ids = [];

        if(isset($_REQUEST["job_type"])) { 
            $job_types = $_REQUEST["job_type"];
            foreach($job_types as $job_type ){
                SavedSearchJobType::create([
                   "saved_search_id" => $saved_search->id,
                   "job_type_id" => $job_type
                ]);
            }
        }

        if(isset($_REQUEST["location"])) { 
            $location_ids = $_REQUEST["location"];
            foreach($location_ids as $location_id ){
                SavedSearchLocation::create([
                   "saved_search_id" => $saved_search->id,
                   "location_id" => $location_id
                ]);
            }

        }

        $data["success"] = true;
        return response()->json($data);
    }


    public function inviteColleague() 
    {
         $data["success"] = false;

         if($_REQUEST["remaining_invites"] <= 0) {
            $data["success"] = false;
            return response()->json($data);
         }
        
         if(isset($_REQUEST["invite_colleague_name"]) &&
            isset($_REQUEST["invite_colleague_email"])&& 
            !empty($_REQUEST["invite_colleague_name"])&&  
            !empty($_REQUEST["invite_colleague_email"])) 
         {
             $randompw = time();
             $already_user = User::where("email", $_REQUEST["invite_colleague_name"])->first();
            
             if($already_user != null) {
                $data["success"] = false;
                return response()->json($data);
             }

             $new_created_user = User::create([
                'name' => $_REQUEST["invite_colleague_name"],
                'email' => $_REQUEST["invite_colleague_email"],
                'password' => bcrypt($randompw),
                'type' => 2,
                'is_confirmed'=> 0
            ]);

             $current_user_pereferences = User_preference::where('user_id', Auth::id())->get();
             foreach ($current_user_pereferences as $pref) {
                 User_preference::create([
                                "user_id" => $new_created_user->id,
                                "job_type_id" => $pref->job_type_id,
                                "location_id" => $pref->location_id,
                                "created_at" => date("Y-m-d H:i:s", time()),
                                "updated_at" => date("Y-m-d H:i:s", time())
                            ]);
             }
            
             //invites remmaining
             if (Auth::user()->remaining_invites) {
                 User_invite::create([
                        "inviter_user_id" => Auth::id(),
                        "invitee_user_id" => $new_created_user->id,
                        "created_at" => date("Y-m-d H:i:s", time())
                    ]);

                $user = User::find(Auth::id());
                $user->remaining_invites = $user->remaining_invites - 1;
                $user->save();
                     
                 $message = '
                    Hi '.$_REQUEST["invite_colleague_name"].' - your colleague '.ucfirst(Auth::user()->name).' has sent you an invitation to Recruiter Intel.';
                
                 if ($_REQUEST["invite_colleague_message"]) {
                     $message .= '<br><br><b>' . $_REQUEST["invite_colleague_message"] ."</b>";
                 }

                 $message .= '

                    <br><br>
                You can login here <a href="http://recruiterintel.com/login">recruiterintel.com/login</a> to access our search platform and customize your email preferences.
                <br><br>Your username is '.$_REQUEST["invite_colleague_email"].'<br>
                    and your password is <strong>'.$randompw.'</strong> (please change password when you login).<br>
                    <br>
                    Recruiter Intel alerts you about new roles and attaches the hiring manager along with other useful intel.
                <br><br>
                    Thank you!<br>
                    -Recruiter Intel';
            
                 $subject = $_REQUEST["invite_colleague_name"] . ' - you have been invited to Recruiter Intel by ' . ucfirst(Auth::user()->name);
                
                 if(Auth::user()->type == 1 && isset($_REQUEST["donotsendemail"]) && $_REQUEST["donotsendemail"]) {
                    //no mail for admin which don'tsendm ail checked
                 } else $this->sendMail([$_REQUEST["invite_colleague_email"]], $message, $_REQUEST["invite_colleague_email"], 1, $subject);
             
            }
         }    //invite
       
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

    public function getUserFavorites() 
    {
        if(!isset($_REQUEST["table_id"])) $_REQUEST["table_id"] = 1;

        $user_favorites = User_favorite::where("table_id", $_REQUEST["table_id"])->where("user_id", Auth::user()->id)->pluck("favoriteable_item_id")->toArray();          
        $data["user_favorites"] = $user_favorites; 
        if($_REQUEST["table_id"] == 1) { 
            if (isset($_REQUEST["search_text"]) && !empty($_REQUEST["search_text"])) {
                //$data["favorites"] = Rolesentry_company::whereIn("id", $user_favorites)->where("name", "like", "%".$_REQUEST["search_text"]."%")->get();
                $data["favorites"] = Rolesentry_company::where("name", "like", "%".$_REQUEST["search_text"]."%")->distinct()->get(['name']);
                
            }   else $data["favorites"] = Rolesentry_company::whereIn("id", $user_favorites)->get();
        }  

        if ($_REQUEST["table_id"] == 2) {

            if (isset($_REQUEST["search_text"]) && !empty($_REQUEST["search_text"])) {
                //$data["favorites"] = Hiring_manager::whereIn("id", $user_favorites)->where("name", "like", "%".$_REQUEST["search_text"]."%")->get();
                $data["favorites"] = Hiring_manager::where("name", "like", "%".$_REQUEST["search_text"]."%")->distinct()->get(['name']);
            } else {
                     $data["favorites"] = Hiring_manager::whereIn("id", $user_favorites)->get();
                     $data["favorite_sql"] = Hiring_manager::whereIn("id", $user_favorites)->toSql();
             }
             $hms =  $data["favorites"];
             $pa = [];
             foreach($hms as $hm){
                 if(!empty($hm->prospect_id)){
                     $pac = DB::table("prospecting_actions")->where("prospect_id", $hm->prospect_id)->where("prospecting_action_type_id", 3)->count();                     
                     $pa[$hm->prospect_id] = $pac;
                 }

             }
             $data["pa"] = $pa;

        }    
        
        if ($_REQUEST["table_id"] == 3) {
            if(isset($_REQUEST["search_text"]) && !empty($_REQUEST["search_text"]))
                $data["favorites"] = SavedSearch::whereIn("id", $user_favorites)->where("term", "like", "%".$_REQUEST["search_text"]."%")->get();
            else $data["favorites"] = SavedSearch::whereIn("id", $user_favorites)->get();
        }    
        
        if ($_REQUEST["table_id"] == 4) {

            if(isset($_REQUEST["search_text"]) && !empty($_REQUEST["search_text"]))
                 $data["favorites"] = Opening::whereIn("id", $user_favorites)->where("title", "like", "%".$_REQUEST["search_text"]."%")->get();
            else $data["favorites"] = Opening::whereIn("id", $user_favorites)->get();
        }    
        
        if ($_REQUEST["table_id"] == 5) {
            
                        if (isset($_REQUEST["search_text"]) && !empty($_REQUEST["search_text"])) {
                            //$data["favorites"] = Hiring_manager::whereIn("id", $user_favorites)->where("name", "like", "%".$_REQUEST["search_text"]."%")->get();
                            $data["favorites"] = Candidate::where("name", "like", "%".$_REQUEST["search_text"]."%")->distinct()->get(['name']);
                        } else {
                                 $candidates= Candidate::whereIn("id", $user_favorites)->get();
                                 $data["favorites"] = $candidates;
                                 $pa = [];
                                 foreach($candidates as $candidate){
                                     if(!empty($candidate->prospect_id)){
                                         $pac = DB::table("prospecting_actions")->where("prospect_id", $candidate->prospect_id)->where("prospecting_action_type_id", 3)->count();
                                         //$pac = DB::select("SELECT COUNT(*) as num FROM prospecting_actions WHERE prospecting_action_type_id = 3 AND prospect_id =".$candidate->prospect_id);
                                         $pa[$candidate->prospect_id] = $pac;
                                     }

                                 }
                                 $data["pa"] = $pa;
                                 $data["favorite_sql"] = Candidate::whereIn("id", $user_favorites)->toSql();
                         }
            
        }    
            



        $data["success"] = true;
        return response()->json($data);
    }

    public function lockUser($id) {

        $data["success"] = false;
        $user = User::find($id);
        
        if ($user != null) {
             $status = User::where("id", $id)->update(["is_locked" => 1]);
            if ($status) {
                $data["success"] = true;

            }    

        } 
        
        return redirect("/");
    }

    public function alertFrequency() {
        
                $data["success"] = false;
                
                     $status = User::where("id", Auth::id())->update(["alert_frequency" => $_REQUEST["frequency"]]);
                    if ($status) {
                        $data["success"] = true;
                    }    
        
               
                
                return response()->json($data);
            }


    public function searchLocation() 
    {
        
        $data["success"] = false;
        
        $locations = Location::where('show_in_preferences',1)->where("name", "like", "%".$_REQUEST["search_location"]."%")->paginate(12, ['*'], 'locationpage');   //->get();
        
        $data["locations"] = $locations;
        if(isset($_REQUEST["location"]))
        $data["locations_selected"] = $_REQUEST["location"];
        if ($locations) {
            $data["success"] = true;
        }    

        return response()->json($data);
    }        

    public function recruiterWidget($recruiter_firm_id, $job_type_ids, $job_subtype_ids, $location_ids) {
        
        $alerts = DB::table("openings")
            ->join("job_types", 'openings.job_type_id', '=', 'job_types.id')
            ->join("job_subtypes", 'job_subtypes.job_type_id', '=', 'job_types.id')
            ->join("rolesentry_companies", 'openings.rolesentry_company_id','=','rolesentry_companies.id')
            ->join("locations", 'locations.id','=','openings.location_id')
            ->join("hiring_manager_openings", 'hiring_manager_openings.opening_id','=','openings.id')
            ->join("hiring_managers", 'hiring_managers.id','=','hiring_manager_openings.hiring_manager_id')
            ->select(DB::raw('openings.job_description_url, openings.job_description_url_on_job_board,rolesentry_companies.logo_url, openings.id as sl, job_types.name as job_type, job_types.id as job_type_id,  rolesentry_companies.name as company, rolesentry_companies.id as rolesentry_company_id, case when length(openings.title) > 50 then concat(LEFT(openings.title, 50), "...") else openings.title end as title, openings.created_at as created_at,locations.name as location, locations.id as location_id,date_format(openings.created_at, "%b %d") AS time_ago,  hiring_managers.name as hiring_manager_name, hiring_managers.hiring_manager_position as hiring_manager_position,  hiring_managers.linkedin_url as hiring_manager_linkedin, openings.manager_auto_detect as manager_auto_detect, hiring_managers.id as hiring_manager_id'))
        ->distinct()
        ->where('openings.is_deleted', 0)->take(10)->orderByRaw('DATE(openings.created_at) DESC');
        

         if(!empty($job_type_ids)) $alerts->whereIn('openings.job_type_id', explode(',', $job_type_ids));
         if(!empty($job_subtype_ids)) $alerts->whereIn('job_subtypes.id', explode(',', $job_subtype_ids));
         if(!empty($location_ids)) $alerts->whereIn('openings.location_id', explode(',', $location_ids));
         
         //$data["sql"] = $alerts->toSql(); 
         //$data["alerts"] = $alerts->get();

         return response()->json($alerts->get()); 

    }

    public function loadApprovalModal() {

	$prospect_saved_search_progress_ids = ProspectSavedSearchProgress::where('saved_search_id', $_REQUEST['id'])->get()->pluck('id');

	$neededApprovals = DB::table("approvals")->select(['prospect_saved_search_progresses.saved_search_id as saved_search_id','approvals.is_approved as is_approved','approvals.created_at as time_generated', 'rolesentry_companies.name as company', 'hiring_managers.name as name', 'email_subject as subject', 'email_message as message','approvals.id as id','hiring_managers.email as email','rolesentry_companies.logo_url as logo_url'])
				->join("prospect_saved_search_progresses", "approvals.prospect_saved_search_progress_id",'=','prospect_saved_search_progresses.id')
				->join("openings","prospect_saved_search_progresses.opening_id",'=','openings.id')
				->join("rolesentry_companies", "rolesentry_companies.id","=","openings.rolesentry_company_id")
				->join("prospects", "prospects.id","=", "prospect_saved_search_progresses.prospect_id")
				->join("hiring_managers", "hiring_managers.prospect_id", "=", "prospects.id")
				->whereIn('prospect_saved_search_progress_id', $prospect_saved_search_progress_ids)
				->whereNull('is_approved')
				->whereNull('is_rejected')
				->orderBy("approvals.created_at","ASC");


	$data['needed_approvals'] = $neededApprovals->paginate(5, ['*'], 'ssoeh');

       $data["success"] = true; 

	return response()->json($data);
	
    }

    public function approveApproval(){
	$approval_id = $_REQUEST['id'];
	$approval = Approval::find($approval_id);
	$approval->is_approved = 1;
	$approval->save();
    }

    public function editApproval() {
        $data["success"] = true; 
        $approval_id = $_REQUEST['id'];
        $approval = Approval::find($approval_id);
        $approval->email_message = $_REQUEST["email_message"];
        $approval->save();
        $data["success"] = true; 
        
        return response()->json($data);
    }

    public function rejectApproval(){
	$approval_id = $_REQUEST['id'];
	$approval = Approval::find($approval_id);
	$approval->is_rejected = 1;
	$approval->save();
    }

    public function loadSavedSearchHistory() {

       $data["success"] = false;
       $prospecting_type_id = @$_REQUEST["prospecting_type_id"]; 
       if(empty($prospecting_type_id)) $prospecting_type_id = 1;   
       //$data["saved_search_openings"]  = SavedSearchOpening::where("saved_search_id", $_REQUEST["id"])->get();

	$past_emails = DB::table("prospecting_actions")
				->join("prospect_saved_search_progresses", "prospect_saved_search_progresses.id", '=','prospecting_actions.prospect_saved_search_progresses_id')
                                ->join("openings","prospect_saved_search_progresses.opening_id",'=','openings.id')
                                ->join("rolesentry_companies", "rolesentry_companies.id","=","openings.rolesentry_company_id")
                                ->join("prospects", "prospects.id","=", "prospect_saved_search_progresses.prospect_id")
                                ->join("hiring_managers", "hiring_managers.prospect_id", "=", "prospects.id")
                                ->leftJoin("user_email_interactions", "user_email_interactions.email_id", "=", "prospecting_actions.id")
				->where("prospect_saved_search_progresses.saved_search_id", '=', $_REQUEST['id'])								
				->select(['rolesentry_companies.name as company', 'prospecting_actions.created_at as time_sent', 'hiring_managers.email as email', 'prospecting_actions.subject as subject', 'prospecting_actions.message as message','rolesentry_companies.logo_url as logo_url','hiring_managers.name as name',DB::raw('(select COUNT(id) from user_email_interactions where email_id = prospecting_actions.id) as opens')])
				->groupBy('prospecting_actions.id');

    $data["past_emails"] =  $past_emails->paginate(5, ['*'], 'ssoeh');

    $data["sql"] = $past_emails->toSql();   
       $data["success"] = true; 

        return response()->json($data);
    }


    public function loadHiringManagerHistory() {
        
               $data["success"] = false;
               $prospecting_type_id = @$_REQUEST["prospecting_type_id"]; 
               if(empty($prospecting_type_id)) $prospecting_type_id = 8;   
               //$data["saved_search_openings"]  = SavedSearchOpening::where("saved_search_id", $_REQUEST["id"])->get();
        
            $past_emails = DB::table("prospecting_actions")
                        ->join("prospect_saved_search_progresses", "prospect_saved_search_progresses.id", '=','prospecting_actions.prospect_saved_search_progresses_id')
                                        ->join("openings","prospect_saved_search_progresses.opening_id",'=','openings.id')
                                        ->join("rolesentry_companies", "rolesentry_companies.id","=","openings.rolesentry_company_id")
                                        ->join("prospects", "prospects.id","=", "prospect_saved_search_progresses.prospect_id")
                                        ->join("hiring_managers", "hiring_managers.prospect_id", "=", "prospects.id")
                                        ->leftJoin("user_email_interactions", "user_email_interactions.email_id", "=", "prospecting_actions.id")
                        ->where("prospecting_actions.prospect_id", '=', $_REQUEST['id'])								
                        ->select(['rolesentry_companies.name as company', 'prospecting_actions.created_at as time_sent', 'hiring_managers.email as email', 'prospecting_actions.subject as subject', 'prospecting_actions.message as message','rolesentry_companies.logo_url as logo_url','hiring_managers.name as name',DB::raw('(select COUNT(id) from user_email_interactions where email_id = prospecting_actions.id) as opens')])
                        ->groupBy('prospecting_actions.id');
        
            $data["past_emails"] =  $past_emails->paginate(5, ['*'], 'hmh');
        
            $data["sql"] = $past_emails->toSql();   
               $data["success"] = true; 
        
                return response()->json($data);
            }


    public function loadSavedSearches() 
    {        

    	$prospecting_type_id = $_REQUEST["prospecting_type_id"];    
        $data["success"] = false;       
        $saved_searches = DB::table("saved_search")->where('prospecting_type_id', $prospecting_type_id)->distinct()
        ->join("saved_search_job_type", 'saved_search_job_type.saved_search_id', '=', 'saved_search.id')        
        ->join("saved_search_location", 'saved_search_location.saved_search_id','=','saved_search.id')
	->join("locations", 'saved_search_location.location_id','=','locations.id')
	->join("job_types", 'saved_search_job_type.job_type_id','=','job_types.id')
        ->select(DB::raw('saved_search.name as name, saved_search.emails_sent as emails_sent, saved_search.last_email_sent as last_email_sent, saved_search.id as id, saved_search.term as term, job_types.name as job_type, job_types.id as job_type_id, locations.name as location, locations.id as location_id'))
    
    ->where('saved_search.user_id', Auth::id()); //Auth::id() ->take(5)

    if(!isset($_REQUEST["location"])) $_REQUEST["location"] = 1; 
    $job_type_ids = $_REQUEST["job_type"];
    $location_ids = $_REQUEST["location"];    
    
    if(!empty($job_type_ids)) $saved_searches->whereIn('saved_search_job_type.job_type_id', $job_type_ids);
    if(!empty($location_ids)) $saved_searches->whereIn('saved_search_location.location_id', $location_ids);

    //$saved_searches->groupBy("id");
    //$data["success"] = true;       
     $data["sql"] = $saved_searches->toSql();
        
      if ($saved_searches->count()) {
          $data["saved_searches"] = $saved_searches->orderByRaw('DATE(saved_search.created_at) DESC')->paginate(5, ['*'], 'ss');    //->get();
          $data["success"] = true;       
      }
        return response()->json($data);
    }


    public function loadSavedSearchItem() 
    {        
        //$data["saved_search"] = SavedSearch::with("job_type")->find($_REQUEST["id"]);

        $prospecting_type_id = 1; //$_REQUEST["prospecting_type_id"];    
        $data["success"] = false;       
        $data["saved_search"] = SavedSearch::join("saved_search_job_type", 'saved_search_job_type.saved_search_id', '=', 'saved_search.id')        
        ->join("saved_search_location", 'saved_search_location.saved_search_id','=','saved_search.id')
	->join("locations", 'saved_search_location.location_id','=','locations.id')
	->join("job_types", 'saved_search_job_type.job_type_id','=','job_types.id')
        ->select(DB::raw('saved_search.id, saved_search.name as name, saved_search.needs_approval, saved_search.email_subject, saved_search.email_body, saved_search.term, job_types.name as job_type, job_types.id as job_type_id, locations.name as location, locations.id as location_id'))
    
	    ->where('saved_search.id', '=',$_REQUEST["id"])->first(); //Auth::id() ->take(5)
	    $data["locations"] = DB::table("saved_search_location")->where("saved_search_id" , $_REQUEST["id"])->get();
	    $data["job_types"] = DB::table("saved_search_job_type")->where("saved_search_id" , $_REQUEST["id"])->get();

	$scheme_steps = SchemeStep::where('saved_search_id', $_REQUEST["id"])->get();

	foreach($scheme_steps as $scheme_step) {
        $scheme_step_lock_types = SchemeStepLockType::where('scheme_step_id', $scheme_step->id)->get();
        
        $scheme_step->email_subject = $this->transformVariablesToComplexText($scheme_step->email_subject);
        $scheme_step->email_body = $this->transformVariablesToComplexText($scheme_step->email_body);
        
		$locks = [];

		foreach($scheme_step_lock_types as $scheme_step_lock_type){
			$value_of_this_lock_type = SchemeStepLockTypeDetail::where('scheme_step_lock_types_id', $scheme_step_lock_type->id)->first();
			$locks[] = ['scheme_step_lock_type_id'=>$value_of_this_lock_type->scheme_step_lock_types_id,
						     'scheme_step_lock_type_detail_value'=>$value_of_this_lock_type->value];  
		} 

		$data['scheme_steps'][] = ['step'=>$scheme_step, 'locks'=>$locks];
		
	}	

        return response()->json($data);
    }

}
