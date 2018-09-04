<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Input;
use Session;

use App\Rolesentry_company;
use App\Robot_company;
use Auth;

class CommonController extends Controller
{
    
    public $data = [];
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        session(['without_website_count' =>""]);
        session(['without_careerpage_count' =>""]);

        $this->robotCompanyCount(1);
        $this->robotCompanyCount(2);
        $this->robotCompanyCount(3);
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


    public function getRightSidebarCompanies() 
    {
        $activities = Rolesentry_company::orderBy('created_at', 'desc')->paginate(50, ['*'], 'activity');
        $activities->setPageName('company');
        return $activities;             
    }
    
    public function robotCompanyCount($type=1) 
    {

        if ($type == 1) {
            $without_website_count = Robot_company::where("website_status_id", 2)->get()->count();    
            session(['without_website_count' => $without_website_count]);
        }
    
           if ($type == 2) {
            $without_career_page_count = Robot_company::where("career_page_status_id", 2)->get()->count();    
            session(['without_careerpage_count' => $without_career_page_count]);
           }
    
           if ($type >= 3) {
            $all_robot_company_count = Robot_company::count();
            session(['all_robot_company_count' => $all_robot_company_count]);    
           }
 
    }
    
}
