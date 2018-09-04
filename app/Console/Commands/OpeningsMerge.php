<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\OpeningConsolidator;
use App\Opening;	

use DB;
use Auth;
		
class OpeningsMerge extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openings:merge';

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

    public function runOpeningsMerge() 
    {
      foreach(Opening::all() as $opening) 
      {
        OpeningConsolidator::mergeOpenings($opening->id); //        
      }
    }

    public function handle() 
    {
      $this->runOpeningsMerge();
    }
}
