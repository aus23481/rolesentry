<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\CMSController;
use App\SavedSearch;

class saved_search_send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:saved_search {time_to_send}';

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
				
        $argument = (object)[
                                                        "time_to_send" => $this->argument("time_to_send"),
                            ];

		$saved_searches = SavedSearch::where('time_to_send', $argument->time_to_send)->get();
		$cms = new CMSController;


		foreach($saved_searches as $saved_search){
			echo "sending saved search id: " . $saved_search->id;
			$cms->sendSavedSearchEmail($saved_search);
		}
    }
}
