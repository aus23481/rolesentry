<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Rolesentry_company;
use App\Job_type;
use App\Location;
use App\User_preference;
use App\User_preferences_subtype;
use DB;
use App\User;
use App\User_favorite_rolesentry_company;
use App\Requested_company;


class UserPreferencesController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = Auth::user();
        $jobtypes = Job_type::all();
        $data["platform"] = "userpref";
        //$locations = Location::whereIn('id',[1,5,15, 16])->get();

        $locations = Location::whereNotNull('show_in_preferences')->get();
        $favorite_companies = User_favorite_rolesentry_company::where("user_id",Auth::id())->get();
        $data["favorite_companies"] = $favorite_companies;
        $data['requested_companies'] = Requested_company::where("user_id",Auth::id())->get();

        $rscompanies = Rolesentry_company::all();
        $user_preferences = User_preference::where("user_id",Auth::id())->get();
        foreach( $user_preferences as $up) {
            $input = "up_".$up->job_type_id."_".$up->location_id;
            $data[$input] = 1;
        }

        $user_preferences_subtypes = User_preferences_subtype::where("user_id",Auth::id())->get();
        foreach( $user_preferences_subtypes as $ups) {
            $input = "up_".$ups->jobsubtype->job_type_id."_".$ups->location_id."_".$ups->job_subtype_id;
            $data[$input] = 1;
        }
        
        $data['jobtypes'] =  $jobtypes;
        $data['locations'] =  $locations;
        $data['rscompanies'] = $rscompanies;
        $data["new_opening_report"] = $user->new_opening_report;
        $data["hiring_manager_report"] = $user->hiring_manager_report;
        $data["high_value_role_report"] = $user->high_value_role_report;
        

        //print "Hello";
        return view("userpreferences", $data);
    }


    public function saveUserPreferences(Request $request)
    {
        //

       // print_r($_REQUEST);
        //die();
        $data["success"] = false;
        $jobtypes = Job_type::all();
        $locations = Location::all();
        //delete existing for current user
        User_preference::where("user_id",Auth::id())->delete();
        User_preferences_subtype::where("user_id", Auth::id())->delete();

        //print_r($_REQUEST);
        foreach($locations as $location) {
        
        //{{$jobtype->name}}
                foreach($jobtypes as $jobtype) 
                {
                    
                    $a =  "up_".$jobtype->id."_".$location->id;
                    if(!empty($request->get($a))) {

                        $up = new User_preference([
                            
                                        "user_id" => Auth::user()->id, 
                                        "job_type_id" => $jobtype->id,
                                        "location_id" => $location->id,
                                        "created_at" => date("Y-m-d H:i:s",time()),
                                        "updated_at" => date("Y-m-d H:i:s",time())                                        
                                    ]);                     
                        $up->save();  
                    } 

                    //subtype
                   foreach($jobtype->subtypes as $subtype) {
                    
                    $st =  "up_".$jobtype->id."_".$location->id."_".$subtype->id;
                    
                        if(!empty($request->get($st))) {
                            $ups = new User_preferences_subtype([                            
                                            "user_id" => Auth::user()->id, 
                                            "job_subtype_id" => $subtype->id,
                                            "location_id" => $location->id,                                                                                
                                        ]);                     
                            $ups->save();  
                        } 
                   } //subtype ends
                    
                } 
        
        } 
        $up = [];

        $user_preferences = User_preference::where("user_id", Auth::id())->get();
        $data["user_preferences"] = $user_preferences;
        $data["user_preferences_subtypes"] = User_preferences_subtype::where("user_id", Auth::id())->get();;

        /*foreach( $user_preferences as $up) {
            $input = "up_".$up->job_type_id."_".$up->location_id;
            $up[$input] = 1;
            $data[$input] = 1;
        }

        $data["up"] = $up;
        */
        $data["success"] = true;
        $data["message"] = "Successfully Saved!!!";

        //return redirect("userpref");
        return response()->json($data);
        
    }
    

    public function saveUserPreferencesbk(Request $request)
    {
        //
        $jobtypes = Job_type::all();
        $locations = Location::all();
        //delete existing for current user
        User_preference::where("user_id",Auth::id())->delete();

        //print_r($_REQUEST);
        foreach($locations as $location) {
        
        //{{$jobtype->name}}
                foreach($jobtypes as $jobtype) 
                {
                    
                    $a =  "up_".$jobtype->id."_".$location->id;
                    if(!empty($request->get($a))) {

                        $up = new User_preference([
                            
                                        "user_id" => Auth::user()->id, 
                                        "job_type_id" => $jobtype->id,
                                        "location_id" => $location->id,
                                        "created_at" => date("Y-m-d H:i:s",time()),
                                        "updated_at" => date("Y-m-d H:i:s",time())                                        
                                    ]);                     
                        $up->save();  
                    } 
                    
                } 
        
        } 

        return redirect("userpref");
        
    }



    public function saveFavoriteCompany()
    {
  
       $company = DB::table("rolesentry_companies")->where("name","=",$_REQUEST["name"])->first();
      // print $company->id."-kd";
       $ci = [];
       if($company !== null) {
        $fcexists = User_favorite_rolesentry_company::where('rolesentry_company_id', '=',  $company->id)->first();
        if ($fcexists === null) {   
                $fc = new User_favorite_rolesentry_company([
                
                            "user_id" => Auth::user()->id, 
                            "rolesentry_company_id" => $company->id,                    
                            "created_at" => date("Y-m-d H:i:s",time()),
                            "updated_at" => date("Y-m-d H:i:s",time())                                        
                        ]);                     
                $fc->save();
                $ci = Rolesentry_company::find($company->id);
                $nfc = User_favorite_rolesentry_company::find(User_favorite_rolesentry_company::max('id'));
                $ci["fc_id"] = $nfc->id;
            }        
        }
        return response()->json($ci);        
    }


    public function saveRequestedCompany()
    {
  
       $company = DB::table("requested_companies")->where("company_text_name","=",$_REQUEST["name"])->first();
      // print $company->id."-kd";
       $rci = [];
       if($company === null) {
                $rc = new Requested_company([
                
                            "user_id" => Auth::user()->id, 
                            "company_text_name" => $_REQUEST["name"],                    
                            "created_at" => date("Y-m-d H:i:s",time()),
                            "updated_at" => date("Y-m-d H:i:s",time())                                        
                        ]);                     
                $rc->save();
                $rci = Requested_company::find(Requested_company::max('id'));
                
         }        
        return response()->json($rci);        
    }


    public function deleteFavoriteCompany()
    {
        $ci = User_favorite_rolesentry_company::find($_REQUEST['id'])->delete(); 
        $favorite_companies = User_favorite_rolesentry_company::select(["user_favorite_rolesentry_companies.id","rolesentry_companies.name"])
        ->join('rolesentry_companies','rolesentry_companies.id','=', 'user_favorite_rolesentry_companies.rolesentry_company_id')                   
        ->where("user_favorite_rolesentry_companies.user_id",Auth::id())->get(); 
        //print_r($favorite_companies); toSql()
        //dd($favorite_companies);
       return response()->json($favorite_companies);        
    }


    public function deleteRequestedCompany()
    {
        $requested_companies = Requested_company::find($_REQUEST['id'])->delete(); 
        $requested_companies = Requested_company::where("user_id",Auth::id())->get();
        
       return response()->json($requested_companies);        
    }
    
    public function updateUserSetting() 
    {
        $user = User::find(Auth::id());
        //print $user->id;
        //#new_opening_report,#hiring_manager_report,#high_value_role_report
        if(isset($_REQUEST["new_opening_report"]))
             $user->new_opening_report = $_REQUEST["new_opening_report"];

         if(isset($_REQUEST["hiring_manager_report"]))
            $user->hiring_manager_report = $_REQUEST["hiring_manager_report"];
         
         if(isset($_REQUEST["high_value_role_report"]))
            $user->high_value_role_report = $_REQUEST["high_value_role_report"];

        return   response()->json($user->save());  

    }
}
