<?php
	
	// CSS importer

	add_action('init', 'csv_importer_taxonomies', 0);

	function csv_importer_taxonomies() {
    	register_taxonomy('art', 'post', array(
        'hierarchical' => true,
        'label' => 'Art',
    	));
    	
	register_taxonomy('country', 'post', array(
        'hierarchical' => false,
        'label' => 'Country',
    	));
	}

	// Add RSS links to <head> section
	automatic_feed_links();
	
	// Load jQuery
	if ( !is_admin() ) {
	   wp_deregister_script('jquery');
	   wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1.4.1/jquery.min.js"), false);
	   wp_enqueue_script('jquery');
	}
	
	// Clean up the <head>
	function removeHeadLinks() {
    	remove_action('wp_head', 'rsd_link');
    	remove_action('wp_head', 'wlwmanifest_link');
    }
    add_action('init', 'removeHeadLinks');
    remove_action('wp_head', 'wp_generator');
    
    if (function_exists('register_sidebar')) {
    	register_sidebar(array(
    		'name' => 'Sidebar Widgets',
    		'id'   => 'sidebar-widgets',
    		'description'   => 'These are widgets for the sidebar.',
    		'before_widget' => '<div id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</div>',
    		'before_title'  => '<h2>',
    		'after_title'   => '</h2>'
    	));
    }

?>
<?php
add_action('acf/register_fields', 'my_register_fields');

function my_register_fields()
{
    include_once('/home/propel/public_html/locator/wp-content/themes/physician/functions.php');
}


function nuevo_manda_email( $post_id ) {
	
$post = get_post($post_id);
 if($post->post_status == 'publish'){
 
	//verify post is not a revision
	
	if ( !wp_is_post_revision( $post_id ) ) {

		$nuevo= get_post_meta($post_id);
		$nzip=$nuevo['zip'][0];
			while (strlen($nzip)<5)	
			$nzip='0'.$nzip;
		
		global $wpdb;

		$coordes= $wpdb->get_results("select Lon, Lat from zipcodes where Zipcode='{$nzip}'");

		foreach($coordes as $coords)
		$zipcodes= $wpdb->get_results("SELECT Zipcode, ( 3959 * acos( cos( radians( ".$coords->Lat." ) ) * cos( radians( Lat ) ) * cos( radians( Lon ) - radians( ".$coords->Lon." ) ) + sin( radians( ".$coords->Lat." ) ) * sin( radians( Lat ) ) ) ) AS distance FROM zipcodes HAVING distance <= 500 ORDER BY Zipcode, distance");
		
		$emails_T=array();
		$names_T=array();
		$distan_T=array();
		$distan_S=array();
		$zip_S=array();
		
			if ($zipcodes)
			foreach ($zipcodes as $zips)
			{
			$emails=$wpdb->get_results("SELECT Name, Email, Distance, Zip FROM datauserword where Distance >= '".(round($zips->distance * 100) / 100)."' and enviado=0");
				foreach ($emails as $ema){
					if (!in_array($ema->Email, $emails_T)) {
					$emails_T[]=$ema->Email;
					$names_T[]=$ema->Name;
					$distan_T[]=round($zips->distance * 100) / 100;
					$distan_S[]=$ema->Distance;
					$zip_S[]=$ema->Zip;
					}
				}						
			}			
		
		if (count($emails_T)>0){
		
			for ($i=1;$i<6;$i++) { 
				if ($nuevo['first_name_'.$i][0] <> '') { 
				$docs .="<li style='margin:0 !Important;  padding:0 !Important;list-style-type:none !Important;'>".$nuevo['designation_'.$i][0]." ".$nuevo['first_name_'.$i][0]." ".$nuevo['last_name_'.$i][0]."</li>";
				}

			} 
		$banc=0;
		
		if (trim($nuevo['address_line_1'][0]) <>'' ){
			
		$location = explode('|',$nuevo['address_line_1'][0]);
		
			if ($location[1]<>''){
			$temp = explode(','  , $location[1]);
			$lat = (float) $temp[0];
			$lng = (float) $temp[1];
			$banc=1;
			}

			if (strlen($location[0])>1)
			$direc=$location[0];
			else
			$direc=$nuevo['address_line_1'][0];
			
		} else $direc=$nuevo['address'][0].' + '.$nuevo['city'][0].', '.$nuevo['state'][0].' + '.$nuevo['zip'][0];
			
			if ($banc==0){
			$details_url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$direc."&sensor=false";
 
   			$ch = curl_init();
   			curl_setopt($ch, CURLOPT_URL, $details_url);
   			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   			$response = json_decode(curl_exec($ch), true);
			 
   			// If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
			
   				if ($response['status'] != 'OK') {
    				$lat=0;
    				$lng=0;
   				}else{
    				$geometry = $response['results'][0]['geometry'];
 	    			$lat= $geometry['location']['lat'];
    				$lng= $geometry['location']['lng'];
    				}
    		
			}
		
		$latlng = $lat . "," . $lng;
				
		
		$result = '<table width="576" align="center">
<tr>
		<td><img src="http://propelopens.com/locator/wp-content/themes/physician/_img/mailtop.jpg" width="576"/></td>
		</tr>
</table>
<table width="506" align="center">
		<tr>
		<td colspan="2">
		<img src="http://propelopens.com/locator/wp-content/themes/physician/_img/mailapropel.jpg"/>
		<p style="color:#9c9c9c; font-size:12px; font-family:Arial, Helvetica, sans-serif;line-height:22px">A new PROPEL physician has been add in your area. Please <a href="http://propelopens.com/" style="color:#feb61c; text-decoration:none;">visit our website</a> or contact a physician near you to find out more about PROPEL.</p>
  		</td>
 		</tr>
 		
 		<tr>
  		<td colspan="2" style="text-align:center"><img src="http://propelopens.com/locator/wp-content/themes/physician/_img/mailine.jpg"/></td>
		</tr>
		<tr>
		<td width="180">
		<ul style="margin:0 !Important;  padding:0 !Important;list-style-type:none !Important;color:#9c9c9c; font-size:12px; font-family:Arial, Helvetica, sans-serif; font-weight:600; ">'.$docs.'</ul>
		<p style="color:#9c9c9c; font-size:12px; font-family:Arial, Helvetica, sans-serif">'.$nuevo['practice_name'][0].'<br />'.$nuevo['address'][0].' '.$nuevo['city'][0].', '.$nuevo['state'][0].' '.$nuevo['zip'][0].'</p>
		<p style="color:#9c9c9c; font-size:12px; font-family:Arial, Helvetica, sans-serif">';
		
		if ($nuevo['phone_number'][0]<>'')
		$result .='Tel: '.$nuevo['phone_number'][0];
		if ($nuevo['email_address'][0]<>'')
		$result .='<br/> Email:<span style="color:#fcac00;">'.$nuevo['email_address'][0].'</span>';
		if ($nuevo['website'][0]<>'')
		$result .='<br/> Website:<span style="color:#fcac00;">'.$nuevo['website'][0].'</span>';
		
		$result .='</p>
  		</td>
		<td width="306" align="right">';
   		
		if ($lat<>0) 
		$result .='<img src="http://maps.google.com/maps/api/staticmap?center='.$latlng.'&zoom=16&size=260x83&sensor=false&markers=icon:http://propelopens.com/_img/content/map-marker2.png|'.$latlng.'" />';
		else
		$result .='<img src="http://maps.google.com/maps/api/staticmap?center='.$direc.'&zoom=16&size=260x83&sensor=false&markers=icon:http://propelopens.com/_img/content/map-marker2.png|'.$direc.'" />';
		
		$result3='</tr>
		</table><p></p>
		
		<table width="576" align="center" bgcolor="#dcdcdc" cellpadding="6">
		<tr >
		<td width="100" style="padding:0 30px;"><a href="http://propelopens.com/locator/?page_id=04" style="color:#b4b4b1; font-size:12px; font-family:Arial, Helvetica, sans-serif; font-weight:600; text-decoration:none;"><img src="http://propelopens.com/_img/mailfind.png" height="15" /> Find Propel</a></td>
  
  		<td width="100"><a href="http://propelopens.com/co-contact_us.html" style="color:#b4b4b1; font-size:12px; font-family:Arial, Helvetica, sans-serif; font-weight:600; text-decoration:none;"> <img src="http://propelopens.com/_img/mailcontact.png" /> Contact Us</a></td>
  
  		<td style="text-align:right; padding-right:30px"><img src="http://propelopens.com/_img/global/intersect_logo.png" /></td>
 		</tr>
 		<tr>
  		<td colspan="3">
   		<p style="color:#a8a8a8; font-size:11px; font-family:Arial, Helvetica, sans-serif;padding:0 30px;">You are receiving this email because you or someone using this email address signd up to receive a notification when a PROPEL physician is available within "';	

		for ($a=0; $a<count($emails_T);$a++){
		$result3=$result3.$distan_S[$a].' miles" of your area ("'.$zip_S[$a].'").<br /></p></td></tr></table>';
		$result2='<p style="color:#9c9c9c; font-size:12px; font-family:Arial, Helvetica, sans-serif; text-align:right;line-height:22px;">Distance: '.$distan_T[$a].'miles <br /><span style="color:#fcac00;">Directions</span></p>
  		</td>';
		$subject=$names_T[$a].", we found a PROPEL PHYSICIAN near you!";
		//die($result.$result2.$result3 );
		add_filter('wp_mail_content_type',create_function('', 'return "text/html"; '));
			if (wp_mail( $emails_T[$a], $subject, $result.$result2.$result3 )) {
			$yaenvio= $wpdb->query("update datauserword set enviado=1 where Email='".$emails_T[$a]."'");
			}
			
		}		
		}

			
	

		
	}
 }

}

add_action ( 'save_post', 'nuevo_manda_email' );//save_post


?>