<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Indeed_company;
use App\Indeed_job_title;
use App\Indeed_listing;
use App\Indeed_location;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ScrapeIndeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:indeed';

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
        $this->baseurl = url('/');
        // //"http://localhost:8000";       
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getScrapeSessionParametersFromCommandCenter($command_center_url) {
            
            $ch = curl_init($command_center_url.'/indeedScraperCommandCenter');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch));
            return $response;
    }
     
    public function getResultsFromServer($career_page_url) {
        //print $this->baseurl."/xpath/?url=". urlencode($career_page_url);
        $response = @file_get_contents($this->baseurl."/xpath/?url=". urlencode($career_page_url));
        //print $response;
        return json_decode($response);
    }
    
    public function scrapeDataFromIndeed() 
    {        
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        $limit = 0;
        $page = 0;
        
        $scrapeSessionParameters = $this->getScrapeSessionParametersFromCommandCenter($this->baseurl);
        while ( $page < $scrapeSessionParameters->pages_to_scrape) {
            $scrape_Url = "https://www.indeed.com/jobs?q=&l=".urlencode($scrapeSessionParameters->name)."&start=".$limit;       
            $results = $this->getResultsFromServer($scrape_Url);
            $item = 0;
            foreach($results as $company) 
            {
                
                $this->saveItem($company->name, $company->company_url,  $company->job_title, $company->job_title_detail,  $company->summary, $scrapeSessionParameters->id, $scrapeSessionParameters->location_id);
                $item++;
            }

            if($item > 9) { 
                $page++;
                $limit +=10; 
            }
        } 
    }

    public function saveItem($company_name, $company_url, $title, $job_title_detail, $summary, $location_id, $main_location_id) 
    {
        //company
        $company = Indeed_company::where('name', '=', addslashes($company_name))->first();          
        $company_id = 0;
        if ($company === null) {
                $rec = new Indeed_company([
                                "name" => $company_name,
                                "url" => $company_url,
                                "location_id" => $main_location_id
                            ]); 
                $rec->save();
                
                $this->addCompanyToRobotQueue($company_name, $main_location_id);
                $company_id = $rec->id;                  
        } else  $company_id = $company->id;
        //job title
        $job_title = Indeed_job_title::where('name', '=', addslashes($title))->first();          
        $job_title_id = 0;
        if($job_title === null) {
                $rec = new Indeed_job_title([
                                "name" => $title,                                
                            ]); 
            $rec->save(); 
           $job_title_id = $rec->id;                 
        } else  $job_title_id = $job_title->id;

        if ($job_title_id && $company_id) {

            $rec = new Indeed_listing([
                "indeed_company_id" => $company_id,
                "indeed_location_id" => $location_id,
                "indeed_job_title_id" => $job_title_id,
                "summary_text" => $summary,
                'created_at' => date("Y-m-d H:i:s",time()),
                'updated_at' => date("Y-m-d H:i:s",time())
            ]); 
            $rec->save();
           // print "saved";            
        }


    }

    public function addCompanyToRobotQueue($name, $location_id) 
    {
        
        $conn = new AMQPConnection(config("app.cloudampq_host"), config("app.cloudampq_port"), config("app.cloudampq_user"), config("app.cloudampq_pw"), config("app.cloudampq_user"));
        
        $ch = $conn->channel();
    
        $ch->queue_declare('robotqueue', false, true, false, false);
    
        $msg_body = '{ "source_id":1, "company_name":"'.$name.'", "location_id":'.$location_id.' }';
        $msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
       // print $msg_body;
        $ch->basic_publish($msg,'','robotqueue');
        //print "Sent";
    }

    public function handle()
    {
        $this->scrapeDataFromIndeed();
        
    }
}
