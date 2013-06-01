<?php

/*Template Name: Prototipo*/

?>

<?php get_header(); ?>

			<!-- inner-content START -->
		<div id="inner-content">

				<!-- header-strip START -->

				<!--<div id="header-strip" style="background-images:url(_img/content/find-a-propel-physician.jpg); background-repeat:no-repeat; width:938px; height:128px; display:block;">-->

   		<div class="findapropel">
				</div>

				<!-- header-strip END -->

				<!-- Content Page Navigation START 
				<ul id="content-nav">
					<li><a href="co-about_us.html">About Us</a></li>
					<li class="sep">|</li>
					<li><a href="co-leadership_team.html">Leadership Team</a></li>
					<li class="sep">|</li>
					<li><a href="co-board_of_directors.html">Board of Directors</a></li>
					<li class="sep">|</li>
					<li><a href="co-patents.html" class="active">Patents</a></li>
					<li class="sep">|</li>
					<li><a href="co-careers.html">Careers</a></li>
					<li class="sep">|</li>
					<li><a href="co-contact_us.html">Contact Us</a></li>
				</ul>-->

				<!-- Content Page Navigation END -->
				<!-- Column Content START -->
				<div id="column-content">
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
	var Cimage = new google.maps.MarkerImage(
    	'http://67.222.18.91/~propel/_img/content/map-marker.png',
    	new google.maps.Size(38,53),    // size of the image
    	new google.maps.Point(0,0), // origin, in this case top-left corner
    	new google.maps.Point(9, 25)    // anchor, i.e. the point half-way along the bottom of the image
	);
	var marker = new google.maps.Marker({
	position: map.getCenter(),
	icon: Cimage ,
	map: map
	});
	}

	function locateByAddress(address,ide){
		var geocoder = new google.maps.Geocoder();
			geocoder.geocode({'address':address},function(results,status){
				if(status == google.maps.GeocoderStatus.OK){
					coordinates = results[0].geometry.location.lat()+','+results[0].geometry.location.lng();
					get_map(results[0].geometry.location.lat(),results[0].geometry.location.lng(),ide);
				}
			});
		}
	//]]>
</script>

		<div class="post" id="post-<?php the_ID(); ?>">
        <div style=" width:95%; background-color:#fcac00; padding-left:50px; padding-top:16px; padding-bottom:8px; font-family: Helvetica, Arial;">
		 <form name="locator_form" id="locator_form" method="get" action="" >
         <h1 style="color:#FFFFFF; font-size:22px; margin-bottom:10px;" class="find-title">REFINE YOUR SEARCH</h1>
	 	<p>
          <span class="moverlabeldos" style="height:35px; width:164px; float:left; margin-right:15px;"><input type="text" name="zipcode" id="zipcode" style="width:164px; height:29.5px; background-color:#eeeeee; font-size:18.5px; border:2px solid #d4d4d4; padding-left:10px; padding-top:4px;  float:left; color:#9b9b99;" placeholder="Zip code*" value="<?=$_GET['zipcode']?>"></span>
    		  
		<div style="float:left; width:164px; margin-left:10px; margin-top:-11px; height:35px;">
         <select name="distance" id="distance" class="chzn-select-no-single" style="width:164px">
			<option value='' disabled selected style='display:none;'>Distance*</option>
			<option value="25" <?php if($_GET['distance']=='25'){ echo "selected"; } ?>>25 miles</option>
			<option value="50" <?php if($_GET['distance']=='50'){ echo "selected"; } ?>>50 miles</option>
			<option value="100" <?php if($_GET['distance']=='100'){ echo "selected"; } ?>>100 miles</option>
			<option value="250" <?php if($_GET['distance']=='250'){ echo "selected"; } ?>>250 miles</option>
		</select>
</div>

		<input type="submit" name="submit" id="submit" class="searchbutton" value=" " style="margin-top:-10px;"> 
		</p> 
	


       <script src="http://67.222.18.91/~propel/locator/wp-content/themes/physician/chosen/chosen.jquery.js" type="text/javascript"></script>
  <script type="text/javascript"> 
    var config = {
      '.chzn-select'           : {},
      '.chzn-select-deselect'  : {allow_single_deselect:true},
      '.chzn-select-no-single' : {disable_search_threshold:10},
      '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chzn-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>
		    
	</form>
<p style="color:#FFF; margin-top:10px; font-size:12px; font-style:italic; display:block; width:600px; clear:both; margin-top:42px; padding-left:4px;"> *Required Fields</p>
</div>
<?php //NO ENCONTRADOS
	if ( !empty($_POST['submitnf']) ) {  ?>
		<h1 style="font-size:18px; margin-top:30px;">Thanks! we will send you new results as soon as posible</h1>
<?php global $wpdb;
$ban= $wpdb->insert( 'datauserword', array('Zip' => $_POST['zipnf'], 'Distance' => $_POST['distancenf'], 'Email' => $_POST['emailnf'], 'Name' => $_POST['namenf']), array('%s','%d', '%s','%s'));
}
?>

<?php // BUSQUEDA DOCTORS

if ( !empty($_GET['submit']) ) {
?>
<div id="ctotal" ></div> 
<?
global $wpdb;
$radius=$_GET['distance'];
$coordes= $wpdb->get_results("select Lon, Lat from zipcodes where Zipcode='{$_GET['zipcode']}'");

    if (count($coordes)==0) { echo '<input id="ZipValido" type="hidden" name="opcion" value="NO">'; }

	if (count($coordes)){ 
	$bant=0;
		foreach ($coordes as $coords) 
		//GUARDA BUSQUEDA PARA INFORME POSTERIOR
		$bans= $wpdb->insert( 'searching', array('date_s' => date("Y-m-d"), 'zipcode' => 	$_GET['zipcode'], 'distance' => $_GET['distance'], 'Lat' => $coords->Lat, 'Lon' => $coords->Lon), array('%s','%s', '%d', '%s', '%s'));
		//CONTINUA BUSQUEDA
		$founds_zips= $wpdb->get_results("SELECT Zipcode, ( 3959 * acos( cos( radians( ".$coords->Lat." ) ) * cos( radians( Lat ) ) * cos( radians( Lon ) - radians( ".$coords->Lon." ) ) + sin( radians( ".$coords->Lat." ) ) * sin( radians( Lat ) ) ) ) AS distance FROM zipcodes HAVING distance <= ".$radius." ORDER BY distance");

		
		if (count($founds_zips)>0){
		$encontrados=array();				
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
    				if ($query->post_count > 0) {
				$bant++;
     					while ($query->have_posts()) : $query->the_post(); ?>
					<?php
					if (!in_array(get_the_ID(), $encontrados)) {
					$encontrados[]=get_the_ID();
					$location = get_field('address_line_1'  );
						if ($location['coordinates']<>'' && strlen($location['coordinates'])>1){
						$temp = explode(','  , $location['coordinates' ]);
						$lat = (float) $temp[0];
						$lng = (float) $temp[1];
						}

						if (strlen($location['address'])>1)
						$direc=$location['address' ];
						else
						$direc=$location;
					?>
					<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
					<div class="entry">
					<!--fichas de los medicos-->

					<div class="theoffice">
                    <div class="mapgoogle">
					<div id="map_<?php the_ID(); ?>" class="mapagoogle"></div>
                    <p><strong>Distance: <?php echo round($fzip->distance * 100) / 100; ?> miles</strong><a href="https://maps.google.com/maps?q=<?php echo $direc;?>&hl=en&t=h&mra=ls&z=16&layer=t" target="_blank" style="float:right;">Directions</a></p>
                    </div>
<div class="names">
						<?php 
						for ($i=1;$i<6;$i++) { 
							if (get_field('first_name_'.$i) <> '') { ?>
						<span><!--Physician:--><strong><?php the_field('first_name_'.$i); ?> <?php the_field('last_name_'.$i); ?> <?php the_field('designation_'.$i); ?></strong></span>
							<?php }
						} ?>
</div>
					<? $pn=get_field('practice_name'); if (!empty($pn)) { ?><span><!--Practice Name:--> <?php the_field('practice_name'); ?></span><? } ?>
					<? if (!empty($direc)) { 
					$mdirec=explode(', ',str_replace('-',', ',$direc));
					?><span><!--Address:--> <?=$mdirec[0]; ?><? if (strlen($mdirec[1])>1) {?><br><?=$mdirec[1]; ?><? } ?></span>
					<? } ?>
				        <!--<iframe width="335" height="119" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.googleapis.com/maps/api/staticmap?center=<?php //echo the_field('address_line_1'); ?>"></iframe>-->
                         <div style="margin-top:20px; margin-bottom:18px;">
					<span>
					<? $ph=get_field('phone_number'); if (!empty($ph)) { ?>Tel: <?php the_field('phone_number'); ?><br /><? } ?>

					<? $ea=get_field('email_address'); if (!empty($ea)) { ?>Email: <span style="display:inline; color:#fcac00"><a href="mailto:<?php the_field('email_address'); ?>"><?php the_field('email_address'); ?></a></span><br /><? } ?>
					<? $ws=get_field('website'); if (!empty($ws)) { if (stripos($ws, 'http://') === false) $lws='http://'.$ws; else $lws=$ws; ?>Website: <span style="display:inline; color:#fcac00"><a href="<?=$lws?>" target="_blank"><?php the_field('website'); ?></a></span> <? } ?>
                    			</span>
		    	</div>
						<?php if ($location['coordinates']<>'' && strlen($location['coordinates'])>1){ ?>
						<script>get_map('<?php echo $lat; ?>','<?php echo $lng; ?>',<?php the_ID(); ?>);</script>
						<?php }else{ ?>
						<script>locateByAddress('<?php echo $direc; ?>',<?php the_ID(); ?>);</script>
						<?php } ?>
					</div>
					
					<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>

					<?php the_tags( 'Tags: ', ', ', ''); ?>

					</div>
					</div>
					<?php }
					endwhile;	
					}?>
			<?php 
				} ?>

				<?php

				if (count($encontrados)>0){ ?>

				<script>$('#ctotal').html('<p class="hay">There are <span><?=count($encontrados)?></span> offices within <span><?=$_GET['distance']?> miles</span> from zip code <span><?=$_GET['zipcode']?></span></p>');</script>

				<?php

				}

				?>						

				<?php
				if ($bant == 0){ ?>

				<div style="width:770px; margin-right:auto; margin-left:auto;">
				
								<script>$('#ctotal').html('<p class="nohay">There are no results within <span><?=$_GET['distance']?> miles</span> from zip code <span><?=$_GET['zipcode']?></span></p>');</script>
				
				
				
								<p style="display:block; margin-top:10px; margin-bottom:40px; font-size:16px;">Please widen your search distance, or fill out the form below to sign up for an email notification once there is a PROPEL physician in your area.</p>
				
								<span class="emailnotification"></span>
				
								<p style="margin-bottom:10px;">Fill out this form to receive an email notification once a PROPEL physician is in your area.</p>
				
								<form method="post" action="" name="nf" id="nf">
				
								<p class="moverlabel"><input type="text" name="namenf" id="namenf" placeholder="Name"></p>
				
				
								<p class="moverlabel"><input type="text" name="emailnf" id="emailnf" placeholder="Email*"></p> 
				
								<p><input type="text" value="<?=$_GET['zipcode']?>" name="zipnf" id="zipnf"  placeholder="Zip Code*" ></p> 
				<div style="width:290px;">
								<p><select name="distancenf" id="distancenf" class="chzn-select-no-single">
				
								<option value="25" <?php if($_GET['distance']=='25' || $_POST['distancenf']=='25'){ echo "selected"; } ?>>25 miles</option>
								<option value="50" <?php if($_GET['distance']=='50' || $_POST['distancenf']=='50'){ echo "selected"; } ?>>50 miles</option>
								<option value="100" <?php if($_GET['distance']=='100' || $_POST['distancenf']=='100'){ echo "selected"; } ?>>100 miles</option>
								<option value="250" <?php if($_GET['distance']=='250' || $_POST['distancenf']=='250'){ echo "selected"; } ?>>250 miles</option> 
				
								</select></p>
				</div>
				<p style="margin-top:10px;"><input type="checkbox" name="chagree" id="chagree" value="1" onchange="agree_submit()">&nbsp;I agree to the <a href='#'>Terms and Conditions of Use</a></p>
				
				<p id="sub_agree" style="margin-top:10px;"><input type="submit" name="submitnf" id="submitnf" class="signup_not" value=" " disabled ></p>    
				
				   <script src="http://67.222.18.91/~propel/locator/wp-content/themes/physician/chosen/chosen.jquery.js" type="text/javascript"></script>
				  <script type="text/javascript"> 
					var config = {
					  '.chzn-select'           : {},
					  '.chzn-select-deselect'  : {allow_single_deselect:true},
					  '.chzn-select-no-single' : {disable_search_threshold:10},
					  '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
					  '.chzn-select-width'     : {width:"95%"}
					}
					for (var selector in config) {
					  $(selector).chosen(config[selector]);
					}
				  </script>
				
				</form>
				
				</div>
				
				<?php

			}		

		}else{ ?>

	 	<p><br />There aren't zipcodes on that range.</p>

		
   		<?php
		
		
		
		}

	}
	else
	{
		 ?>
		<div style="width:770px; margin-right:auto; margin-left:auto;">

				<script>$('#ctotal').html('<p class="nohay">There are no results within <span><?=$_GET['distance']?> miles</span> from zip code <span><?=$_GET['zipcode']?></span></p>');</script>



				<p style="display:block; margin-top:10px; margin-bottom:40px; font-size:16px;">Please widen your search distance, or fill out the form below to sign up for an email notification once there is a PROPEL physician in your area.</p>

                <span class="emailnotification"></span>

                <p style="margin-bottom:10px;">Fill out this form to receive an email notification once a PROPEL physician is in your area.</p>

				<form method="post" action="" name="nf" id="nf">

				<p class="moverlabel"><input type="text" name="namenf" id="namenf" placeholder="Name"></p>


				<p class="moverlabel"><input type="text" name="emailnf" id="emailnf" placeholder="Email*"></p> 

				<p style="display:block;"><input type="text" value="<?=$_GET['zipcode']?>" name="zipnf" id="zipnf"  placeholder="Zip Code*" ></p> 
<div style="width:290px;">
				<p><select name="distancenf" id="distancenf" class="chzn-select-no-single">
 				<option value="25" <?php if($_GET['distance']=='25' || $_POST['distancenf']=='25'){ echo "selected"; } ?>>25 miles</option>
				<option value="50" <?php if($_GET['distance']=='50' || $_POST['distancenf']=='50'){ echo "selected"; } ?>>50 miles</option>
				<option value="100" <?php if($_GET['distance']=='100' || $_POST['distancenf']=='100'){ echo "selected"; } ?>>100 miles</option>
				<option value="250" <?php if($_GET['distance']=='250' || $_POST['distancenf']=='250'){ echo "selected"; } ?>>250 miles</option> 
				</select></p>
</div>
<p style="margin-top:10px; margin-left:2px;"><input type="checkbox" name="chagree" id="chagree" value="1" onchange="agree_submit()"><label for="chagree"></label>&nbsp;I agree to the <a href='#'>Terms and Conditions of Use</a></p>

<p id="sub_agree" style="margin-top:10px;"><input type="submit" name="submitnf" id="submitnf" class="signup_not" value=" " disabled ></p>    

   <script src="http://67.222.18.91/~propel/locator/wp-content/themes/physician/chosen/chosen.jquery.js" type="text/javascript"></script>
  <script type="text/javascript"> 
    var config = {
      '.chzn-select'           : {},
      '.chzn-select-deselect'  : {allow_single_deselect:true},
      '.chzn-select-no-single' : {disable_search_threshold:10},
      '.chzn-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chzn-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>

</form>

</div>
		<?
		
	}

}

		?>
				</div>

				<!-- Column Content END -->

			</div>

		<!-- inner-content END -->
		</div>

		<div id="content-bottom"></div>

		<!-- content END -->



<script>
function agree_submit(){
	if($('#submitnf').attr('disabled') == 'disabled'){ 
	$('#submitnf').removeClass('signup_not'); 
	$('#submitnf').addClass('signup'); 
	$('#submitnf').removeAttr('disabled');

	}else{ 
	$('#submitnf').removeClass('signup'); 
	$('#submitnf').addClass('signup_not'); 
	$('#submitnf').attr('disabled', 'disabled');	
	}
}

</script>
<?php

/**

 * Initiate the script.

 * Calls the validation options on the comment form.

 */

function pbd_vc_init() { ?>

	<script type="text/javascript">
	function isValidUSZip(sZip) {return /^\d{5}(-\d{4})?$/.test(sZip);}
	
	$('#locator_form').submit(function() {
		var result = true;
		
		document.getElementById('distance_chzn').children[0].style.backgroundColor = '#eeeeee';
		document.getElementById('distance_chzn').children[0].style.color = '#9b9b99';
		
		document.getElementById('zipcode').style.backgroundColor = '#eeeeee';
		document.getElementById('zipcode').style.color = '#9b9b99';
		
		if(document.getElementById('distance').value == '')
		{
			document.getElementById('distance_chzn').children[0].style.backgroundColor = 'red';
			document.getElementById('distance_chzn').children[0].style.color = 'white';
			result = false;
		}
		
		if(!isValidUSZip(document.getElementById('zipcode').value))
		{
			document.getElementById('zipcode').style.backgroundColor = 'red';
			document.getElementById('zipcode').style.color = 'white';
			result = false;
		}
		return result;});
	
	
		jQuery(document).ready(function($) {
		
		$('#locator_form').validate({
			rules: {
				zipcode: {

				required: true,

				minlength: 5

				},

				distance: {

					required:true
					
						/* {
							depends: function () {
								return $('.chzn-single span').css({"background-color":"red"}) 
              				}
						}*/

				} 

			},


			messages: {

			zipcode: "Please enter a valid Zipcode.",

			distance: "Please select a Distance."

			}

			});


			$('#nf').validate({
			rules: {
				namenf: {
				required: true
				},
				
				emailnf: {
				required: true,
				email: true
				},

				zipnf: {
				required: true,
				minlength: 5
				}, 

				distancenf: {
				required: true
				},

				chagree: {
				required: true
				}	

			},

			messages: {

			namenf: "Please enter your name.",

			emailnf: "Please enter a correct e-mail.",

			zipnf: "Please enter a valid Zipcode.",

			distancenf: "Please select a Distance.",

			chagree: "You have to agree to the Terms and Conditions of Use."

			}

			});

		});

	</script>

<?php }
add_action('wp_footer', 'pbd_vc_init', 999);
?>
<?php get_footer(); ?>