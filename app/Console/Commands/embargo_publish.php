<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Email;
use App\Http\Controllers\CMSController;

class embargo_publish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'embargo:publish {location_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'publish at later time';

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

        $argument = (object)[
                                                        "location_id" => $this->argument("location_id")
                                                ];

	if ($this->argument("location_id") == 1) {
		$email = Email::where('embargo_new_york', 1)->orderBy('id','DESC')->first();
		$email->embargo_new_york = 0;
		$email->save();
	}

	if ($this->argument("location_id") == 5) {
		$email = Email::where('embargo_san_francisco', 1)->orderBy('id','DESC')->first();
		$email->embargo_san_francisco = 0;
		$email->save();
	}

	if ($this->argument("location_id") == 15) {
		$email = Email::where('embargo_sydney', 1)->orderBy('id','DESC')->first();
		$email->embargo_sydney = 0;
		$email->save();
	}

	if ($this->argument("location_id") == 16) {
		$email = Email::where('embargo_melbourne', 1)->orderBy('id','DESC')->first();
		$email->embargo_melbourne = 0;
		$email->save();
	}

	if ($email) {
		$request = new \Illuminate\Http\Request(['emailId'=>$email->id]);
		$cms = new CMSController;
	 	$cms->sendSummaryAlert($request, $argument->location_id);
	}
        //
    }
}
