<?php 



namespace App\Classes {


use App\Classes\RobotHelper;
use App\Robot_company;
use App\Rolesentry_company;
use App\Robot_log;
use App\Positions_found_with_key_selector;
use App\Robot_company_progression_status;
use DB;
use Auth;
use  DOMDocument;

    class RobotHelper {

	public static function getDeduped($raw_company_name)
	{
		$deduped_company_name = self::dedupeString($raw_company_name, 1);
		$fully_deduped_company_name = self::dedupeString($deduped_company_name, 2);
		return $fully_deduped_company_name;
	}

	public static function dedupeString($string, $pass = 1)
	{
		$string = str_replace("&apos;", "'", $string);
		$string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');
		$string = strtoupper($string);
		$string = preg_replace('/[^A-Za-z0-9 &]/', ' ', $string);

		static $cdws;
		
		if (!$cdws) {
			$cdws = DB::table("company_extra_text")->get();
		}

		static $words = [];
		static $replacements = [];
		if (!isset($words[$pass])) {

			$words[$pass] = [];
			$replacements[$pass] = [];
			foreach ($cdws as $cdw) {

				if ($cdw->active && $pass >= $cdw->pass) {
					// add boundary condition to word
					$words[$pass][]        = '/\b' . strtoupper(preg_quote($cdw->word, '/')) . '\b/i';
					$replacements[$pass][] = $cdw->replace;
				}
			}
		}

		// remove/replace words from string
		$string = preg_replace($words[$pass], $replacements[$pass], $string);

		$last3 = substr($string, -3);
		$last4 = substr($string, -4);

		if ($last4 == "CORP" || $last4 == "INTL"){
			$string = str_replace("CORP", "", $string);
			$string = str_replace("INTL", "", $string);
		}


		if ($last3 == "LLC" || $last3 == "INC" || $last3 == "LTD"){
			$string = str_replace("LLC", "", $string);
			$string = str_replace("INC", "", $string);
			$string = str_replace("LTD", "", $string);
		}


		$string = str_replace(" ", "", $string);	
		return $string;
	}

        public function AddRobotCompanyByName() {

            return 'It is OK';

        }

        function findCompanyWebsite($company_name) 
        {
           	 
                //this code is used by both the ROBOT CMS, and the RabbitMQ system 
            
        } 

	public function findKeySelector($career_page_url) {
		$words = ['ngineer','oftware','anager','eporter', 'enior', 'ackend','rontend','ntern','ssociate']; //Words in most tech job descriptions, without first letter
		$results = $this->getResultsFromNodeServer($career_page_url, $words);
		//print $results;
		$word_count = 0;
		$result_set = [];
		if (!$results){
		 return false;
		}
		foreach($results as $result_for_word){
			
			$path_and_word_results = [];
			foreach($result_for_word as $path_and_text_result){
				$path = $path_and_text_result[0];
				$text = $path_and_text_result[1];
				$path_and_word_results[] = ['path'=>$path, 'text'=>$text];
			}
			$result_set[] = [$words[$word_count] => $path_and_word_results];
			$word_count++;
		}

		$clean_paths = [];

		foreach($result_set as $word ){

			$actual_word = key($word);		

			foreach ($word[$actual_word] as $path_details) {

				$path_details['path'] = str_replace("[0]",'', $path_details['path']);
				$path_details['path'] = str_replace("[1]",'', $path_details['path']);
				$path_details['path'] = str_replace("[2]",'', $path_details['path']);
				$path_details['path'] = str_replace("[3]",'', $path_details['path']);
				$path_details['path'] = str_replace("[4]",'', $path_details['path']);
				$path_details['path'] = str_replace("[5]",'', $path_details['path']);
				$path_details['path'] = str_replace("[6]",'', $path_details['path']);
				$path_details['path'] = str_replace("[7]",'', $path_details['path']);
				$path_details['path'] = str_replace("[8]",'', $path_details['path']);
				$path_details['path'] = str_replace("[9]",'', $path_details['path']);
				$path_details['path'] = str_replace("[10]",'', $path_details['path']);
				$path_details['path'] = str_replace("[11]",'', $path_details['path']);
				$path_details['path'] = str_replace("[12]",'', $path_details['path']);
				$path_details['path'] = str_replace("[13]",'', $path_details['path']);
				$path_details['path'] = str_replace("[14]",'', $path_details['path']);
				$path_details['path'] = str_replace("[15]",'', $path_details['path']);
				$path_details['path'] = str_replace("[16]",'', $path_details['path']);
				$path_details['path'] = str_replace("[17]",'', $path_details['path']);
				$path_details['path'] = str_replace("[18]",'', $path_details['path']);
				$path_details['path'] = str_replace("[19]",'', $path_details['path']);
				$path_details['path'] = str_replace("[20]",'', $path_details['path']);
				$path_details['path'] = str_replace("[21]",'', $path_details['path']);
				$path_details['path'] = str_replace("[22]",'', $path_details['path']);

				$exists = false;
				$counter = 0;
				foreach($clean_paths as $clean_path){
					if ($clean_path['path'] == $path_details['path']){
						$exists = true;break;
					}
					$counter++;
				}

				if ($exists){
					$clean_paths[$counter]['count_matched_words']++;
					$clean_paths[$counter]['count_matched_words']+=strlen($text);


					$clean_paths[$counter]['ratio'] = $this->calculateRatio($clean_paths[$counter]);
				}
				else{

					//echo $path_details['path'] .'not found in ';
					//print_r($clean_paths);
					sleep(1);

					$new_path = [];
					
					$new_path['path'] = $path_details['path']; 
					$new_path['count_total_characters'] = strlen($path_details['text']); 
					$new_path['count_matched_words'] = 1;
					$new_path['ratio'] = $this->calculateRatio($new_path);

					array_push($clean_paths, $new_path);

				}		
			}

			
		}

		usort($clean_paths, function($item1, $item2){

		    if ($item1['ratio'] == $item2['ratio']) return 0;
		    return ($item1['ratio'] < $item2['ratio']) ? 1 : -1; 

		});

		if (!empty($clean_paths)){
			$key_selector = $clean_paths[0]['path'];
	//id("content")/DIV/DIV/DIV/SECTION/DIV[16]/UL/LI/SPAN/A
		
			
			$key_selector_js = str_replace('/',' > ',$key_selector);
			
			$key_selector_js = str_replace('id("','#',$key_selector_js);
			$key_selector_js = str_replace('")','',$key_selector_js);
			$url_for_node_server  =  config('app.nodeserver_url') . ':3002';
			//config('app.scraperurl')			

			$job_roles = json_decode(@file_get_contents($url_for_node_server . '?page=' . urlencode($career_page_url) . '&selector=' . urlencode($key_selector_js)));
	
			$company = Robot_company::where("career_page",$career_page_url)->first();
			if ($company){
				if ($job_roles){
					
					$positions_found = Positions_found_with_key_selector::where("robot_company_id", $company->id)->delete();

					foreach($job_roles as $job_role){
						if (strlen($job_role) < 200){
							Positions_found_with_key_selector::create(["position_title" => $job_role, "robot_company_id"=> $company->id]);
						}
						//echo substr($job_role, 0, strpos($job_role, "9999x9999")) . PHP_EOL;
					}
					if (count($job_roles) > 0){
						return $key_selector_js;
					}
				}
			}
		}
		return false;
	}


        public function calculateRatio($path) {
		return $path['count_matched_words']/$path['count_total_characters'];
	}

	public function invenDescSort($item1,$item2)
	{
	        if ($item1['ratio'] == $item2['ratio']) return 0;
	        return ($item1['ratio'] < $item2['ratio']) ? 1 : -1;
	}

	public function getResultsFromNodeServer($career_page_url, $words) {
		$url_for_node_server =  config('app.nodeserver_url') . ':3010/';
		//echo $url_for_node_server;
		$request = $url_for_node_server . '?career_page=' . urlencode($career_page_url) . '&words=' . urlencode(implode($words,","));

		//echo $request. '0000000000000000000000000000000000000000000000000';

		$response = @file_get_contents($request);
		return json_decode($response);

	}


	public function findCareerPageByTryingJobsiteUrls($company_name) {
		$jobsite_company_name = str_replace(" ","",strtolower($company_name));

		$jobsite = "https://jobs.lever.co/".$jobsite_company_name;
		if ($this->doesURLExistAndWhereDoesItForwardTo($jobsite)) {
			return $jobsite;			
		}
//echo '1';
		$jobsite = "https://boards.greenhouse.io/".$jobsite_company_name."#.WqoGbbC-nIV";
		if ($this->doesURLExistAndWhereDoesItForwardTo($jobsite)) {
			return $jobsite;			
		}

//echo '1';
		$jobsite = "https://".$jobsite_company_name.".workable.com";
		if ($this->doesURLExistAndWhereDoesItForwardTo($jobsite)) {
			return $jobsite;			
		}
//		echo '1';
		
	}


	public function findCareerPageByLookingForLinks($pageToLookForJobLinksOn) {
		$arrContextOptions=array(
			"ssl"=> [
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			],
		);

		$pageToLookForJobLinksOnEditted = $pageToLookForJobLinksOn;

		$html = @file_get_contents($pageToLookForJobLinksOnEditted, false, stream_context_create($arrContextOptions));
		
		if(empty($html))
		$html = @file_get_contents("http://".$pageToLookForJobLinksOnEditted, false, stream_context_create($arrContextOptions));

		if(empty($html))
		$html = @file_get_contents("https://".$pageToLookForJobLinksOnEditted, false, stream_context_create($arrContextOptions));

		if(empty($html))
		$html = @file_get_contents("http://www".$pageToLookForJobLinksOnEditted, false, stream_context_create($arrContextOptions));
		
		if(empty($html))
		$html = @file_get_contents("https://www".$pageToLookForJobLinksOnEditted, false, stream_context_create($arrContextOptions));

		/*if (!stripos($pageToLookForJobLinksOnEditted, 'http')){
			$pageToLookForJobLinksOnEditted = 'http://'.$pageToLookForJobLinksOnEditted;
		}

		try {
			//$html = file_get_contents($pageToLookForJobLinksOnEditted, false, stream_context_create($arrContextOptions));
			$html =""; //wasn't working
		}
		catch(ErrorException $e) {
			  if (!stripos($pageToLookForJobLinksOnEditted, 'www')){
				$pageToLookForJobLinksOnEditted = 'www.'.$pageToLookForJobLinksOn;
		                if (!stripos($pageToLookForJobLinksOnEditted, 'http')){
					$pageToLookForJobLinksOnEditted = 'http://'.$pageToLookForJobLinksOnEditted;
				}
			} 	
			$html = @file_get_contents($pageToLookForJobLinksOnEditted, false, stream_context_create($arrContextOptions));
		}
		*/

		if(stripos($html, "\">Work At") !==false) {

			$link_text = "Work At";
			$link = $this->getLinkFromText($html, $link_text);
			return $link;
		}
		if(stripos($html, "\">Jobs") !==false) {

			$link_text = "Jobs";
			$link = $this->getLinkFromText($html, $link_text);
			return $link;
		}
		if(stripos($html, "\">Careers") !==false) {
			
			$link_text = "Careers";
			$link = $this->getLinkFromText($html, $link_text);

			return $link;
		}
	}

	public function findRealCareerPageThroughLinksOnAssumedCareerPage($assumed_career_page) {
		//Look for linked with text 'View All Openings', 'See All Openings', 'Open Positions', 'Open Roles'
		
		$link_text = ["View Openings", "View All Openings", "See All Openings", "Open Positions", "Open Roles", "openings", "jobs"];
		$arrContextOptions=array(
			"ssl"=> [
				"verify_peer"=>false,
				"verify_peer_name"=>false,
			],
		);
		$html = @file_get_contents($assumed_career_page, false, stream_context_create($arrContextOptions));
			
		$link = "";
        $text_string = "";

        foreach($link_text as $text) {
            
            $text_string = $text;
            $link = $this->getLinkFromText($html, $text);
            if (!empty($link)) break;

        }
		
		//if can't find anything, then just look for any link with the word "openings" or "jobs"
	
		return $link;
	}

	public function addCareerPageToRobotCompany($robot_company_id) 
	{
		Robot_log::create(['robot_action_type_id'=>3, 'robot_company_id'=> $robot_company_id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Trying to find career page']);
		$robotCompany = Robot_company::find($robot_company_id);

		if (!$robotCompany){
			return false;
		}

		$career_page_found_by_trying_urls = $this->findCareerPageByTryingURLs($robotCompany->website);
		$career_page_found_by_trying_jobsite_urls = $this->findCareerPageByTryingJobsiteUrls($robotCompany->company_name);
		$career_page_found_by_trying_links_on_webpage = $this->findCareerPageByLookingForLinks($robotCompany->website);	

		$career_page = false;

		if ($career_page_found_by_trying_urls) {
			$career_page = $career_page_found_by_trying_urls;
		}

		else if ($career_page_found_by_trying_links_on_webpage) {
			$career_page = $career_page_found_by_trying_links_on_webpage;
		}

		else if ($career_page_found_by_trying_jobsite_urls) {
			$career_page = $career_page_found_by_trying_jobsite_urls;
		}
	


		if ($career_page){

			if(!is_string($career_page)) { return false;} 

			$robotCompany->career_page = $career_page;
			$robotCompany->career_page_status_id = 1;
			$robotCompany->save();
                	Robot_log::create(['robot_action_type_id'=>3, 'robot_company_id'=> $robot_company_id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Found '. $career_page .' for ' . $robotCompany->name]);
			return $career_page;
		}
		else{
			$robotCompany->career_page_status_id = 3;
			$robotCompany->save();
                	Robot_log::create(['robot_action_type_id'=>3, 'robot_company_id'=> $robot_company_id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Unable to find career page for website: '.$robotCompany->website]);
		}

		return false;

	}

	public function findCareerPageByTryingURLs($domain) {

		//Try to find career page by trying many different URLs

		//echo "Trying to find career page for $domain";


		if ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/careers')){
			return ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/careers'));
		}
		if ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/jobs')){
			return ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/jobs'));
		}
		if ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/join')){
			return ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/join'));
		}
		if ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/about/careers')){
			return ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/about/careers'));
		}

		if ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/careers')){
			return ($this->doesURLExistAndWhereDoesItForwardTo($domain . '/careers'));
		}

		//dd($this->doesURLExistAndWhereDoesItForwardTo($domain . '/about/careers'));

		//if we cannot find career page, return false

		return false;	

	}

	public function doesURLExistAndWhereDoesItForwardTo($url){
		//echo "checking $url";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, false);    // we want headers
		curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);

		curl_exec($ch);
		
		$target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);


		curl_close($ch);
		return ($httpcode == 200 ? $target : false);
	}

	public function guessWebsite($company_name) {
		$clean_name = $this->getDeduped($company_name);
		return $this->doesURLExistAndWhereDoesItForwardTo($clean_name.".com");
	}

        public function nameToDomain($name) 
        {


            $ch = curl_init('https://company.clearbit.com/v1/domains/find?name='.urlencode($name));
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer sk_bc7d384f49c0de68ddd2c586d37a4cd2']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT,10);
            $output = json_decode(curl_exec($ch));

            curl_close($ch);
            if (isset($output->domain)){
                return $output->domain;
            }
            else{
                return false;
            }
            
        }

	public function saveCompany($company_name, $source_id, $location_id=26) {
		$company_exists = Robot_company::where('company_name', '=', addslashes($company_name))->first();
		if($company_exists === null) {
			$company = Robot_company::create([
					"company_name" => $company_name,
					"source_id" => $source_id,
					"website" => "",
					"website_status_id" => 1,
					"career_page" => "",
					"career_page_status_id" => 2,
					'created_at' => date("Y-m-d H:i:s",time()),
					'updated_at' => date("Y-m-d H:i:s",time()),
					'location_id' => $location_id
				    ]);

		    if($source_id>=1 || $source_id <=3)
		    {
			Robot_log::create(['robot_action_type_id'=>1, 'robot_company_id'=> $company->id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Trying to find website for ' . $company_name]);
			$website = $this->nameToDomain($company_name);

			if(!empty($website)) {
			  $company->website = $website;
			  $company->website_status_id = 2;
			  $company->save();
			  Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> $company->id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Found website']);
			  return $company->id;

			} 
			else {

			  $website = $this->guessWebsite($company_name);
			  if ($website){
				  $company->website = $website;
				  $company->website_status_id = 2;
				  $company->save();
				  Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> $company->id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Found website']);
				  return $company->id;

			  }
			  else{
				  Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> $company->id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Could not find website']);
				  $company->website_status_id = 3;
				  $company->save();
				  return FALSE;
			  
			  }
			}
		    }
		    else{
			    Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> $company->id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Unidentified Source']);
			    return FALSE;
		    }
		}
		else{
		    Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> 0, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Company name already exists in robot_companies']);
		    return FALSE;	
		}
	}

	//save company by website

	public function addCompanyByWebsite($company_name, $source_id, $website) {
		$company_exists = Robot_company::where('company_name', '=', addslashes($company_name))->first();
		if($company_exists === null) {
			$company = Robot_company::create([
					"company_name" => $company_name,
					"source_id" => $source_id,
					"website" => $website,
					"website_status_id" => 1,
					"career_page" => "",
					"career_page_status_id" => 2,
					'created_at' => date("Y-m-d H:i:s",time()),
					'updated_at' => date("Y-m-d H:i:s",time()),
					'location_id' => 0
				    ]);

		    if($source_id>=1 || $source_id <=3)
		    {
				Robot_log::create(['robot_action_type_id'=>1, 'robot_company_id'=> $company->id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Trying to add career page for ' . $company_name]);
				$career_page_url = $this->addCareerPageToRobotCompany($company->id);
				//add ks
				if ($career_page_url){ 
					$ks = $this->findKeySelector($career_page_url);
					if ($ks){
						$RobotCompany = Robot_company::find($company->id);
						$RobotCompany->key_selector = $ks;
						$RobotCompany->save();
						$this->updateRobotCompanyProgressionStatus($company->id, 4);
		//				echo 'got ks';
					}
				} else $this->updateRobotCompanyProgressionStatus($company->id, 3);
				//ks

				return $company->id;			
		    }
		    else{
			    Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> $company->id, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Unidentified Source']);
			    return FALSE;
		    }
		}
		else{
		    Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> 0, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Company name already exists in robot_companies']);
		    return FALSE;	
		}
	}
	//Add company by careerpage

	public function addCompanyByCareerPage($company_name, $source_id, $website, $career_page) {
		$company_exists = Robot_company::where('company_name', '=', addslashes($company_name))->first();
		if($company_exists === null) {
			$company = Robot_company::create([
					"company_name" => $company_name,
					"source_id" => $source_id,
					"website" => $website,
					"website_status_id" => 1,
					"career_page" => $career_page,
					"career_page_status_id" => 2,
					'created_at' => date("Y-m-d H:i:s",time()),
					'updated_at' => date("Y-m-d H:i:s",time()),
					'location_id' => 0
					]);	
					
			if($source_id>=1 || $source_id <=3)
			{
				
				
				$ks = $this->findKeySelector($career_page);
				if ($ks){
					$RobotCompany = Robot_company::find($company->id);
					$RobotCompany->key_selector = $ks;
					$RobotCompany->save();
					$this->updateRobotCompanyProgressionStatus($company->id, 4);
	//				echo 'got ks';
				}
								
				$this->updateRobotCompanyProgressionStatus($company->id, 4);
				return $company->id;			
			}		

		}
		else{
		    Robot_log::create(['robot_action_type_id'=>2, 'robot_company_id'=> 0, 'created_at' => date("Y-m-d H:i:s",time()), 'note' =>'Company name already exists in robot_companies']);
		    return FALSE;	
		}
	}

	//update robot company progression status
	public function updateRobotCompanyProgressionStatus($company_id, $status_id) 
	{
		$rc = Robot_company::find($company_id);
		$rc->robot_company_progression_status_id = $status_id;
		$rc->save();
	}


	public function extractHtmlDom($html, $query) 
	{
		$dom = new DOMDocument;
		@$dom->loadHTML($html);
		$xpath =  new \DOMXPath($dom);
		$links = $xpath->query($query);
		return $links;

	}
	
	public function getLinkFromText($html, $link_text) {

		if (!is_string($html)){
			return false;
		}

		$query = "//a";
		$links = $this->extractHtmlDom($html, $query);
		$link = "";       
		foreach ($links as $link) 
		{
			if (trim(strtolower($link->nodeValue)) === strtolower($link_text)
				||(stristr(trim(strtolower($link->nodeValue)), strtolower($link_text)) !== false)) {
				$link = $link->getAttribute('href');
				break;				
				}
			}
		return $link;
	}

	//QA career page
	public function QACareerPage($robot_company_id) 
	{
		$robotCompany = Robot_company::find($robot_company_id);
		$originalCareerPage = $robotCompany->career_page;
		if (!empty($originalCareerPage)) {
			$positions_found = Positions_found_with_key_selector::where("robot_company_id", $robot_company_id)->count();
			if (2 > $positions_found) {
				if(($robotCompany->career_page == $robotCompany->website)
					|| (stripos($robotCompany->career_page,"about") !==false)) {
					
						$newCareerPage = $this->findRealCareerPageThroughLinksOnAssumedCareerPage($originalCareerPage);

						if ($newCareerPage && ($originalCareerPage !== $newCareerPage)) {
							$robotCompany->career_page = $newCareerPage;
							$robotCompany->save();
						  }
						  return false;
			 



				} // career_page == website
			} // pos <2
			else if(2 > Positions_found_with_key_selector::where("robot_company_id", $robot_company_id)->orWhere(function($query){
				$query->where("position_title","like", "engineer")
						 ->where("position_title","like", "software");
			})->count()) 
			{
				   
					$newCareerPage = $this->findRealCareerPageThroughLinksOnAssumedCareerPage($originalCareerPage);
					
					if (!empty($newCareerPage) && ($originalCareerPage !== $newCareerPage)) {
						$robotCompany->career_page = $newCareerPage;
						$robotCompany->save();
					}
					return false;

			} //else if
			  return true;
		} // end of org car page exists

		return false;

	} //end of func

	//getAQPositions

	public function QAPositions($robot_company_id) 
	{
		$positions_found = Positions_found_with_key_selector::where("robot_company_id", $robot_company_id)->count();
		$robotCompany = Robot_company::find($robot_company_id);
		$robotCompany->robot_company_progression_status_id = $robotCompany->robot_company_progression_status_id+1;
		$robotCompany->save();
	}

	public function convertRobotCompanyToRoleSentryCompany($robot_company_id) 
	{
		
				$robotCompany = Robot_company::find($robot_company_id);
				$rolesentry_company_exists = Rolesentry_company::where('career_page_url', '=', $robotCompany->career_page)->first();  
				
						if($rolesentry_company_exists === null) {
							$rec = new Rolesentry_company([
								//name, angellist_url, career_page_url, xpath  
											"name" => $robotCompany->company_name ,
											"angellist_url" => "",
											"career_page_url" => $robotCompany->career_page,
											'xpath' => $robotCompany->key_selector,
											'first_scrape' => 1,
											'location_id' => 1,
											'created_at' => date("Y-m-d H:i:s",time()),
											'updated_at' => date("Y-m-d H:i:s",time()),
										'user_id' => Auth::user()->id 
										]);
						//unset($data); 
						$rec->save();		
			}
	}

    //.......
    }
}
