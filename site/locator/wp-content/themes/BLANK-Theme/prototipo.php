<?php
/*

Template Name: Prototipo

*/
?>
<?php get_header(); ?>

<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
<script type="text/javascript">
	//<![CDATA[
	

	function get_map(lat, lng, ide){
	
	// coordinates to latLng
	var latlng = new google.maps.LatLng(lat, lng);
	// map Options
	var myOptions = {
	zoom: 13,
	center: latlng,
	mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	//draw a map
	var map = new google.maps.Map(document.getElementById("map_" + ide), myOptions);
	var marker = new google.maps.Marker({
	position: map.getCenter(),
	map: map
	});
	}

	//]]>
</script>
			
		<div class="post" id="post-<?php the_ID(); ?>">

		 <form method="post" action="" style="border:2px solid #333; width:400px; background-color:#eaeaea; padding:20px; font-family:arial;">
		 <p>Distance: <select name="distance" id="distance">
  <option value="50">50 millas</option>
  <option value="100">100 millas</option>
  <option value="300">300 millas</option>
  <option value="500">500 millas</option>
</select>

		 <p>Zip: <input type="text" name="czip" id="czip"></p> 
		 <input type="submit" name="submit" id="submit">    
	</form>

			<?php
			if ( !empty($_POST['submit']) ) { 

			global $wpdb;
				
				$radius=$_POST['distance'];

				$coordes= $wpdb->get_results("select Lon, Lat from zipcodes where Zipcode='{$_POST['czip']}'");

					if (count($coordes)){ 

						$bant=0;

					foreach ($coordes as $coords) 
					$founds_zips= $wpdb->get_results("SELECT Zipcode, ( 3959 * acos( cos( radians( ".$coords->Lat." ) ) * cos( radians( Lat ) ) * cos( radians( Lon ) - radians( ".$coords->Lon." ) ) + sin( radians( ".$coords->Lat." ) ) * sin( radians( Lat ) ) ) ) AS distance FROM zipcodes HAVING distance <= ".$radius." ORDER BY distance");
					
						if (count($founds_zips)>0){
							foreach ($founds_zips as $fzip){

								$args = array(
    								'meta_query' => array(
       							 		array(
           						 		'key' => 'address_line_1',	
           						 		'value' => ' '.$fzip->Zipcode,
           								'compare' => 'LIKE'
										)
									)
       							 );
							
							$query = new WP_Query($args);
 							
    						/*$query = new WP_Query("meta_key=zip&meta_value=".$fzip->Zipcode);*/

    							if ($query->post_count > 0) {

    								$bant++;
    						
     								while ($query->have_posts()) : $query->the_post(); ?>

     								<?php
									$location = get_field('address_line_1'  );

									$temp = explode(','  , $location['coordinates' ]);
									$lat = (float) $temp[0];
									$lng = (float) $temp[1];
									$direc=$location['address' ];
									?>

									<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

									<div class="entry">

             						<!--fichas de los medicos-->

									<div style="width:1000px; height:300px; background-color:#FFF; display:block; border:1px solid #333; margin-top:30px;margin-bottom:10px; overflow:auto;">
									<div id="map_<?php the_ID(); ?>" style="width: 300px; height: 250px; float:right;"></div>
									<p><strong>Name:</strong> <?php the_field('name'); ?></p>
									<p><strong>Office name:</strong> <?php the_field('office_name'); ?></p>
									<p><strong>Address line 1:</strong> <?php echo $direc; ?></p>

									<p><strong>Address line 1:</strong> <?php the_field('location'); ?></p>

				        			<!--<iframe width="400" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php echo the_field('address_line_1'); ?>"></iframe>-->
				     
									<p><strong>Address line 2:</strong> <?php the_field('address_line_2'); ?></p>
									<p><strong>Phone number:</strong> <?php the_field('phone_number'); ?></p>
									<p><strong>Email address:</strong> <?php the_field('email_address'); ?></p>
									<p><strong>Website url:</strong> <?php the_field('website_url'); ?></p>
									<p><strong>Distance:</strong> <?php echo round($fzip->distance * 100) / 100; ?> M</p>
									
					
									
									<script>get_map('<?php echo $lat; ?>','<?php echo $lng; ?>',<?php the_ID(); ?>);</script>
									</div>
				
									<?php the_content(); ?>

									<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
				
									<?php the_tags( 'Tags: ', ', ', ''); ?>

									</div>
			
				

									</div>

									<?php endwhile; 
								}?>

							<?php 
							} 

							if ($bant == 0){ ?>

								<p>No se encontraron profesionales en el rango buscado. Haga click en regitrar para que le avisemos:</p>
								<form method="post" action="">
							Name: <input type="text" name="name" id="name">
							Email: <input type="text" name="email" id="email"> 
							Zip: <input type="text" value="<?php echo $_GET['cs-zip-0'];?>" name="zip" id="zip"> 
							Distance: <input type="text" value="<?php echo $_GET['cs-all-1'];?>"  name="distance" id="distance">
		 
		 					<input type="submit" name="submit" id="submit">    
							</form>
						<?php
							}

						}else{ ?>
	 					<p>No tenemos ese ZipCode registrado.</p>
   
						<?php
						}
					}else  echo "no coordenadas" ;
			}else echo "no hay" ;
			?>


<?php get_footer(); ?>
