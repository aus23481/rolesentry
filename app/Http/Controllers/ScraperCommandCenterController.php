<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use DB;

class ScraperCommandCenterController extends Controller
{
    private function logScrape($function, $response, $ip){
	DB::table('rolesentry_scrape_log')->insert(['function'=>$function, 
						'response'=>$response,
						'ip'=>$ip]);
    }

    public function showActivity($offset, $limit) {
	$activity = DB::table('rolesentry_scrape_log')->orderBy('created_at', 'DESC')->skip($offset)->take($limit)->get();
	return $activity->toJson();	
    }
	

	/**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getScrapeSessionParameters()
    {

	$company_to_scrape = DB::table('rolesentry_companies')->orderBy('last_scrape','ASC')->where('legacy', '=', 1)->first();

	DB::table('rolesentry_companies')
		->where('id', $company_to_scrape->id)
		->update(['last_scrape'=> date("Y-m-d H:i:s")]);

	$response = [];
	$response['id'] = $company_to_scrape->id;
	$response['name'] = $company_to_scrape->name;
	$response['career_page_url'] = $company_to_scrape->career_page_url;
	$response['xpath'] = $company_to_scrape->xpath;
	$response['needs_custom'] = $company_to_scrape->needs_custom;
	$response['first_scrape'] = $company_to_scrape->first_scrape;
	
	$response['ip'] = \Request::ip();	

	$responseJSON = json_encode($response);

	self::logScrape('get_scrape_session_parameters', $responseJSON, $response['ip']);

	return $responseJSON;
    }
}
