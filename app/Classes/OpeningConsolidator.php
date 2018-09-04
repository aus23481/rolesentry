<?php 

namespace App\Classes {

use App\Classes\OpeningConsolidator;
use App\Opening;
use App\NextEmail;
use App\EmailOpening;
use App\Scrape_google;
use App\Rolesentry_company;
use DB;
use Auth;
use DOMDocument;

class OpeningConsolidator 
{

	public static function CreateOrGetOpening($job_title, $company, $job_description_url, $job_description_url_on_job_board, $full_description, $location_id, $job_type_id) 
	{
		$existing_opening = opening::where('rolesentry_company_id', '=', $company)
		->where('location_id', '=', $location_id)
		->where('title', '=', $job_title)		
		->first();


		if ($existing_opening == null) {

			$reports_pos = false;
			$matching_text = "";
			$manager_auto_detect_all_text = "";

			if (isset($full_description)) {
				$positions = [];
				
				$reports_to_text_position = stripos($full_description, 'reports to ');
				$reporting_to_text_position = stripos($full_description, 'reporting to ');
				$report_to_text_position = stripos($full_description, 'report to ');

				if ($reports_to_text_position > 0){
						$reports_pos = $reports_to_text_position;
						$matching_text = "reports to ";
				}
				else if ($reporting_to_text_position > 0){
				echo $reporting_to_text_position;
						$reports_pos = $reporting_to_text_position;
						$matching_text = "reporting to ";
				}
				else if ($report_to_text_position > 0){
						$reports_pos = $report_to_text_position;
						$matching_text = "report to ";
				}

				if(!empty($matching_text)) {	
					$positions = self::strpos_all($full_description, $matching_text);					
					if(count($positions)) {						
						foreach ($positions as $position) {
							$manager_auto_detect_all_text .= substr($full_description, $position, 50)." <br /> ";						
						}
					}
				}	
			}

			
			if(!empty($manager_auto_detect_all_text))
				$manager_auto_detect = $reports_pos ? $manager_auto_detect_all_text : "";
			else 
				$manager_auto_detect = $reports_pos ? substr($full_description, $reports_pos, 50) : "";			
				
			$opening =  opening::create([
			'location_id' => $location_id,
			'job_type_id' => $job_type_id, 
			'rolesentry_company_id' => $company,
			'title' => $job_title, 
			'job_description_url' => $job_description_url,
			'job_description_url_on_job_board' => $job_description_url_on_job_board,
			'job_description' => $full_description,
			'manager_auto_detect' => htmlspecialchars(utf8_encode(addslashes($manager_auto_detect))),
			'created_at' => date("Y-m-d H:i:s",time()),
			'updated_at' => date("Y-m-d H:i:s",time())
			]);

			$rolesentry_company = Rolesentry_company::find($company);

			if(!empty($reports_pos) && !empty($manager_auto_detect)) {
				Scrape_google::create([
					'title' => str_replace($matching_text, "", strip_tags($manager_auto_detect)),
					'company_name' => $rolesentry_company->name,
					'first_google_result' => '',
					'first_google_result_text' => '',
					'opening_id' => $opening->id					
				]);
			}	

			return $opening->id;
		} 
		else 
			return $existing_opening->id;

	} //end of CreateOrGetOpening function

	public static function mergeOpenings($id = 293) 	
	{
		$opening = Opening::find($id);
		$primary_opening = $opening;
		$possible_duplicate_openings = Opening::where('title', $opening->title )->where('rolesentry_company_id', $opening->rolesentry_company_id)->get(); //look for similar opening.title, and opening.rolesentry_company_id

		foreach($possible_duplicate_openings as $possible_duplicate_opening) 
		{
			if ($opening->job_description_url == $possible_duplicate_opening->job_description_url) 
			{
				if($primary_opening->id != $possible_duplicate_opening->id)
				$possible_duplicate_opening->delete();
				//must find all places this opening was used, and update the opening_id to the primary_opening
				$next_emails_to_update = NextEmail::where('opening_id', $possible_duplicate_opening->id)->get();
				foreach($next_emails_to_update as $next_email_to_update){
						$next_email_to_update->opening_id = $primary_opening->id;
						$next_email_to_update->save();
				}

				$email_openings_to_update = EmailOpening::where('opening_id', $possible_duplicate_opening->id)->get();
				foreach($email_openings_to_update as $email_opening_to_update){
						$email_opening_to_update->opening_id = $primary_opening->id;
						$email_opening_to_update->save();
				}
				//I think it’s just next_email and email_openings we need to update, but maybe more in future
			} //if
		} //end foreach
   } //end of CreateOrGetOpening function

  public static function strpos_all($haystack, $needle) 
   {
		$offset = 0;
		$allpos = array();
		while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
			$offset   = $pos + 1;
			$allpos[] = $pos;
		}
		return $allpos;
}

}

}