<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Owler_company;
use DB;
use Auth;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

class ScrapeOwler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:owler {location}  {--location=}';

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
    public function getScrapeSessionParametersFromCommandCenter($command_center_url, $location) {
            
            $ch = curl_init($command_center_url.'/owlerScraperCommandCenter?location='.$location);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch));
            return $response;
    }
     
    public function scrapeDataFromOwler() 
    {        
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  
        $scrapeSessionParameters = $this->getScrapeSessionParametersFromCommandCenter($this->baseurl, $this->argument('location'));



        $scrape_Url = "https://www.owler.com/a/v1/pb/getSearchPageResults?city=".urlencode($scrapeSessionParameters->city)."&mod=FOLLOWERS&p=".$scrapeSessionParameters->page."&limit=15&minFunding=0&maxFunding=2000000000&minAge=0&maxAge=100&minRevenue=2000000&maxRevenue=500000000000&minEmployees=0&maxEmployees=2500000";

	echo $scrape_Url;	

        //print $scrape_Url;
        $json_items = file_get_contents($scrape_Url, false, stream_context_create($arrContextOptions));
        $json_items = json_decode($json_items);
        foreach($json_items->companyDetails as $company) 
        {
            $this->saveItem($company->name, $company->description, $company->profile_url, $scrapeSessionParameters->location_id );
        }
    }

    public function saveItem($company_name, $company_description, $link_to_full_company_profile, $location_id) 
    {
        $company_exists = Owler_company::where('company_name', '=', addslashes($company_name))->first();          
        if($company_exists === null) {
                $rec = new Owler_company([
                                "company_name" => $company_name,
                                "company_description" => $company_description,
                                "link_to_full_company_profile" => $link_to_full_company_profile,                                    
                                'created_at' => date("Y-m-d H:i:s",time()),
                                'updated_at' => date("Y-m-d H:i:s",time()),
                                'location_id' => $location_id
                            ]); 
            $rec->save();
            $this->addCompanyToRobotQueue($company_name, $location_id);                  
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
        $this->scrapeDataFromOwler();
        
    }
}
