<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;
use App\Classes\RobotHelper;
use App\Robot_company;	
use App\Robot_log;
use DB;
use Auth;
		
class RobotConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:consume';

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


    public function handle() 
    {

    //print "Test Test";
    //$this->saveCompany("Google", 1) ;
    //die();
    $connection  = new AMQPConnection(config("app.cloudampq_host"), config("app.cloudampq_port"), config("app.cloudampq_user"), config("app.cloudampq_pw"), config("app.cloudampq_user"));
		$channel = $connection->channel();
		$channel->basic_qos(null, 1, null);			
		$channel->queue_declare('robotqueue', false, true, false, false);		
		$channel->basic_consume('robotqueue', '', false, false, false, false, array($this,'mqCallback'));
		while(count($channel->callbacks)) {
	    	$channel->wait();
		}

    }

    public function mqCallback($msg, $silent = false) {
      $MessageFromRobotQueue = json_decode($msg->body);	
      //dd($MessageFromRobotQueue);
      var_dump($MessageFromRobotQueue);
      //if(!empty($MessageFromRobotQueue))
      if (!$MessageFromRobotQueue){
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
          return false;
      }
      $rh = new RobotHelper;
      $newly_saved_company_id = $rh->saveCompany($MessageFromRobotQueue->company_name, $MessageFromRobotQueue->source_id, $MessageFromRobotQueue->location_id);

      if ($newly_saved_company_id) {

		$conn = new AMQPConnection(config("app.cloudampq_host"), config("app.cloudampq_port"), config("app.cloudampq_user"), config("app.cloudampq_pw"), config("app.cloudampq_user"));
		$ch = $conn->channel();
		$ch->queue_declare('robot_queue_needs_career_page', false, true, false, false);
		$msg_body = '{ "robot_company_id":'.$newly_saved_company_id.' }';
		$msgForCareerPage = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
		$ch->basic_publish($msgForCareerPage,'','robot_queue_needs_career_page');
      }
 
      $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);				
    
    }

    /**
     * Execute the console command.
     *		
     * @return mixed
     */

    public function RobotFindCareerPageQueue() 
    {

      //code goes here

    }


}
