<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\f6s_company;
use DB;
use Auth;
use App\Classes\RobotHelper;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ScrapeF6s extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:f6s';

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
        $this->baseurl = url('/').":8000";
        //.":8000"; //"http://localhost:8000";       
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function getScrapeSessionParametersFromCommandCenter($command_center_url) {
            
            $ch = curl_init($command_center_url.'/f6sScraperCommandCenter');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch));
            return $response;
    }
     
    public function scrapeDataFromf6s() 
    {        
        
        
        $postdata = http_build_query(
            array(
                'page' => 2,
                'ss' => 1,
                'sort' => "popularity",
                'sort_dir' => "desc",
                'all_startups' => 1,
                'columns' =>['location']
                
                

            )
        );
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
            'http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );  
       // $scrapeSessionParameters = $this->getScrapeSessionParametersFromCommandCenter($this->baseurl);

        //if(!$scrapeSessionParameters->page) $scrapeSessionParameters->page = 1;
        //$scrape_Url = "https://f6s.com/search/new?category=startups&keywords=".urlencode($scrapeSessionParameters->name)."&page=".urlencode($scrapeSessionParameters->page)."&partial=results&";
        $scrape_Url = "https://www.f6s.com/startups";
        $scrape_Url = "https://www.f6s.com/startups?search={%22context%22:%22and%22,%22rules%22:[{%22filter%22:%22location%22,%22operator%22:%22eq%22,%22value%22:{%22object_id%22:118726,%22type%22:%22city%22}}]}&ss=1&sort=popularity&sort_dir=desc&all_startups=1&columns[]=markets&columns[]=location&columns[]=founders&columns[]=founded";
        

	

        //print $scrape_Url;
        $html = file_get_contents($scrape_Url, false, stream_context_create($arrContextOptions));
        print $html;
        print_r(json_decode($html)) ;
        die();
        $rh = new RobotHelper;
        $query = "//ul//li//div/div/div//a";
        $links = $rh->extractHtmlDom($html, $query);
        foreach($links as $company) {
            $url = $company->getAttribute("href");
            $name = $company->nodeValue;
            if(!empty($name)) {
                $this->saveItem($name, $url, $scrapeSessionParameters->location_id );
            }
        }
    }

    public function saveItem($name, $url, $location_id) 
    {
        $company_exists = f6s_company::where('name', '=', addslashes($name))->first();          
        if($company_exists === null) {
                $rec = new f6s_company([
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
        $this->scrapeDataFromf6s();
    }
}
