<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class GustScraperController extends Controller
{
    	/**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getScrapeSessionParameters()
    {

		$location_to_scrape = DB::table('gust_locations')->orderBy('last_scrape','ASC')->first();
		$main_location = DB::table('locations')->where("gust_location_name", $location_to_scrape->name)->first();

		DB::table('gust_locations')
			->where('id', $location_to_scrape->id)
			->update(['last_scrape'=> date("Y-m-d H:i:s")]);

		DB::table('gust_locations')
		->where('id', $location_to_scrape->id)
		->increment('last_page_scraped');
	
		$response = [];
	
			
		$response['id'] = $location_to_scrape->id;
		$response['location_id'] = $main_location->id;
		$response['page'] = $location_to_scrape->last_page_scraped;	
		$response['name'] = $location_to_scrape->name;		

		$responseJSON = json_encode($response);

		
	return $responseJSON;
	}
	
	public function getResultsFromNodeServer($career_page_url, $xpath, $url_for_node_server) {
		
		$response = @file_get_contents($url_for_node_server . '?page=' . urlencode($career_page_url) . '&selector=' . urlencode($xpath));
		return json_decode($response);
	}
	
}
