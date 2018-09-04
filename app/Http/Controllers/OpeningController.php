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
use App\opening;

use DB;
use App\Mail\AlertEmail;
use App\Location;
use Mail;

class OpeningController extends Controller
{
    //

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
    
     public function getOpenings()
    {
        //
        if(isset($_REQUEST["sort"])) {

        $openings_not_approved = Opening::where('approved', 0)->where('skipped',0)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->orderByRaw("FIELD(location_id,".$_REQUEST["location_id"]." )","DESC")->orderByRaw("FIELD(job_type_id,".$_REQUEST["job_type_id"]." )", "DESC")->paginate(50, ['*'], 'not_approved'); //
        $openings_approved = Opening::where('approved', 1)->where('skipped',0)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->orderByRaw("FIELD(location_id,".$_REQUEST["location_id"]." )","DESC")->orderByRaw("FIELD(job_type_id,".$_REQUEST["job_type_id"]." )", "DESC")->paginate(50, ['*'], 'approved');
        $openings_skipped = Opening::where('approved', 0)->where('skipped',1)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->orderByRaw("FIELD(location_id,".$_REQUEST["location_id"]." )","DESC")->orderByRaw("FIELD(job_type_id,".$_REQUEST["job_type_id"]." )", "DESC")->paginate(50, ['*'], 'skipped');
         } 
        else {

        $openings_not_approved = Opening::where('approved', 0)->where('skipped',0)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->paginate(50, ['*'], 'not_approved'); //
        $openings_approved = Opening::where('approved', 1)->where('skipped',0)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->paginate(50, ['*'], 'approved');
        $openings_skipped = Opening::where('approved', 0)->where('skipped',1)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->paginate(50, ['*'], 'skipped');
    
        }
        //print_r($openings_not_approved);

        $data["openings_not_approved"] = $openings_not_approved;
        $data["openings_approved"] = $openings_approved;
        $data["openings_skipped"] = $openings_skipped;        
        $data["job_types"] = Job_type::all(); 
        $data["locations"] = Location::all(); 

        return view("openings", $data);
    }


    public function editOpening()
    {
        //
        $opening = Opening::find($_REQUEST["id"]);
        $data["job_types"] = Job_type::all(); 
        $data["locations"] = Location::all(); 
        $data["companies"]  = Rolesentry_company::all();
        //print_r($openings_not_approved);

        $data["opening"] = $opening;
 
        return view("opening-edit", $data);
    }

    public function saveOpeningData(Request $request)
    {
        //        
        $opening = Opening::find($request->get("id"));
        
        if(isset($_REQUEST["hiring_manager_name"]))
        $opening->hiring_manager_name = $request->get("hiring_manager_name");

        if(isset($_REQUEST["hiring_manager_position"]))
        $opening->hiring_manager_position = $request->get("hiring_manager_position");

        if(isset($_REQUEST["hiring_manager_linkedin"]))
        $opening->hiring_manager_linkedin = $request->get("hiring_manager_linkedin");

        if(isset($_REQUEST["title"]))
        $opening->title = $request->get("title");

        if(isset($_REQUEST["job_description"]))
        $opening->job_description = $request->get("job_description");

        if(isset($_REQUEST["job_type_id"]))
        $opening->job_type_id = $request->get("job_type_id");

        if(isset($_REQUEST["location_id"]))
        $opening->location_id = $request->get("location_id");

        if(isset($_REQUEST["rolesentry_company_id"]))
        $opening->rolesentry_company_id = $request->get("rolesentry_company_id");


        if(isset($_REQUEST["approve"])) { 
            $opening->skipped = 0; 
            $opening->approved = 1;            
        }

        if(isset($_REQUEST["skip"])) { 
            $opening->skipped = 1; 
            $opening->approved = 0; 
        }
        
        $opening->save();
        //next loading       
         $opening = Opening::where('approved', 0)->where('skipped',0)->where('created_at', '>=', date('Y-m-d').' 00:00:00')->first();
         $data["companies"]  = Rolesentry_company::all();
         $data["job_types"] = Job_type::all(); 
         $data["locations"] = Location::all(); 
        //print_r($openings_not_approved);

         $data["opening"] = $opening;
       // print_r($opening);
         return view("opening-edit", $data);
    }
}
