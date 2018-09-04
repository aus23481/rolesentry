<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AllStartupsScraperController extends Controller
{
    	/**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getScrapeSessionParameters()
    {

		$page_to_scrape = DB::table('allstartups_last_page_scraped')->first();
	


		DB::table('allstartups_last_page_scraped')
		->increment('last_page_scraped');
	
		$response = [];
	
			
		
		$response['last_page_scraped'] = $page_to_scrape->last_page_scraped;		

		$responseJSON = json_encode($response);

		
	return $responseJSON;
	}
	
	public function getResultsFromNodeServer($career_page_url, $xpath, $url_for_node_server) {
		
		$response = @file_get_contents($url_for_node_server . '?page=' . urlencode($career_page_url) . '&selector=' . urlencode($xpath));
		return json_decode($response);
	}
	
}
