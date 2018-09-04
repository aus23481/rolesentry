<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Classes\RobotHelper;
use App\Scrape_google;

use DB;
use  DOMDocument;


class ScrapeGoogleController extends Controller
{
    //
    public function searchGoogle() {
    
        
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );  

        $search_keys = DB::table('scrape_google')->get();

        $searchable_key = "";
        foreach($search_keys as $key) {           
            $scrape_google = Scrape_google::find($key->id);
            $searchable_key = urlencode($key->title." ".$key->company_name." linkedin");
            $scrape_Url = "https://www.google.com/search?source=hp&ei=hvp0W6_CKdK6rQGgio_QAg&q=".$searchable_key."&oq=".$searchable_key."&gs_l=psy-ab.3.0.33i21k1j33i160k1.428967.438236.0.444400.13.11.0.0.0.0.786.3566.3-5j1j1j1.8.0....0...1.1.64.psy-ab..5.3.1711.0...0.1XfrE07Xh-k";
            //print $scrape_Url."<br>";
            $html = file_get_contents($scrape_Url, false, stream_context_create($arrContextOptions));
            //$rh = new RobotHelper;
            $query = "//div//h3//a";
            //$links = $rh->extractHtmlDom($html, $query);
            $links = $this->extractHtmlDom($html, $query);
            foreach($links as $link) {
                
                $url = $link->getAttribute("href");
                $url = explode('q=', $url)[1];
                $url = explode('&sa', $url)[0];
                $result_text = $link->nodeValue;
                print $url."/<br>";
                print $result_text."<br>";
                $scrape_google->first_google_result = $url."/";
                $scrape_google->first_google_result_text = $result_text;
                $scrape_google->save();  
                print "Saved<br>";              
                break;    
            }
            sleep(60);        
        }        	
    }

    public function extractHtmlDom($html, $query) 
	{
		$dom = new DOMDocument;
		@$dom->loadHTML($html);
		$xpath =  new \DOMXPath($dom);
		$links = $xpath->query($query);
		return $links;

	}

}
