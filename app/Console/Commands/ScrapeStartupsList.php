<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Startupslist_company;
use App\Startupslist_location;
use DB;
use Auth;
use App\Classes\RobotHelper;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ScrapeStartupsList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:startupslist';

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
        //.":8000"; //"http://localhost:8000";       
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getScrapeSessionParametersFromCommandCenter($command_center_url) {
            
            $ch = curl_init($command_center_url.'/startupslistCommandCenter');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch));
           // print "-Hello-".$command_center_url.'/startupslistCommandCenter';
            return $response;
    }
     
    public function scrapeDataFromStartups() 

    {                
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            )
        );  

        $scrape_Url = "https://startups-list.com/";
        $html = file_get_contents($scrape_Url, false, stream_context_create($arrContextOptions));
        //$location_added = 1;
        //if(!$location_added) $this->addLocation($html);
        //print $this->baseurl;
        $scrapeSessionParameters = $this->getScrapeSessionParametersFromCommandCenter($this->baseurl);
        
        //print_r($scrapeSessionParameters);
        //die();
        $scrape_Url = $scrapeSessionParameters->url;
        //print $scrape_Url;
        
        $html = file_get_contents($scrape_Url, false, stream_context_create($arrContextOptions));
       // print $html;
        //die();
        $rh = new RobotHelper;
        $query = "//div//a//h1";
        $links = $rh->extractHtmlDom($html, $query);
        foreach($links as $company) {
            $url = $company->parentNode->getAttribute("href");
            $name = trim(strip_tags($company->nodeValue));
            //print $name."-".$url."<br>";
            if(!empty($name)) {
                $this->saveItem($name, $url, $scrapeSessionParameters->location_id );
            }
        }


    }

    public function addLocation($html) {
        $rh = new RobotHelper;
        $query = "//a//h3";
        $links = $rh->extractHtmlDom($html, $query);
        foreach($links as $company) {
            $url = $company->parentNode->getAttribute("href");
             $name = trim(strip_tags($company->nodeValue));
             if(!empty($name)) {
                Startupslist_location::create(["name"=>$name,"url"=> $url]);
            }
        }

    }
    public function saveItem($name, $url, $location_id) 
    {
        $company_exists = Startupslist_company::where('name', '=', addslashes($name))->first();          
        if($company_exists === null) {
                $rec = new Startupslist_company([
                                "name" => $name,
                                "url" => $url,                                
                                'location_id' => $location_id
                            ]); 
            $rec->save();
            
            $this->addCompanyToRobotQueue($name, $location_id);                  
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
        $this->scrapeDataFromStartups();
    }
}
