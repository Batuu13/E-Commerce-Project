<?php
require('includes/config.php'); 
function sef_link($bas)
{   
    $bas = str_replace(array("&quot;","&#39;"), NULL, $bas);
    $bul = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', '-', ' & ');
    $yap = array('c', 's', 'g', 'u', 'i', 'o', 'c', 's', 'g', 'u', 'o', 'i', ' ', ' ');
    $perma = strtolower(str_replace($bul, $yap, $bas));
    
    $perma = preg_replace("@[^A-Za-z0-9\-_]@i", ' ', $perma);
    $perma = trim(preg_replace('/\s+/',' ', $perma));
    $perma = str_replace(' ', '-', $perma);
    return $perma;
}


echo'<meta charset="utf-8">';
 $site = ('www.sahibinden.com');
 
  $ch = curl_init();
  $hc = "YahooSeeker-Testing/v3.9 (compatible; Mozilla 4.0; MSIE 5.5; Yahoo! Search - Web Search)";
  curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
  curl_setopt($ch, CURLOPT_URL, $site);
  curl_setopt($ch, CURLOPT_USERAGENT, $hc);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
  $site = curl_exec($ch);
  curl_close($ch);
  
  // Veriyi parçalama işlemi
  preg_match_all('@ <a href="/kategori/(.*?)" title="(.*?)">(.*?)</a>@si',$site,$veri_derece1);
  
/*  preg_match_all('@<div id="liGunes">(.*?)</div>@si',$site,$veri_derece2);
  preg_match_all('@<div id="liOgle">(.*?)</div>@si',$site,$veri_derece3);
  preg_match_all('@<div id="liIkindi">(.*?)</div>@si',$site,$veri_derece4);
  preg_match_all('@<div id="liAksam">(.*?)</div>@si',$site,$veri_derece5);
  preg_match_all('@<div id="liYatsi">(.*?)</div>@si',$site,$veri_derece6);*/

  echo $veri_derece1[1][0];
  	$i = 1;
	$k = 1;
  foreach($veri_derece1[2] as $veri){     
  echo sef_link($veri) ."<br>";
 
 	 	$stmt = $db->prepare('INSERT INTO categories2 (parentID,name,link_adresi,siralama) VALUES (:parentID,:name,:link_adresi,:siralama)');
				$stmt->execute(array(
				':parentID' => 0,
				':name' =>  $veri,
				':link_adresi' => sef_link($veri),
				':siralama' => $i
 					));
 
 
 	$site2 = "www.sahibinden.com/kategori/". sef_link($veri);
	  $ch = curl_init();
	  $hc = "YahooSeeker-Testing/v3.9 (compatible; Mozilla 4.0; MSIE 5.5; Yahoo! Search - Web Search)";
	  curl_setopt($ch, CURLOPT_REFERER, 'http://www.google.com');
	  curl_setopt($ch, CURLOPT_URL, $site2);
	  curl_setopt($ch, CURLOPT_USERAGENT, $hc);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 2);
	  $site2 = curl_exec($ch);
	  curl_close($ch);
  preg_match_all('@ <a href="/'.sef_link($veri).'-(.*?)" shape="rect">(.*?)</a>@si',$site2,$veri_derece2);
  $j = 1; 
   foreach($veri_derece2[2] as $veri1){     
 		 echo $veri1 ."*<br>";
		
		   	$stmt = $db->prepare('INSERT INTO categories2 (parentID,name,link_adresi,siralama) VALUES (:parentID,:name,:link_adresi,:siralama)');
				$stmt->execute(array(
				':parentID' => $k,
				':name' =>  $veri1,
				':link_adresi' => sef_link($veri1),
				':siralama' => $j
 					));
					$j++;
					
   }
 	$k += $j;

				
				
  	
			
  
   $i++;
  }

  
 
 
 

 

?>
