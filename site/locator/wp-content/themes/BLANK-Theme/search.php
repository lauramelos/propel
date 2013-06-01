<?php get_header(); ?>

	<?php if (have_posts()) : ?>

		<h2>Search Results</h2>

		<?php include (TEMPLATEPATH . '/inc/nav.php' ); ?>

		<?php while (have_posts()) : the_post(); ?>

			<div <?php post_class() ?> id="post-<?php the_ID(); ?>">

				<h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>

				

			</div>

		<?php endwhile; ?>

		<?php include (TEMPLATEPATH . '/inc/nav.php' ); ?>

	<?php else : ?>

		<p>No se encontro la busqueda. Haga click en regitrar para que le avisemos:</p>
    
    
	<form method="post" action="">
		 Name: <input type="text" name="name" id="name">
		 Email: <input type="text" name="email" id="email"> 
		 Zip: <input type="text" value="<?php echo $_GET['cs-zip-0'];?>" name="zip" id="zip"> 
		 Distance: <input type="text" value="<?php echo $_GET['cs-all-1'];?>"  name="distance" id="distance">
		 
		 <input type="submit" name="submit" id="submit">    
	</form>
	<?php

//$dbh = new wpdb( root, '', prueba, localhost );

//$query = "INSERT INTO datauserword (zip,distance,email) values('".$_POST['zip']."',".$_POST['distance'].", '".$_POST['distance']."')";

if ( !empty($_POST['submit']) ) { ?>
<p>Sus datos fueron registrados</p>
<?php global $wpdb;
$ban= $wpdb->insert( 'datauserword', array('zip' => $_POST['zip'], 'distance' => $_POST['distance'], 'email' => $_POST['email']), array('%s','%d', '%s'));
}
?>


	<?php endif; ?>



<?php get_footer(); ?>
