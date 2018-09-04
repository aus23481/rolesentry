<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Location;
use App\Job_board;
use App\Search_term;
use Artisan;

class calljobboardscraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'callscraper:jobboard {--job_board=} {--location=} {--search_term=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will call jobBoardScraper command in loop';

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
      
		$job_boards_to_scrape = [];
		$search_terms_to_scrape = [];
		$location_ids_to_scrape = [];


		if ($this->option('job_board')) {
			$job_board = Job_board::find($this->option('job_board'));
			$job_boards_to_scrape = [$job_board->name];
		}
		else{
			$job_boards = Job_board::pluck("name")->toArray();
			$job_boards_to_scrape = $job_boards;
		}

		if ($this->option('location')) {
			$location = Location::find($this->option('location'));
			$locations_to_scrape = [$location->id];
		}
		else{
			$locations = Location::whereNotNull('indeed_location_name')->pluck("id")->toArray();
			$locations_to_scrape = $locations;
		}
		if ($this->option('search_term')) {
			$search_term = Search_term::find($this->option('search_term'));
			$search_terms_to_scrape = [$search_term];
		}
		else{
			$search_terms = Search_term::select(["term","job_type_id"])->get();
			$search_terms_to_scrape = $search_terms;
		}
		
		$t=time();
echo($t . "<br>");
echo(date("Y-m-d",$t));
			foreach($locations_to_scrape as $location){
				foreach($search_terms_to_scrape as $search_term){
					foreach($job_boards_to_scrape as $job_board){
					
						$command = "php /var/www/html/artisan scrape:job_board " . $job_board . " \"" . $search_term->term . "\" " . $location . " " . $search_term->job_type_id;
						echo $command;
						shell_exec($command);

						/*$this->call('scrape:job_board', [
							'job_board' => $job_board,						
							'job_terms' => $search_term->term,
							'location_id' => $location,
							'job_type_id' => $search_term->job_type_id
						]); */
					}	
				}
			}

$t=time();
echo($t . "<br>");
echo(date("Y-m-d",$t));
		
			//
	}
}

