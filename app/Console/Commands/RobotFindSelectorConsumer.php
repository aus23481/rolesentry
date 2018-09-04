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

class RobotFindSelectorConsumer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'robot:findSelectorFromCareerPage';

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
        //
         $connection  = new AMQPConnection(config("app.cloudampq_host"), config("app.cloudampq_port"), config("app.cloudampq_user"), config("app.cloudampq_pw"), config("app.cloudampq_user"));
         
                $channel = $connection->channel();
                $channel->basic_qos(null, 1, null);
                $channel->queue_declare('robot_queue_needs_key_selector', false, true, false, false);
                $channel->basic_consume('robot_queue_needs_key_selector', '', false, false, false, false, array($this,'mqCallback'));
                while(count($channel->callbacks)) {
                	$channel->wait();
                }

   }
   public function mqCallback($msg, $silent = false) {

	$MessageFromRobotNeedsSelectorQueue = json_decode($msg->body);
	$RobotCompany = Robot_company::find($MessageFromRobotNeedsSelectorQueue->robot_company_id);
        if (!$RobotCompany){
      $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
	return 0;
	}	
	echo "trying to find key selector for " . $RobotCompany->company_name . ' robot_company_id - ' . $RobotCompany->id . ' career_page ' . $RobotCompany->career_page;

	$rh = new RobotHelper();
	$keySelector = $rh->findKeySelector($RobotCompany->career_page);
	if ($keySelector){
		echo "Found key selector: " . $keySelector . ' for ' . $RobotCompany->name ;
		$RobotCompany->key_selector = $keySelector;
		$RobotCompany->save();
		//found key selector
		//send to queue for approval robot_queue_needs_approval
	}
	else{
		echo "Could not find key selector for" . $RobotCompany->name;
		//could not find key selector
	}
      $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);	
    }
}
