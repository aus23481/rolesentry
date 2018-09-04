<?php

namespace App\Http\Controllers;

use App\User;
use App\Http\Controllers\Controller;
use DB;

class OwlerScraperCommandCenterController extends Controller
{
	/**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return Response
     */
    public function getScrapeSessionParameters()
    {

	$location_page_to_scrape = DB::table('owler_scraper_location_progresses')->where('location_name', '=', $_REQUEST["location"])->first();
	$main_location = DB::table('locations')->where("owler_location_name", $location_page_to_scrape->location_name)->first();

	DB::table('owler_scraper_location_progresses')
		->where('id', $location_page_to_scrape->id)
		->increment('last_page_scraped');

	$response = [];
	$response['page'] = $location_page_to_scrape->last_page_scraped;
	$response['location_id'] = $main_location->id;

	$response['city'] = ucwords(str_replace("_"," ",$location_page_to_scrape->location_name));
		

	$responseJSON = json_encode($response);
	return $responseJSON;
    }
}
