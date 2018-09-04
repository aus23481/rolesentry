<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Greekwire_company;

use DB;
use Auth;
use App\Classes\RobotHelper;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ScrapeGreekWire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:greekwire';

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
            
            $ch = curl_init($command_center_url.'/GreekWireCommandCenter');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch));
           // print "-Hello-".$command_center_url.'/GreekWireCommandCenter';
            return $response;
    }
     
    public function scrapeDataFromGreekwire() 

    {                
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            )
        );  

        $scrape_Url = "https://www.geekwire.com/startup-list/";
        $html = file_get_contents($scrape_Url, false, stream_context_create($arrContextOptions));
        
        $html = file_get_contents($scrape_Url, false, stream_context_create($arrContextOptions));
        $rh = new RobotHelper;
        $query = "//table//tr//td//div//a//span";
        $links = $rh->extractHtmlDom($html, $query);
        foreach($links as $company) {
            $url = $company->parentNode->getAttribute("href");
            $name = trim(strip_tags($company->nodeValue));
            if(!empty($name)) {
                $this->saveItem($name, $url, 25 );
            }
        }


    }

    public function saveItem($name, $url, $location_id) 
    {
        $company_exists = Greekwire_company::where('name', '=', addslashes($name))->first();          
        if($company_exists === null) {
                $rec = new Greekwire_company([
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
        $this->scrapeDataFromGreekwire();
    }
}
