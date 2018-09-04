<?php
     //print phpinfo();
		header('Content-Type: application/json'); 
        header("Access-Control-Allow-Origin: *");

       $_REQUEST['career_page_url'] = $_REQUEST["url"];
	   $html = file_get_contents($_REQUEST['career_page_url']);
       //$_REQUEST['xpath'] = "//h5";
		
	   $job_postings = extractByTag($html, $_REQUEST['xpath']);

		if ($job_postings){
			//echo "<h2>Job Postings found!</h2><br><br>";
			$res = [];
			foreach($job_postings as $job_posting) {
				   //echo $job_posting->nodeValue . '<br>';
				   $res[] = $job_posting->nodeValue; 
			}
			
			print json_encode($res);
			
		}	

function extractByTag($html,$query)
{
	if ($html){
		$dom = @DOMDocument::loadHTML($html);
		$xpath = new DOMXpath($dom);
		$links = $xpath->query( $query );
		return $links;
	}
	else{
		return false;
	}
}
?>