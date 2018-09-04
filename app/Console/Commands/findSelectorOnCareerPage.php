<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Owler_company;
use App\Rolesentry_company;

class findSelectorOnCareerPage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'find:selector';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {

        parent::__construct();

    }

	public function get_career_page($domain){
		echo 'trying ' . $domain;
			echo '/careers';
		if ($this->getStatusCode($domain . '/careers')){
			return ($this->getStatusCode($domain . '/careers'));
		}
			echo '/jobs';
		if ($this->getStatusCode($domain . '/jobs')){
			return ($this->getStatusCode($domain . '/jobs'));
		}
			echo '/join';
		if ($this->getStatusCode($domain . '/join')){

			return ($this->getStatusCode($domain . '/join'));
		}
		if ($this->getStatusCode($domain . '/about/careers')){

			return ($this->getStatusCode($domain . '/careers'));
		}
		return false;
	}

        public function nameToDomain($name){


		$ch = curl_init('https://company.clearbit.com/v1/domains/find?name='.$name);
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

	public function getStatusCode($url){
		$ch = curl_init($url);
echo $url;
		curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
		curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,10);
		$output = curl_exec($ch);

		echo $output;	

		$target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);

	echo "33234234234234 ".$target . '********************************';

		return ($httpcode == 200 ? $target : false);
	}

    public function handle(){
	while(1){
		$company = Owler_company::where('worked', '!=', 1)->orWhereNull('worked')->first();
		$company->worked = 1;
		$company->save();

		$clean_company_name = substr($company->company_description, 0, strpos($company->company_description,"is"));
		$company_domain = $this->nameToDomain($clean_company_name);
		
		if (!$company_domain){
			echo('cant find domain');
			continue;
		}


		$career_page_url = $this->get_career_page($company_domain);

		if (!$career_page_url){
			echo('cant find career page');
			continue;
		}

		$words = ['ngineer','oftware','asdf','anager','eporter', 'enior', 'ackend','rontend'];
		

		$results = $this->getResultsFromNodeServer($career_page_url, $words);


		$word_count = 0;
		$result_set = [];
		if (!$results){
		continue;
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

					echo $path_details['path'] .'not found in ';
					print_r($clean_paths);
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

		$job_roles = json_decode(file_get_contents( config('app.scraperurl') . '?page=' . urlencode($career_page_url) . '&selector=' . urlencode($key_selector_js)));
		if ($job_roles){
			foreach($job_roles as $job_role){
				echo substr($job_role, 0, strpos($job_role, "9999x9999")) . PHP_EOL;
			}
			if (count($job_roles) > 3){
				$newCompany = new Rolesentry_company(['career_page_url'=>$career_page_url, 'name'=>$clean_company_name, 'xpath'=>$key_selector_js]);
				echo $newCompany->save();
			}
			echo '1231223';
		}



	}
//  	insert into companies table.	
	}

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
		$url_for_node_server = config('app.nodeserver_url') . ':3010/';	
		$request = $url_for_node_server . '?career_page=' . urlencode($career_page_url) . '&words=' . urlencode(implode($words,","));

		echo $request. '0000000000000000000000000000000000000000000000000';

		$response = file_get_contents($request);	
		return json_decode($response);

   }

}
