<?php get_header(); ?>

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			
			<h2><?php the_title(); ?></h2>
			
			<?php include (TEMPLATEPATH . '/inc/meta.php' ); ?>

			<div class="entry">
				<div style="width:1000px; height:300px; background-color:#FFF; display:block; border:1px solid #333;">
					<div id="map" style="width: 300px; height: 250px; float:right;"></div>
					<p><strong>Name:</strong> <?php the_field('name'); ?></p>
					<p><strong>Office name:</strong> <?php the_field('office_name'); ?></p>
					<p><strong>Address line 1:</strong> <?php the_field('address_line_1'); ?></p>
					<p><strong>Address line 1:</strong> <?php the_field('location'); ?></p>

<?php
	$location = get_field('address_line_1'  );
	$temp = explode(','  , $location['coordinates' ]);
	$lat = (float) $temp[0];
	$lng = (float) $temp[1];
?>
<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
<script type="text/javascript">
	//<![CDATA[
	function load() {
	var lat = <?php echo $lat; ?>;
	var lng = <?php echo $lng; ?>;
	// coordinates to latLng
	var latlng = new google.maps.LatLng(lat, lng);
	// map Options
	var myOptions = {
	zoom: 8,
	center: latlng,
	mapTypeId: google.maps.MapTypeId.ROADMAP
	};
	//draw a map
	var map = new google.maps.Map(document.getElementById("map"), myOptions);
	var marker = new google.maps.Marker({
	position: map.getCenter(),
	map: map
	});
	}
	// call the function
	load();
	//]]>
</script>
					<p><strong>Address line 2:</strong> <?php the_field('address_line_2'); ?></p>
					<p><strong>Phone number:</strong> <?php the_field('phone_number'); ?></p>
					<p><strong>Email address:</strong> <?php the_field('email_address'); ?></p>
					<p><strong>Website url:</strong> <?php the_field('website_url'); ?></p>
					<p><strong>Distance:</strong> <?php the_field('distance'); ?></p>
					<p><strong>Zip:</strong> <?php the_field('zip'); ?></p>

    
</div>
				<?php the_content(); ?>

				<?php wp_link_pages(array('before' => 'Pages: ', 'next_or_number' => 'number')); ?>
				
				<?php the_tags( 'Tags: ', ', ', ''); ?>

			</div>
			
			<?php edit_post_link('Edit this entry','','.'); ?>
			
		</div>

	<?php comments_template(); ?>

	<?php endwhile; endif; ?>
	
<?php get_sidebar(); ?>

<?php get_footer(); ?>