<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Location;
use App\Job_board_alert;
use App\Job_board_scrape_log;
use  DOMDocument;
use App\Ban_url;
use App\Ban_job_title;
use App\Ban_company_name;
use App\Job_type_word;
use App\Job_type_word_job_subtype;
use App\Opening_subtype;
use App\opening;
use App\Rolesentry_company;
use App\Tag;
use App\SavedSearch;
use App\Http\Controllers\PlatformController;
use App\SavedSearchJobType;
use App\SavedSearchLocation;
use App\SavedSearchOpening;
use App\Opening_tag;
use App\Http\Controllers\CMSController;
use App\Classes\OpeningConsolidator;

use Auth;


class jobBoardScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:job_board {job_board} {job_terms} {location_id} {job_type_id}';

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	 // Sample Command Usage
	// php artisan scrape:job_board indeed tech 1 (Will scrape Software Engineer, New York City on Indeed)
	// php artisan scrape:job_board indeed tech 5 (Will scrape Sales Director, San Francisco on ZipRecruiter)  These are numbers are locations.id in existing locations table 
	
	$argument = (object)[
							"job_board" => $this->argument("job_board"),
							"job_terms" => $this->argument("job_terms"),
							"location_id" => $this->argument("location_id"),
							"job_type_id" => $this->argument("job_type_id")
						];
	
	if ($argument->job_board == "Indeed") {

		$urlToGetListOfItems = $this->getIndeedLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id		
		$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //$urlForListOfNewestListings// actually scrape the URL
		$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//div//h2//a'); //*[@class="turnstyleLink"]		
	} 		
	
	if ($argument->job_board == "ZipRecruiter") {

		$urlToGetListOfItems = $this->getZipRecruiterLatestResultsUrl($argument->job_terms, $argument->location_id); // This is locations table id		
		$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //actually scrape the URL		
		$newestListings = $this->getElementsFromHTML($newestListingsHTML, "//article//div//a");
	} 
	
	if ($argument->job_board == "CareerOne") {

		$urlToGetListOfItems = $this->getCareerOneLatestResultsUrl($argument->job_terms, $argument->location_id); // This is locations table id		
		print $urlToGetListOfItems."\n";
		$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //actually scrape the URL		
		//print $newestListingsHTML;
		$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//div//div//div//div//div[@class="job_title"]//a');
	} 
	
	if ($argument->job_board == "Trovit") {

		$urlToGetListOfItems = $this->getTrovitLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id		
		print $urlToGetListOfItems."\n";
		$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //actually scrape the URL
		$newestListings = $this->getElementsFromHTML($newestListingsHTML, "//div//h4//a");
	}		
	
	if ($argument->job_board == "CareerJet") {

		$urlToGetListOfItems = $this->getCareerJetLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id
		print $urlToGetListOfItems."<br>";
		$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //actually scrape the URL
		//print $newestListingsHTML;
		$newestListings = $this->getElementsFromHTML($newestListingsHTML, "//div//div//p//a");
	}
	
	if ($argument->job_board == "Dice") {
		
				$urlToGetListOfItems = $this->getDiceLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id
				$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //actually scrape the URL
				$newestListings = $this->getElementsFromHTML($newestListingsHTML, "//ul//li//h3//a");
	}

	if ($argument->job_board == "NYTimes") {
		
				$urlToGetListOfItems = $this->getNYTimesLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id						
				$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //$urlForListOfNewestListings// actually scrape the URL
				$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//div//a'); //*[@class="turnstyleLink"]		
	}
	
	if ($argument->job_board == "CareerBuilder") {
		
				$urlToGetListOfItems = $this->getCareerBuilderLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id						
				print $urlToGetListOfItems;
				$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //$urlForListOfNewestListings// actually scrape the URL
				$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//div//h2//a'); //*[@class="turnstyleLink"]		
	}

	if ($argument->job_board == "SimplyHired") {
		
				$urlToGetListOfItems = $this->getSimplyHiredLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id						
				print $urlToGetListOfItems;
				$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //$urlForListOfNewestListings// actually scrape the URL
				$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//div//h2//a'); //*[@class="turnstyleLink"]		
	}

	if ($argument->job_board == "Monster") {
		
				$urlToGetListOfItems = $this->getMonsterLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id						
				print $urlToGetListOfItems;
				$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //$urlForListOfNewestListings// actually scrape the URL
				$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//header//h2//a'); //*[@class="turnstyleLink"]		
	}

	if ($argument->job_board == "Linkup") {
		
				$urlToGetListOfItems = $this->getLinkupLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id						
				print $urlToGetListOfItems;
				$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //$urlForListOfNewestListings// actually scrape the URL
				$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//div//h4//a'); //*[@class="turnstyleLink"]		
	}			

	if ($argument->job_board == "Stackoverflow") {
		
				$urlToGetListOfItems = $this->getStackoverflowLatestResultsUrl($argument->job_terms, $argument->location_id); //This is locations table id						
				print $urlToGetListOfItems;
				$newestListingsHTML = $this->scrapeUrl($urlToGetListOfItems); //$urlForListOfNewestListings// actually scrape the URL
				$newestListings = $this->getElementsFromHTML($newestListingsHTML, '//div//h2//a'); //*[@class="turnstyleLink"]		
	}

    /**/
	//Now we are trying to find the details page for these listings.
	$item = 0;	
	foreach($newestListings as $listing) 
	{
		
		$jobDescriptionOnCompanyPage = "";
		$location_id = 1;
		$company = ""; 
		$job_title = "";
		$full_description = "";
		$jobDescriptionDetailsPageUrl = "";
		//print $listing->nodeValue;
		//die();
		if ($argument->job_board == 'Indeed') {
			$jobDetailsPageUrl = "https://www.indeed.com".$listing->getAttribute("href");
			//print $listing->nodeValue."\n";
			
			///*
			$href_json = str_replace("vjs=3","vjs=1",$jobDetailsPageUrl);

			$detail_page_json =  json_decode($this->scrapeUrl($href_json));
			$jobDescriptionDetailsPageUrl = $this->scrapeUrl($jobDetailsPageUrl, true);
			$company = $detail_page_json->sicm->cmN;
			$job_title = $detail_page_json->jobTitle;
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;
			//print $jobDetailsPageUrl;
			$detail_page_html = $this->scrapeUrl($jobDetailsPageUrl);
			//Extract Job Summary
			//$full_description = $this->getElementsFromHTML($detail_page_html, '//span[@id="job_summary"]')[0]->nodeValue;
			$fd = $this->getElementsFromHTML($detail_page_html, '//span[@id="job_summary"]');
			if($fd->length)				
			$full_description = $fd[0]->nodeValue;		
			
			$jobDescriptionOnCompanyPage =  $this->scrapeUrl($jobDetailsPageUrl, true);
			$final_url = "";
			if(strpos($detail_page_html,'Apply On Company Site') !== false) {
				if($this->getElementsFromHTML($detail_page_html, '//a[contains(@class,"view-apply-button")]')->length)
				$final_url = $this->getElementsFromHTML($detail_page_html, '//a[contains(@class,"view-apply-button")]')[0]->getAttribute("href");
			$jobDescriptionOnCompanyPage = $this->scrapeUrl("https://www.indeed.com".$final_url, true);
			} else {
				if($this->getElementsFromHTML($detail_page_html, '//span[contains(@class,"indeed-apply-widge")]')->length)
				$final_url = $this->getElementsFromHTML($detail_page_html, '//span[contains(@class,"indeed-apply-widge")]')[0]->getAttribute("data-indeed-apply-joburl");
				$jobDescriptionOnCompanyPage = $this->scrapeUrl($final_url, true);
				
			}
			if($jobDescriptionOnCompanyPage === $jobDescriptionDetailsPageUrl) 
				$jobDescriptionOnCompanyPage = " ";
			//*/	
		}

		else if ($argument->job_board == 'ZipRecruiter') {

			//TODO
			$jobDescriptionDetailsPageUrl = $listing->getAttribute("href");
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;
			//$jobDescriptionDetailsPageUrl_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
			// apply on company page url not found
			$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true); //"Not Found";
				

		}

		else if ($argument->job_board == 'Trovit') {
			//TODO
			$jobDescriptionDetailsPageUrl = $listing->getAttribute("href");
			$jobDescriptionDetailsPageUrl = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);
			$job_title = $listing->nodeValue;			
			//$company = $this->getElementsFromHTML($newestListingsHTML, '//div//h4//a[@href="'.$listing->getAttribute("href").'"]//..//../h5//span//span');

			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;

			$detail_url_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
			if($detail_url_html && strpos($detail_url_html, "View job") !== false) {

				
				
				
				if(strpos($detail_url_html, "Company") !== false)
				$company = $this->getElementsFromHTML($detail_url_html, "//dl//dd")[0]->nodeValue;

				$full_description = $this->getElementsFromHTML($detail_url_html, '//div[@id="description"]')[0]->nodeValue;
				//$requirements = @$this->getElementsFromHTML($detail_url_html, '//div[@id="requirements"]')[0]->nodeValue;
				$full_description = $full_description;
				$jobDescriptionOnCompanyPage = "";
				if(strpos($detail_url_html, "View job") !== false) {
					//print $detail_url_html;
					$final_url = $this->getElementsFromHTML($detail_url_html, '//div[contains(@class,"to_source")]//a[@class="button_primary"]')[0]->getAttribute("href");
					$jobDescriptionOnCompanyPage = $this->scrapeUrl($final_url, true);

					$jobDescriptionOnCompanyPage = urldecode($this->get_redirect_final_target($jobDescriptionOnCompanyPage));
					$jobDescriptionOnCompanyPage = $this->scrapeUrl(urldecode(explode("id.",explode("url.",$jobDescriptionOnCompanyPage)[1])[0]),true);				
			    }	
			}			

		}

		else if ($argument->job_board == 'CareerJet') {
			//TODO
			$jobDescriptionDetailsPageUrl = "https://www.careerjet.com".$listing->getAttribute("href");			
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;  
			if(strpos($jobDescriptionDetailsPageUrl,'/job/') !== false) {
				$job_title = $listing->nodeValue;
			    print $job_title."\n";	
				$jobDescriptionDetailsPageUrl =  $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);
				$detail_page_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
				//print $detail_page_html;
				if(strpos($detail_page_html,"Learn More & Apply") !== false){					
					$final_url = "https://www.careerjet.com".$this->getElementsFromHTML($detail_page_html, "//div//div//div//a[@id='learn_more_button']")[0]->getAttribute("href");
					$company = $this->getElementsFromHTML($detail_page_html, '//span[@class="company_compact"]')[0]->nodeValue;	
					$jobDescriptionOnCompanyPage = $this->scrapeUrl($final_url, true);
					
				}

				$full_description = @$this->getElementsFromHTML($detail_page_html, '//div[@class="advertise_compact"]')[0]->nodeValue;
				

				if(empty($company))
					$company = $this->getElementsFromHTML($newestListingsHTML, '//p//span[@class="company_compact"]')[$item]->nodeValue;

				if(empty($full_description))
					$full_description = $this->getElementsFromHTML($newestListingsHTML, '//div[@class="advertise_compact"]')[$item]->nodeValue;
				
				if(empty($jobDescriptionOnCompanyPage)) {
					$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);
					$html = $this->scrapeUrl($jobDescriptionOnCompanyPage);
					if($jobDescriptionOnCompanyPage !== $jobDescriptionDetailsPageUrl)
					$full_description = $this->getElementsFromHTML($html, '//div[@class="description"]')[0]->nodeValue;
				}

				$item++;
			}	
			
		}

		else if ($argument->job_board == 'Dice') {
			//TODO
			$jobDescriptionDetailsPageUrl = "https://www.dice.com".$listing->getAttribute("href");
			$job_title = $listing->nodeValue;

			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;
			$detail_page_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
			//$jobDescriptionDetailsPageUrl = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);
			//print $jobDescriptionDetailsPageUrl."<br>";
			//print $jobDescriptionOnCompanyPage."<br>";
			
			//print $detail_page_html;
			if(strpos($detail_page_html,'id="applybtn"') !== false){
				//print "In apply button";					
				$final_url = $this->getElementsFromHTML($detail_page_html, "//[@id='applybtn']")[0]->getAttribute("onclick");
				$company = $this->getElementsFromHTML($detail_page_html, '//ul//li//a//span')[0]->nodeValue;	
				//$jobDescriptionOnCompanyPage = $this->scrapeUrl($final_url, true);
				
			}
			//print $final_url ."::".$company;
			//print $job_title."<br>";
			//die();

		}

		else if ($argument->job_board == 'NYTimes') {
			//TODO
			$jobDescriptionDetailsPageUrl = $listing->getAttribute("href");			
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;  
			if(strpos($jobDescriptionDetailsPageUrl,'/job/') !== false) {
				  
				$job_id = explode("/", str_replace("https://jobs.nytimes.com/job/","", $jobDescriptionDetailsPageUrl))[0];
				$job_title = $this->getElementsFromHTML($newestListingsHTML, '//span[@id="pos-title-'.$job_id.'" ]//span//span')[0]->nodeValue;
				$company = $this->getElementsFromHTML($newestListingsHTML, '//a[@href="'.$jobDescriptionDetailsPageUrl.'" ]//span//span//span//span//span[@class="Company-name"]')[0]->nodeValue;
				$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);
				$full_description = $this->getElementsFromHTML($newestListingsHTML, '//a[@href="'.$jobDescriptionDetailsPageUrl.'" ]//span//span//span//span//span[contains(@class,"Position-Description")]//span')[0]->nodeValue;
				if($jobDescriptionOnCompanyPage === $jobDescriptionDetailsPageUrl) $jobDescriptionOnCompanyPage = " ";
				$detail_page_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
				//print $detail_page_html;
				//die();
				//detail page is not readable response paritial html
			}			
		}

		else if ($argument->job_board == 'CareerBuilder') {
			//TODO
			$jobDescriptionDetailsPageUrl = "https://www.careerbuilder.com".$listing->getAttribute("href");	
			$job_title = $listing->nodeValue;		
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;
			
			$company_did = $listing->getAttribute("data-company-did");
			$cmp = $this->getElementsFromHTML($newestListingsHTML, '//div//h4//a[@data-company-did="'.$company_did.'" ]');
			
			if($cmp->length > 0) {
				$node = $cmp->item(0);
				$company  = $node->nodeValue;
			}

			//if(isset($company[0])) $company = $company[0]->nodeValue;

			$detail_page_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
			$full_description = $this->getElementsFromHTML($detail_page_html, '//div[contains(@class,"description")]')[0]->nodeValue;
			if(empty($company)) {

				$cmp = $this->getElementsFromHTML($detail_page_html, '//h2[@id="job-company-name"]');
				if($cmp->length > 0) {
					$node = $cmp->item(0);
					$company  = $node->nodeValue;
				}
			}	
			print $job_title."\n";
			$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);

			if($jobDescriptionOnCompanyPage === $jobDescriptionDetailsPageUrl) 
			$jobDescriptionOnCompanyPage = "";

			if(empty($jobDescriptionOnCompanyPage)) {

				 if(strpos($detail_page_html,"Apply on Company Website") !== false) {
					$final_url = $this->getElementsFromHTML($detail_page_html, '//a[@id="apply-now-top"]')[0]->getAttribute("href");
					$jobDescriptionOnCompanyPage = "https://www.careerbuilder.com".$final_url;
					//print "\n this->".$jobDescriptionOnCompanyPage;
					$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionOnCompanyPage, true);
				    $jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionOnCompanyPage, true);
					//print "\n redirected->".$jobDescriptionOnCompanyPage."-kd\n";
				}
			}
			
	
		}	

		else if ($argument->job_board == 'SimplyHired') {
			//TODO
			$jobDescriptionDetailsPageUrl = "https://www.simplyhired.com".$listing->getAttribute("href");	
			$job_title = $listing->nodeValue;		
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;

			$detail_page_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);

			$full_description_node = $this->getElementsFromHTML($detail_page_html, '//div[contains(@class,"description")]')[0];

			$full_description = isset($full_description_node) ? $full_description_node->nodeValue : "";

			$company_node = $this->getElementsFromHTML($detail_page_html, '//h2//span[contains(@class,"company")]')[0];

			$company = isset($company_node) ? $company_node->nodeValue : "";

			$apply_url_node = $this->getElementsFromHTML($detail_page_html, '//div[@class ="apply"]//a')[0];

			$apply_url = isset($apply_url_node) ? $apply_url_node->getAttribute("href") : "";

			$jobDescriptionOnCompanyPage = $this->scrapeUrl("https://www.simplyhired.com".$apply_url, true);
		}
		
		else if ($argument->job_board == 'Monster') {
			//TODO
			sleep(5);
			$jobDescriptionDetailsPageUrl = $listing->getAttribute("href");	
			$job_title = $listing->nodeValue;		
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;

			$detail_page_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
			$fd = $this->getElementsFromHTML($detail_page_html, '//div[contains(@class,"details-content")]');
			sleep(5);
			if($fd->length > 0) {
				$node = $fd->item(0);
				$full_description  = $node->nodeValue;
			}

			if(empty($full_description)) {

				$fd = $this->getElementsFromHTML($detail_page_html, '//div[contains(@itemprop,"description")]');
				
				if($fd->length > 0) {
					$node = $fd->item(0);
					$full_description  = $node->nodeValue;
				} else {

					$fd = $this->getElementsFromHTML($detail_page_html, '//div[contains(@class,"description")]');
					if($fd->length > 0) {
						$node = $fd->item(0);
						$full_description  = $node->nodeValue;
					}

				}
				sleep(5);
				if(empty($full_description)) {
					
					$fd = $this->getElementsFromHTML($detail_page_html, '//div[contains(@id,"CJT_jobBodyContent")]');
					if($fd->length > 0) {
						$node = $fd->item(0);
						$full_description  = $node->nodeValue;
					}
				}

			}
			sleep(5);
			$cmp = $this->getElementsFromHTML($detail_page_html, '//div//h1[contains(@class,"title")]');
			print $jobDescriptionDetailsPageUrl."\n";

			if($cmp->length > 0) {
				$node = $cmp->item(0);
				$company  = $node->nodeValue;
				$company = explode(" at ", $company)[1];
				

			}

			if(strpos($detail_page_html, "BodyCaoApply") !== false) {
				$jobDescriptionOnCompanyPage = $this->getElementsFromHTML($detail_page_html, '//div[contains(@class,"mux-cao-subscribe")]')[0]->getAttribute("data-apply");				
				if(empty($jobDescriptionOnCompanyPage))
				$jobDescriptionOnCompanyPage = $this->getElementsFromHTML($detail_page_html, '//div[contains(@id,"BodyCaoPanel")]')[0]->getAttribute("data-apply");
				//print $jobDescriptionOnCompanyPage."\n";
			}

		}

		else if ($argument->job_board == 'Linkup') {
			//TODO
			$jobDescriptionDetailsPageUrl = $listing->getAttribute("href");	
			$job_title = $listing->nodeValue;		
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;
			print $job_title."-".$jobDescriptionDetailsPageUrl;
			//die();
			$detail_page_html = $this->scrapeUrl("https://www.linkup.com".$jobDescriptionDetailsPageUrl);
			$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);
			
			
			if(strpos($detail_page_html, "VIEW MORE &") !== false) {

				$full_description = $this->getElementsFromHTML($detail_page_html, '//div[contains(@class,"description")]')[0]->nodeValue;
				$company = $this->getElementsFromHTML($detail_page_html, '//span[contains(@itemprop,"hiringOrganization")]')[0]->nodeValue;
	
				//$jobDescriptionOnCompanyPage = $this->getElementsFromHTML($detail_page_html, '//a[contains(@class,"apply-link")]')[0]->getAttribute("href");				
				if(empty($jobDescriptionOnCompanyPage))
				$jobDescriptionOnCompanyPage = $this->getElementsFromHTML($detail_page_html, '//a[contains(@class,"apply-link")]')[0]->getAttribute("href");
				//print $jobDescriptionOnCompanyPage."\n";
			}

			//print $job_title."-".$company."-".$jobDescriptionOnCompanyPage."\n";
			//print $full_description."\n";
		}

		else if ($argument->job_board == 'Stackoverflow') {
			//TODO
			$jobDescriptionDetailsPageUrl = "https://stackoverflow.com".$listing->getAttribute("href");	
			$job_title = $listing->nodeValue;		
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;
			print $jobDescriptionDetailsPageUrl."\n";
			$detail_page_html = $this->scrapeUrl($jobDescriptionDetailsPageUrl);
			$full_description = $this->getElementsFromHTML($detail_page_html, '//section[contains(@class,"-job-description")]')[0]->nodeValue;
			$company = $this->getElementsFromHTML($detail_page_html, '//div//a[contains(@class,"employer")]')[0]->nodeValue;
			$jobDescriptionOnCompanyPage = "";
			if(strpos($detail_page_html, "Apply now") !== false) {
				$jobDescriptionOnCompanyPage = $this->getElementsFromHTML($detail_page_html, '//div//a[contains(@class,"js-apply")]')[0]->getAttribute("href");
				$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionOnCompanyPage, true);			
				
				if(strpos($jobDescriptionOnCompanyPage, "stackoverflow") != false)
				$jobDescriptionOnCompanyPage = "";				
				//print $jobDescriptionOnCompanyPage."\n";
			} 
			//print $job_title."-".$company."-".$jobDescriptionOnCompanyPage."\n";
			//print $full_description."\n";
			//die();

		}

		else if ($argument->job_board == 'CareerOne') {
			//TODO
			$jobDescriptionDetailsPageUrl = $listing->getAttribute("href");	
			$job_title = $listing->nodeValue;		
			$jobDetailsPageUrl = $jobDescriptionDetailsPageUrl;
			print $job_title."-".$jobDescriptionDetailsPageUrl;
			die();

			$detail_page_html = $this->scrapeUrl("https://www.linkup.com".$jobDescriptionDetailsPageUrl);
			$jobDescriptionOnCompanyPage = $this->scrapeUrl($jobDescriptionDetailsPageUrl, true);
			

			
			if(strpos($detail_page_html, "VIEW MORE &") !== false) {

				$full_description = $this->getElementsFromHTML($detail_page_html, '//div[contains(@class,"description")]')[0]->nodeValue;
				$company = $this->getElementsFromHTML($detail_page_html, '//span[contains(@itemprop,"hiringOrganization")]')[0]->nodeValue;
	
				//$jobDescriptionOnCompanyPage = $this->getElementsFromHTML($detail_page_html, '//a[contains(@class,"apply-link")]')[0]->getAttribute("href");				
				if(empty($jobDescriptionOnCompanyPage))
				$jobDescriptionOnCompanyPage = $this->getElementsFromHTML($detail_page_html, '//a[contains(@class,"apply-link")]')[0]->getAttribute("href");
				//print $jobDescriptionOnCompanyPage."\n";
			}

			//print $job_title."-".$company."-".$jobDescriptionOnCompanyPage."\n";
			//print $full_description."\n";
		}

		$success = 0;
		$automatically_approved = 0;
		$job_type_id = NULL;
		
		//if ($jobDescriptionOnCompanyPage&&($jobDescriptionOnCompanyPage !== $jobDescriptionDetailsPageUrl)) {
		///*	
			if (!$this->jobBoardAlertExists($jobDescriptionOnCompanyPage, $jobDescriptionDetailsPageUrl, $argument->job_board, $job_title, $company) && !empty($job_title)) {
				
				$automatically_approved = $this->getBanStatus($jobDescriptionOnCompanyPage, $job_title, $company);
				//$calculated_job_type_id = $this->getJobTypeId($job_title);
				$job_type_subtypes_array = $this->getJobTypeId($job_title);
				$calculated_job_type_id = $job_type_subtypes_array["type"];
				$job_type_id = $calculated_job_type_id ? $calculated_job_type_id : $argument->job_type_id;

				if(empty($job_type_id)) $job_type_id = NULL;

				$newJobBoardAlert = new Job_board_alert();
				$newJobBoardAlert->job_description_on_company_page = $jobDescriptionOnCompanyPage; //This is what we really want
				$newJobBoardAlert->job_descrpition_on_job_board_page = $jobDetailsPageUrl;
				$newJobBoardAlert->job_board = $argument->job_board;
				$newJobBoardAlert->location_id = $argument->location_id;
				$newJobBoardAlert->company = $company;
				$newJobBoardAlert->job_title = $job_title;
				$full_description = mb_check_encoding($full_description, 'UTF-8') ? $full_description : utf8_encode($full_description);
				$newJobBoardAlert->full_description =  htmlspecialchars(utf8_encode(addslashes($full_description)));
				$newJobBoardAlert->created_at = date("Y-m-d H:i:s",time());
				$newJobBoardAlert->automatically_approved = $automatically_approved;
				$newJobBoardAlert->job_type_id = $job_type_id;
				$newJobBoardAlert->save();				
				$success = 1; //mark this a success since we are adding a row to job_alerts	
				//opening create and check
				if((!empty($company))) 
				{					
						echo $company;
					//Create new opening with all fields.
					$company_rec = Rolesentry_company::where("name", $company)->first();
					if($company_rec === null) {

						$company_rec = Rolesentry_company::create([
										"name" => $company,
										"angellist_url" => "",
										"career_page_url" => $jobDetailsPageUrl,
										'xpath' => "",
										'first_scrape' => 1,
										'location_id' => $argument->location_id,
										'created_at' => date("Y-m-d H:i:s",time()),
										'updated_at' => date("Y-m-d H:i:s",time()),
										'user_id' => 1 
									]);
					}
		

					$opening_id = OpeningConsolidator::CreateOrGetOpening(
														$job_title, 
														$company_rec->id, 
														$newJobBoardAlert->job_description_on_company_page, 
														$newJobBoardAlert->job_descrpition_on_job_board_page, 
														$newJobBoardAlert->full_description, 
														$argument->location_id, 
														$job_type_id);

					if($opening_id) 
					{									
						if(!empty($job_type_subtypes_array["subtypes"]))
						$this->addToOpeningSubtypes($opening_id, $job_type_subtypes_array["subtypes"]);

						PlatformController::runBanning($opening_id);				   
						//tags to be added found in job_description
						$this->searchJobDescriptionForTags($newJobBoardAlert->full_description, $job_type_id, $opening_id);
						$this->addToSavedSearches($opening_id);
					} 
				
				}
				//end of opening create
				//die();					
			}
		 //*/	 
		//}
	}

	//$this->logScrape($argument->job_board, $argument->job_terms, $argument->location_id, $success);

	}
	
	function get_redirect_final_target($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // follow redirects
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1); // set referer on redirect
		curl_exec($ch);
		$target = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
		curl_close($ch);
		if ($target)
			return $target;
		return false;
	}

    public function getElementsFromHTML($html, $query) {

	if ($html) {

		$dom = new DOMDocument;
		@$dom->loadHTML($html);
		$xpath =  new \DOMXPath($dom);
		$links = $xpath->query($query);
		return $links;		
	}
	else {
		return false;
	}
    }
	
    public function logScrape($job_board, $job_terms, $location_id, $success) {

	$jobBoardScrapeLog = new Job_board_scrape_log();
	$jobBoardScrapeLog->job_board = $job_board; //Indeed
	$jobBoardScrapeLog->job_terms = $job_terms; //Software Engineer
	$jobBoardScrapeLog->location_id = $location_id; //1
	$jobBoardScrapeLog->success = $success; //TRUE
	$jobBoardScrapeLog->save();

    }

    public function scrapeUrl($url, $only_return_final_url_after_redirects = FALSE) {

	// $only_return_final_url_after_redirects does not return any HTML
	// it will follow the redirects until ending up at the final URL
	// and will return that final URL
	
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,0); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	$html = curl_exec($ch);
	//if($only_return_final_url_after_redirects) sleep(20);

	$redirectURL = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL );	
	
	//if($only_return_final_url_after_redirects) {print $url."<br>".$redirectURL; die(); }
	curl_close($ch);

	return $only_return_final_url_after_redirects ? $redirectURL : $html;
	
    }

    public function jobBoardAlertExists($jobDescriptionOnCompanyPage, $jobDescriptionDetailsPageUrl, $job_board, $job_title, $company) {

	$jobBoardAlertResult = Job_board_alert::where('company', $company)
							//where('job_description_on_company_page', $jobDescriptionOnCompanyPage)
						  //->where('job_descrpition_on_job_board_page', $jobDescriptionDetailsPageUrl)
						  ->where('job_title', $job_title)
					      ->where('job_board', $job_board)
						  ->first();
	if ($jobBoardAlertResult == NULL) { 
		return false;				
	}

	return true;

    }

    public function getIndeedLatestResultsUrl($job_terms, $location_id) {
	
	$location = Location::find($location_id);

	$location_string = $location->indeed_location_name;
	$location_url = $location->indeed_url;

	$url =  "https://". ($location_url ? $location_url : "www.indeed.com" ) ."/jobs?q=" . urlencode($job_terms) . "&l=" . urlencode($location_string).'&sort=date&radius=0';
	echo $url;
	return $url;
    }
	
    public function getZipRecruiterLatestResultsUrl($job_terms, $location_id) {
			
	$location_string = Location::find($location_id)->ziprecruiter_location_name;
	return "https://www.ziprecruiter.com/candidate/search?search=".urlencode($job_terms)."&location=".urlencode($location_string); 
    }

    public function getTrovitLatestResultsUrl($job_terms, $location_id) {

    	$location_string = Location::find($location_id)->trovit_location_name;
	return "https://job.trovit.com/index.php/cod.search_jobs/what_d.".urlencode($job_terms)."/where_d.".urlencode($location_string)."/sug.1/isUserSearch.1"; 
    }
    public function getCareerJetLatestResultsUrl($job_terms, $location_id) {

    	$location_string = Location::find($location_id)->careerjet_location_name;
	$url = "https://www.careerjet.com/wsearch/jobs?s=".urlencode($job_terms)."&l=".urlencode($location_string)."&sort=date";  
	echo $url;
	return $url;
	}
	
	public function getDiceLatestResultsUrl($job_terms, $location_id) {
		
		$location_string = Location::find($location_id)->dice_location_name;
		return "https://www.dice.com/jobs?q=".urlencode($job_terms)."&l=".urlencode($location_string);  
	}

	public function getNYTimesLatestResultsUrl($job_terms, $location_id) {		
		$location_string = Location::find($location_id)->nytimes_location_name;
		return "https://jobs.nytimes.com/Jobs/".urlencode(str_replace(" ","-",strtolower($job_terms)))."-jobs-in-".urlencode($location_string)."?source=1&countryId=3&radius=20";
	}

	public function getCareerBuilderLatestResultsUrl($job_terms, $location_id) {		
		$location_string = Location::find($location_id)->careerbuilder_location_name;
		return "https://www.careerbuilder.com/jobs-".urlencode(str_replace(" ","-",strtolower($job_terms)))."-in-".urlencode($location_string)."?sort=relevance_desc";
	}

	public function getSimplyHiredLatestResultsUrl($job_terms, $location_id) {		
	
	
	$location = Location::find($location_id);

	$location_string = $location->simplyhired_location_name;
	$location_url = $location->simplyhired_url;

		$location_string = Location::find($location_id)->simplyhired_location_name;
		return "https://". ($location_url ? $location_url : "www.simplyhired.com" ) ."/search?q=".urlencode(strtolower($job_terms))."&l=".urlencode($location_string)."&mi=exact&fdb=1&sb=dd";
	}

	public function getMonsterLatestResultsUrl($job_terms, $location_id) {		
		$location_string = Location::find($location_id)->monster_location_name;
		return "https://www.monster.com/jobs/search/?q=".urlencode(strtolower($job_terms))."&tm=0&rad=5&where=".urlencode($location_string);
	}

	public function getLinkupLatestResultsUrl($job_terms, $location_id) {		
		$location_string = Location::find($location_id)->linkup_location_name;
		return "https://www.linkup.com/search/results/".urlencode(strtolower($job_terms))."-jobs?location=".urlencode($location_string);
	}

	public function getStackoverflowLatestResultsUrl($job_terms, $location_id) {		
		$location_string = Location::find($location_id)->stackoverflow_location_name;
		return "https://stackoverflow.com/jobs?sort=p&q=".urlencode(strtolower($job_terms))."&l=".urlencode($location_string)."&d=5&u=Miles";

	}

	public function getCareerOneLatestResultsUrl($job_terms, $location_id) {		
		$location_string = Location::find($location_id)->careerone_location_name;
		return "https://www.careerone.com.au/search?search_keywords=".urlencode(strtolower($job_terms))."&search_category=&search_location=".urlencode($location_string);


	}

	
	// Above redirect url cann't find final url for NYTimes
	
	/**
	 * get_redirect_url()
	 * Gets the address that the provided URL redirects to,
	 * or FALSE if there's no redirect. 
	 *
	 * @param string $url
	 * @return string
	 */

	public function get_redirect_url($url){
		$redirect_url = null; 

		$url_parts = parse_url($url);
		if (!$url_parts) return false;
		if (!isset($url_parts['host'])) return false; //can't process relative URLs
		if (!isset($url_parts['path'])) $url_parts['path'] = '/';

		$sock = fsockopen($url_parts['host'], (isset($url_parts['port']) ? (int)$url_parts['port'] : 80), $errno, $errstr, 30);
		if (!$sock) return false;

		$request = "HEAD " . $url_parts['path'] . (isset($url_parts['query']) ? '?'.$url_parts['query'] : '') . " HTTP/1.1\r\n"; 
		$request .= 'Host: ' . $url_parts['host'] . "\r\n"; 
		$request .= "Connection: Close\r\n\r\n"; 
		fwrite($sock, $request);
		$response = '';
		while(!feof($sock)) $response .= fread($sock, 8192);
		fclose($sock);

		if (preg_match('/^Location: (.+?)$/m', $response, $matches)){
			if ( substr($matches[1], 0, 1) == "/" )
				return $url_parts['scheme'] . "://" . $url_parts['host'] . trim($matches[1]);
			else
				return trim($matches[1]);

		} else {
			return false;
		}

	}

	/**
	 * get_all_redirects()
	 * Follows and collects all redirects, in order, for the given URL. 
	 *
	 * @param string $url
	 * @return array
	 */
	function get_all_redirects($url){
		$redirects = array();
		while ($newurl = $this->get_redirect_url($url)){
			if (in_array($newurl, $redirects)){
				break;
			}
			$redirects[] = $newurl;
			$url = $newurl;
		}
		return $redirects;
	}

	/**
	 * get_final_url()
	 * Gets the address that the URL ultimately leads to. 
	 * Returns $url itself if it isn't a redirect.
	 *
	 * @param string $url
	 * @return string
	 */
	public function get_final_url($url){
		$redirects = $this->get_all_redirects($url);
		if (count($redirects)>0){
			return array_pop($redirects);
		} else {
			return $url;
		}
	}
	//Redirect URL


       public function getBanStatus($url, $job_title, $company)
        {

		$ban_terms_url = Ban_url::all()->pluck('term')->toArray();
		$ban_terms_search_array = array_map('strtolower', $ban_terms_url);

                foreach($ban_terms_search_array as $ban_word){
                        if (stripos(strtolower($url), $ban_word) != "") {
                                return false;
                        }
                }

		$ban_terms_job_title = Ban_job_title::all()->pluck('term')->toArray();
		$ban_terms_search_array = array_map('strtolower', $ban_terms_job_title);

                foreach($ban_terms_search_array as $ban_word){
	
                        if (stripos(strtolower($job_title), $ban_word) != "") {
                                return false;
                        }
                }

		$ban_terms_company_name = Ban_company_name::all()->pluck('term')->toArray();
		$ban_terms_search_array = array_map('strtolower', $ban_terms_company_name);

                foreach($ban_terms_search_array as $ban_word){
                        if (stripos(strtolower($company), $ban_word) != "") {
                                return false;
                        }
                }

                return true;

        }

	public function getJobTypeId($job_title) 
	{
		$subtypes = [];
		$type_id = 0;

		if(!empty($job_title)) {
			$str_exists = Job_type_word::whereIn("word", explode(" ",$job_title))->first();
			if($str_exists) {
				$job_type_word_job_subtypes = Job_type_word_job_subtype::where("job_type_word_id", $str_exists->id)->get();
				if($job_type_word_job_subtypes) {
					foreach($job_type_word_job_subtypes as $job_type_word_job_subtype) {
						$subtypes[] = $job_type_word_job_subtype->job_subtype_id;
					}
				}
				
				$type_id =  $str_exists->job_type_id; 
				return [ "type" => $type_id, "subtypes" =>$subtypes ];
			}
		}

		return false;
	}
	
	public function searchJobDescriptionForTags($job_description, $job_type_id, $opening_id) 
	{		
		$all_tags_to_search_for = Tag::where('job_type_id', $job_type_id)->get();
		
		foreach($all_tags_to_search_for as $tag) 
		{
			if(stripos($job_description, $tag->name) !== false) {
				Opening_tag::create([
					"opening_id" => $opening_id,
					"tag_id" => $tag->id
				]);
			}

		}
		//search $job_description for any of $all_tags_to_search_for
		//and create rows in *opening_tag* for all tags found	
	}

        public function addToSavedSearches($opening_id) {
		

		$cms = new CMSController;

		$opening = opening::find($opening_id);
		

		$saved_searches = SavedSearch::all();
		

		foreach($saved_searches as $saved_search) {
	

		//echo "saved search searching";
		if(empty($saved_search->term)) continue;

		if (strpos($opening->title, $saved_search->term)){
			
				$types_good = false;
				$locations_good = false;

				$saved_search_job_types = SavedSearchJobType::where('saved_search_id', $saved_search->id)->get();
				if ($saved_search_job_types->isEmpty()){
					$types_good = true;
				}
				else{
					foreach($saved_search_job_types as $saved_search_job_type){
						if ($saved_search_job_type->job_type_id == $opening->job_type_id){
							$types_good = true;
						}
					}
				}			

				$saved_search_locations = SavedSearchLocation::where('saved_search_id', $saved_search->id)->get();			

				if ($saved_search_locations->isEmpty()){
					$locations_good = true; 
				}
				foreach($saved_search_locations as $saved_search_location){
					if ($saved_search_location->location_id == $opening->location_id){
						$locations_good = true;
					}
				}	
		
				if ($types_good && $locations_good) {
				      $saved_search_opening = SavedSearchOpening::create([
						'saved_search_id'=>$saved_search->id,
						'opening_id'=>$opening->id
				      ]);

				      if ($saved_search->is_instant) {
						echo "SENDING THIS OUT";
                        			$cms->sendSavedSearchEmail($saved_search);
				      }
					else{
						echo "NOTSENDNIG INSTANT";
					}	

				}
	
			}
		}
	}

	//Add opening id to Opening_subtype
	public function addToOpeningSubtypes($opening_id, $subtypes) {

		foreach($subtypes  as $subtype) {
			Opening_subtype::create([
				"opening_id" => $opening_id,
				"subtype_id" => $subtype
			]);
		}
	}

	
}
