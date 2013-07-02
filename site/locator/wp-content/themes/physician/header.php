<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />

	<?php if (is_search()) { ?>
		  <meta name="robots" content="noindex, nofollow" /> 
	<?php } ?>

	<title>
		   <?php
		      if (function_exists('is_tag') && is_tag()) {
		         single_tag_title("Tag Archive for &quot;"); echo '&quot; - '; }
		      elseif (is_archive()) {
		         wp_title(''); echo ' Archive - '; }
		      elseif (is_search()) {
		         echo 'Search for &quot;'.wp_specialchars($s).'&quot; - '; }
		      elseif (!(is_404()) && (is_single()) || (is_page())) {
		         wp_title(''); echo ' - '; }
		      elseif (is_404()) {
		         echo 'Not Found - '; }
		      if (is_home()) {
		         bloginfo('name'); echo ' - '; bloginfo('description'); }
		      else {
		          bloginfo('name'); }
		      if ($paged>1) {
		         echo ' - page '. $paged; }
		   ?>
	</title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/_css/reset.css" media="all" />
<link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/_css/colorbox-media.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/_css/jquery.loadmask.css" media="all" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/_css/li-scroller.css" media="all" />
<!--link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/chosen/chosen.css" media="all" /-->
<link rel="stylesheet" type="text/css" href="http://propelopens.com/chosen/chosen.css" media="all" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="_css/main_ie.css" media="all" />
<![endif]-->
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/_fonts/urbano_boldexpanded_macroman/stylesheet.css" type="text/css" charset="utf-8" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<!--script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script-->

<style type="text/css" media="screen">#vid1 {visibility:hidden}</style>
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

	<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
	<?php wp_head(); ?>

</head>


<body <?php body_class(); ?>>

	

<!-- body-center START -->

<div id="body-center">

	<!-- body START -->

	<div id="body">

		<!-- content START -->

		<div id="content">

			<!-- header START -->

			<div id="header">

				<ul id="logo-text">

					<li><a href="../../index.html" id="logo" style=" background:url('http://propelopens.com/locator/wp-content/themes/physician/_img/global/logo.png'); width:181px; height:44px; display:block;"></a></li>

					<li class="copy"><p>Opens.</p><p>Delivers.</p><p>Maintains.</p></li>

				</ul>

				<!-- newsalert START -->

				<div id="newsalert">

					<div id="newsalertleft">NEWS ALERT:</div>

					<div class="tickercontainer"><div class="mask"><ul id="ticker01" class="newsticker" style="width: 4655px;">
						<li><span>Nov 2012:</span><a href="docs/IntersectENT_PressRelease-PROPEL_mini_FDA_Approval_2012_11_06.pdf" target="_blank">Intersect ENT Announces FDA Approval PROPEL Mini, Allowing More Chronic Sinusitis Patients to Benefit from Localized Drug Delivery</a></li>
						<li><span>Jan 2013:</span><a href="news-in_the_news.html">Warren Man With Chronic Sinusitus Breathes Easy After Implant Surgery</a></li>
						<li><span>Jan 2013:</span><a href="docs/IntersectENT_PressRelease-RESOLVE_Initiation_Press_Release.pdf" target="_blank">Intersect ENT Initiates U.S. Clinical Study of a Novel In-Office Treatment for Chronic Sinusitis Patients</a></li>
						<li><span>Mar 2013:</span><a href="news-in_the_news.html">Sinus “Stent” Helps Patients Breathe Easier</a></li>
						<li><span>Nov 2012:</span><a href="docs/IntersectENT_PressRelease-PROPEL_mini_FDA_Approval_2012_11_06.pdf" target="_blank">Intersect ENT Announces FDA Approval PROPEL Mini, Allowing More Chronic Sinusitis Patients to Benefit from Localized Drug Delivery</a></li>
						<li><span>Jan 2013:</span><a href="news-in_the_news.html">Warren Man With Chronic Sinusitus Breathes Easy After Implant Surgery</a></li>
						<li><span>Jan 2013:</span><a href="docs/IntersectENT_PressRelease-RESOLVE_Initiation_Press_Release.pdf" target="_blank">Intersect ENT Initiates U.S. Clinical Study of a Novel In-Office Treatment for Chronic Sinusitis Patients</a></li>
						<li><span>Mar 2013:</span><a href="news-in_the_news.html">Sinus “Stent” Helps Patients Breathe Easier</a></li>
					</ul></div></div>

				</div>

				<div id="newsalert-bottom"></div>

				<!-- newsalert END -->

				<!-- main-nav START -->

				<ul id="main-nav">

					<li id="for-patients">

						<a href="http://propelopens.com/patients-what_is_chronic_sinusitis.html" class="item">For Patients</a>

						<div class="subnav">

							<div class="sub-container">

								<ul class="sub">

									<li class="first"><a href="http://propelopens.com/patients-what_is_chronic_sinusitis.html" class="bolt"><span>What is Chronic Sinusitis?</span></a></li>

	                <li><a href="http://propelopens.com/patients-treatments_for_chronic_sinusitis.html" class="plus"><span>Treatments for Chronic Sinusitis</span></a></li>

	                <li><a href="http://propelopens.com/patients-benefits_of_propel.html" class="burst"><span>Benefits of PROPEL<span style="font-size:0.6em; vertical-align:top; line-height:14px; padding:0">&reg;</span></span></a></li>

	                <li><a href="http://propelopens.com/patients-additional_resources.html" class="doc"><span>Additional Resources</span></a></li>

								</ul>

							</div>

						</div>

					</li>

					<li id="propel-advantage">

						<a href="http://propelopens.com/advantage-propel_steroid_releasing_implant.html" class="item">The PROPEL<span style="font-size:0.6em; vertical-align:top; margin-left:-1px; ">&reg;</span> advantage</a>

						<div class="subnav">

							<div class="sub-container">

								<ul class="sub">

									<li class="first"><a href="http://propelopens.com/advantage-propel_steroid_releasing_implant.html" class="cog"><span>PROPEL<span style="font-size:0.6em; vertical-align:top; line-height:14px; padding:0px;">&reg;</span> Steroid-Releasing Implant</span></a></li>

	                <li><a href="http://propelopens.com/advantage-propel_mini.html" class="twocog"><span>PROPEL<span style="font-size:0.6em; vertical-align:top; line-height:14px; padding:0px;">&reg;</span> mini Steroid-Releasing Implant</span></a></li>

	                <li><a href="http://propelopens.com/advantage-how_it_works.html" class="gears"><span>How It Works</span></a></li>

	                <li><a href="http://propelopens.com/advantage-clinical_data.html" class="bars"><span>Clinical Data</span></a></li>

	                <li><a href="http://propelopens.com/advantage-publications.html" class="newspaper"><span>Publications</span></a></li>

								</ul>

							</div>

						</div>

					</li>

					<li id="newsroom">

						<a href="http://propelopens.com/newsroom.html" class="item">Newsroom</a>

						<div class="subnav">

							<div class="sub-container">

								<ul class="sub">

									<li class="first"><a href="http://propelopens.com/newsroom.html" class="microphone"><span>Newsroom</span></a></li>

	                <li><a href="http://propelopens.com/news-press_releases.html" class="doc"><span>Press Releases</span></a></li>

	                <li><a href="http://propelopens.com/news-in_the_news.html" class="newspaper"><span>In The News</span></a></li>

	                <li><a href="http://propelopens.com/news-events_and_awards.html" class="calendar"><span>Events &amp; Awards</span></a></li>

								</ul>

							</div>

						</div>

					</li>

					<li id="company">

						<a href="http://propelopens.com/co-about_us.html" class="item">Company</a>

						<div class="subnav">

							<div class="sub-container">

								<ul class="sub">

									<li class="first"><a href="http://propelopens.com/co-about_us.html" class="cog"><span>About Us</span></a></li>

	                <li><a href="http://propelopens.com/co-leadership_team.html" class="people"><span>Leadership Team</span></a></li>

	                <li><a href="http://propelopens.com/co-board_of_directors.html" class="people"><span>Board of Directors</span></a></li>

	                <li><a href="http://propelopens.com/co-patents.html" class="person"><span>Patents</span></a></li>

	                <li><a href="http://propelopens.com/co-careers.html" class="suitecase"><span>Careers</span></a></li>

	                <li><a href="http://propelopens.com/co-contact_us.html" class="mail"><span>Contact Us</span></a></li>

								</ul>

							</div>

						</div>

					</li>

				</ul>

				<!-- main-nav END -->

				<!-- top-tab START -->

				<div id="top-tab">

					<a href="http://propelopens.com/co-about_us.html" style="background:url('http://propelopens.com/locator/wp-content/themes/physician/_img/global/intersect_logo.png') no-repeat; width:139px; height:35px; display:block;"></a>

				</div>

				<!-- top-tab END -->

			</div>

			<!-- header END -->
