<?php get_header(); ?>

	

			<h2><?php the_title(); ?></h2>
<div style="border:2px solid #333; width:500px;">
			<?php if(function_exists('wp_custom_fields_search')) 
	wp_custom_fields_search('preset-default'); ?>
</div>


<?php get_footer(); ?>
