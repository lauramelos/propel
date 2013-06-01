<?php



/*



Template Name: analitycs



*/

?>

<?php get_header(); ?>





			<!-- inner-content START -->

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/css/iThing.css" type="text/css" />
<script src="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/lib/jquery-1.7.1.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/lib/jquery-ui-1.8.16.custom.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/jQDateRangeSlider-min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/_js/jQRangeSlider-5.1.1/jQAllRangeSliders-withRuler-min.js"></script>
<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>


<script type="text/javascript">

/* BARRA DE TIEMPO */
jQuery(document).ready(function($) {
var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"];
        
		$("#sliderdate").dateRangeSlider({
        
		bounds: {min: new Date(<?=date('Y')?>, 0, 1), max: new Date(<?=date('Y')?>,11,31)},
        
		defaultValues: {min: new Date(<? if (isset($_POST['date_from'])) echo date('Y,m-1,d',strtotime($_POST['date_from'])); else echo date('Y,m-1,d',strtotime('-7 DAY'));?>), max: new Date(<? if (isset($_POST['date_to'])) echo date('Y,m-1,d',strtotime($_POST['date_to']))?>)},
       	
	    scales: [{
      	first: function(value){  return value; },
      	end: function(value) {   return value; },
      	next: function(value){
        var next = new Date(value);
        return new Date(next.setMonth(value.getMonth() + 1));
     	 },
      	
		label: function(value){
        return months[value.getMonth()];
      	}
		
    	},
		{
      	first: function(vale){  return vale; },
      	end: function(vale) {   return vale; },
      	next: function(vale){
        var next = new Date(vale);
        	 
					if (vale.getMonth == 1) 
				   	return new Date(next.setDate(vale.getDate() + 4));
					else
					return new Date(next.setDate(vale.getDate() + 6));
				 
			
     	 },
      	
		label: function(vale){
        return null;
      	}
		
    	}]
        
		});
        
		$("#sliderdate").bind("valuesChanged", function(e, data){
		var f= new Date(data.values.min);
		f = new Date(f.setMonth(f.getMonth() + 1));
		var t= new Date(data.values.max);
		t = new Date(t.setMonth(t.getMonth() + 1));
		
		dfrom=f.getFullYear() + '-' + f.getMonth() + '-' + f.getDate();
		dto=t.getFullYear() + '-' + t.getMonth() + '-' + t.getDate();
		
    	$("#date_from").val(dfrom);
		$("#date_to").val(dto);		
    	});
});
/* GENERA MAPA */

var markers= new Array();

	
	function get_map_mult(){

	var latlng = new google.maps.LatLng(37.06, -95.67);

    	var myOptions = {

        zoom: 4,

        center: latlng,

        mapTypeId: google.maps.MapTypeId.ROADMAP,

        mapTypeControl: false

    	};

    var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);

    var infowindow = new google.maps.InfoWindow(), marker, i;

    	for (i = 0; i < markers.length; i++) {  

        	marker = new google.maps.Marker({

        	position: new google.maps.LatLng(markers[i][1], markers[i][2]),

	        map: map

        	});

        	google.maps.event.addListener(marker, 'click', (function(marker, i) {

            	return function() {

                infowindow.setContent(markers[i][0]);

                infowindow.open(map, marker);

            	}

        	})(marker, i));

    	}

	}



</script>





			<div id="inner-content">

				<!-- header-strip START -->

				<div id="header-strip" style="background:url(_img/content/find-a-propel-physician.jpg) no-repeat; width:938px; height:128px;">

					<p>Company</p>

				</div>

				<!-- header-strip END -->



				<!-- Content Page Navigation START -->

				<ul id="content-nav">

					<li><a href="co-about_us.html">About Us</a></li>

					<li class="sep">|</li>

					<li><a href="co-leadership_team.html">Leadership Team</a></li>

					<li class="sep">|</li>

					<li><a href="co-board_of_directors.html">Board of Directors</a/></li>

					<li class="sep">|</li>

					<li><a href="co-patents.html" class="active">Patents</a></li>

					<li class="sep">|</li>

					<li><a href="co-careers.html">Careers</a></li>

					<li class="sep">|</li>

					<li><a href="co-contact_us.html">Contact Us</a></li>

				</ul>

				<!-- Content Page Navigation END -->



				<!-- Column Content START -->



				<div id="column-content">

				 

				<div style="width:800px; margin-left:auto; margin-right:auto;">
                <div id="ctotal" ></div> 
				<div style="width:720px;  background-color:#fcac00; padding:20px; font-family:arial; margin-bottom:20px;">
				 
				<form action="" method="post">
				
					<p>
					<input type="hidden" id="date_from" name="date_from" value="<?=date('Y-m-d')?>" /><input type="hidden" id="date_to" name="date_to" value="<?=date('Y-m-d')?>" />
					<input type="submit" name="submitsz" value="search" id="submitsz" style="padding:5px;"></p>
				<div id="sliderdate"></div> 
				</form>
				</div>
                     		</div>
				<div id="map_canvas" style="width: 800px; height: 500px; margin-left:20px; "></div>
				<script>get_map_mult();</script>



				<?php

				if ( !empty($_POST['submitsz']) ) { 

				global $wpdb;

				$coordes= $wpdb->get_results("select distinct date_s, zipcode, distance, Lat, Lon from searching where date_s >= '".date('Y-m-d', strtotime($_POST['date_from']))."' and date_s <= '".date('Y-m-d', strtotime($_POST['date_to']))."' order by date_s desc, zipcode, distance");
				//$ocurrences= $wpdb->get_results("select count (distinct date_s, zipcode, distance, Lat, Lon) from searching where date_s >= '".date('Y-m-d', strtotime($_POST['date_from']))."' and date_s <= '".date('Y-m-d', strtotime($_POST['date_to']))."' order by date_s desc, zipcode, distance");

					

					if (count($coordes) > 0 ){	?>
					<div id="search" style="width:800px; margin-left:20px; margin-top:30px; ">Date&nbsp;&nbsp;<?=date('d M Y', strtotime($_POST['date_from']))?> / <?=date('d M Y', strtotime($_POST['date_to']))?></div>
					<div id="search" style="width:950px; margin-left:20px; margin-top:30px; ">			
					<ul class="cssreport"><li>Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li><li>Zipcode</li><li>Distance</li><li>Ocurrences</li></ul>
					</div>
						<?
						foreach ($coordes as $index=>$scorde){ 
							?>

						<div id="search_<?=$index?>" style="width:950px; margin-left:20px; margin-top:30px; ">

						<ul class="cssreport"><li><?= $scorde->date_s?></li><li><?= $scorde->zipcode?></li><li>&nbsp;&nbsp;&nbsp;<?= $scorde->distance?></li><li>&nbsp;&nbsp;&nbsp;<?= $value?></li></ul>

						</div>

						

						<script type="text/javascript">

						var coordina=['<?=$scorde->zipcode?>',<?=$scorde->Lat?>,<?=$scorde->Lon?>];

						markers[<?=$index?>]=coordina;

						</script>

						<?

						}
						
					?><script>$('#ctotal').html('<p class="hay">There where <span><?=count($coordes)?> searches</span> between the selected dates.</p>');</script><?

					} else { ?>

					<script>$('#ctotal').html('<p class="hay">There where not searches between the selected dates.</p>');</script><?

					} ?>

				<p>&nbsp;</p>
				

				<?

				}

				?>



				</div>

				<!-- Column Content END -->

			</div>

			<!-- inner-content END -->

		</div>

		<div id="content-bottom"></div>

		<!-- content END -->











<?php get_footer(); ?>