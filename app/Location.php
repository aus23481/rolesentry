<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\email_openings;
use DB;
class Location extends Model
{
    //
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];

    public static function last_email_time($location_id) {
	
		$last_emailed_opening_from_this_location = email_openings::join('openings','opening_id','=','email_opening.opening_id')
		->join('locations','locations.id','=','openings.location_id')
		->where('locations.id','=',$location_id)
		->orderBy('openings.created_at','DESC')
		->first();		

		if ($last_emailed_opening_from_this_location){
			return $last_emailed_opening_from_this_location->created_at;
		}

		return '2018-04-25 19:00:35';

    }

    public static function getLastEmailTimesAllLocations($location_id = NULL) {
		$allLocations = Location::select(['location_id', DB::raw('MAX(openings.created_at) as last_email')])
					->join('openings', 'openings.location_id','=','locations.id')
					->join('email_openings', 'email_openings.opening_id','openings.id')
					->groupBy('location_id');

		if ($location_id){
			$allLocations->where('locations.id','=',$location_id);
		}

		$locationsLastEmails = [];

		$allLocations = $allLocations->get();

		foreach($allLocations as $location) {
			$locationsLastEmails[$location->location_id] = $location->last_email;
		}

		if ($location_id){
			if (isset($locationsLastEmails[$location_id])){
				return $locationsLastEmails[$location_id]; 
			}
			else{
				return '2018-04-25 19:00:35';
			}
		}

		return $locationsLastEmails;
    }

}
