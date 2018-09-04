<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DOMDocument;
use App\Rolesentry_company;
use App\Rolesentry_job;
use App\Rolesentry_alert;
use DB;

class CompanyController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $companies = Rolesentry_company::paginate(50, ['*'], 'company');
        $data['companies'] = $companies;
        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;

        return view("company", $data);

    }

    public function getCompanyDetail()
    {
        //
        $company = Rolesentry_company::find($_REQUEST['id']);
        $data['company'] = $company;

        $companies = $this->common->getRightSidebarCompanies();
        $data['companies'] = $companies;

        return view("company-detail", $data);

    }

    

}
