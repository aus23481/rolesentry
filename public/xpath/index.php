<?php
     //print phpinfo();
		header('Content-Type: application/json'); 
        header("Access-Control-Allow-Origin: *");
		//print $_REQUEST["url"];
		//die();
       $_REQUEST['career_page_url'] = $_REQUEST["url"];
	   $html = file_get_contents($_REQUEST['career_page_url']);
       //print $html;
	   //die();
	   $_REQUEST['xpath'] = "//h2[@class='jobtitle']//a";
		
	   $job_postings = extractByTag($html, $_REQUEST['xpath']);
	   
	   
		if ($job_postings){
			//echo "<h2>Job Postings found!</h2><br><br>";
			$res = [];
			$i = 0;
			foreach($job_postings as $job_posting) {
				
				   $role =  $job_posting->nodeValue . '<br>';
				   $job_title_detail = $job_posting->getAttribute("href");				   
				   $href = explode("&fccid=",$job_posting->getAttribute("href"))[0];
				   $href = str_replace("/rc/clk?jk=","",$href);				   
				   $company = @extractByTag($html,"//div[@id='p_".$href."']//span//a")[0]->nodeValue;				   
				   $company_url_check = $company; //str_replace(" ","-",$company);
				   $company_url = "";
				   $match = '/cmp/'.trim($company);
				   $pos = stripos($html,$match);
				   //print $company."-".$company_url_check."-".$pos."<br>";
				   
					//die();
				   if($pos !== false)
				   $company_url = $match;  //@extractByTag($html,"//div[@id='p_".$href."']//span//a")->item(0)->getAttribute("href");				   
				   //print $company_url."<br>";
				   $summary = @extractByTag($html,"//div[@id='p_".$href."']//table//tr//td//div//span")[0]->nodeValue;
				   
				   $res[$i]["name"] = trim($company);
				   $res[$i]["company_url"] = trim($company_url);
				   $res[$i]["job_title"] = trim($job_posting->nodeValue);
				   $res[$i]["job_title_detail"] = trim($job_title_detail);
				   $res[$i]["summary"] = trim($summary);
				   //print $company."-".$company_url."-".$summary."<br>";
				   $i++; 
			}
			 //print_r($res);
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