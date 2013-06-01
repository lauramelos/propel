		<!-- Footer START -->
		<div id="footer" style="background-color:#e0e0dd">
			<div id="copy" style="background-color:#e0e0dd">
			&copy; 2013 Intersect ENT Inc. All rights reserved. <br />
			INTERSECT ENT<span style="font-size: 0.75em; vertical-align:top; line-height:1.1em;">&reg;</span> and PROPEL<span style="font-size: 0.75em; vertical-align:top; line-height:1.1em;">&reg;</span> are trademarks of Intersect ENT, Inc.
			</div>
			<div id="links" style="background-color:#e0e0dd">
				<a href="#physician_locator" rel="colorbox" onmouseover="getElementById('footerFind').style.backgroundPosition='0px -25px';" onmouseout="getElementById('footerFind').style.backgroundPosition='0px 0px';" class="cboxElement"><div id="footerFind" style="background-position: 0px 0px;">Find PROPEL</div></a>
				<!-- <a href="#physician_locator" ><div id="footerFind">Find PROPEL</div></a>-->
				<a href="http://67.222.18.91/~propel/co-careers.html" onMouseOver="getElementById('footerCareer').style.backgroundPosition='0px -25px';" onMouseOut="getElementById('footerCareer').style.backgroundPosition='0px 0px';"><div id="footerCareer">Careers</div></a>
				<a href="http://67.222.18.91/~propel/co-contact_us.html" onMouseOver="getElementById('footerContact').style.backgroundPosition='0px -25px';" onMouseOut="getElementById('footerContact').style.backgroundPosition='0px 0px';"><div id="footerContact">Contact Us</div></a>
			</div>
		</div>
		<!-- Footer END -->
	</div>

	<div id="body-footer" style="background-color:#e0e0dd"></div>
	<div style="clear:both"></div>
	<div id="body-footer-footer"></div>

	<!-- body END -->
</div>
<!-- body-center END -->

<?php wp_footer(); ?>	
<!-- Don't forget analytics -->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/_jquery/jquery-1.6.2.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/_jquery/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/_jquery/preloadCssImages.jQuery_v5.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/app.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/swfobject.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/vid_player.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/_jquery/jquery.loadmask.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/_jquery/jquery.placeholder.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/_js/_jquery/jquery.li-scroller.1.0.js"></script>
<script type="text/javascript">
$(function(){
    $("ul#ticker01").liScroll({travelocity:.05});
});
</script>
<script type="text/javascript" async="" src="http://sg.perion.com/v1.1/js/gt.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory');?>/_js/validate.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory');?>/_js/validate.js"></script>

<script>

function isValidUSZip(sZip) 
{
	return /^\d{5}(-\d{4})?$/.test(sZip);
}

function validateTextZip()
{
document.getElementById('zipcode').style.backgroundColor = '#eeeeee';
document.getElementById('zipcode').style.color = '#9b9b99';
document.getElementById('zipCodeDiv').style.backgroundColor = '#eeeeee';
document.getElementById('zipCodeDiv').style.color = '#9b9b99';
/*Place Holder change color*/
document.getElementById('zipcode').className = 'physician_field placeholderdefault'; 

		if(!isValidUSZip(document.getElementById('zipcode').value))
		{
			document.getElementById('zipcode').style.backgroundColor = 'red';
			document.getElementById('zipcode').style.color = '#ffffff';
			document.getElementById('zipCodeDiv').style.backgroundColor = 'red';
			document.getElementById('zipCodeDiv').style.color = '#ffffff';
			
			document.getElementById('zipcode').className = 'physician_field placeholdererror'; 
			
			return false;
		}
return true;
}

function validateComboDistance()
{
	document.getElementById('distance_chzn').children[0].style.backgroundColor = '#eeeeee';
	document.getElementById('distance_chzn').children[0].style.color = '#9b9b99';
	document.getElementById('distanceDiv').style.backgroundColor = '#eeeeee';
	document.getElementById('distanceDiv').style.color = '#9b9b99';
	
	if(document.getElementById('distance').value == '')
	{
		document.getElementById('distance_chzn').children[0].style.backgroundColor = 'red';
		document.getElementById('distance_chzn').children[0].style.color = 'white';
		document.getElementById('distanceDiv').style.backgroundColor = 'red';
		document.getElementById('distanceDiv').style.color = '#ffffff';
		return false;
	}
	return true;
}

function validateForm()
{
var result = true;
	if(!validateComboDistance())
	{
		result = false;
	}
	
	if(!validateTextZip())
	{
		result = false;
	}
	
return result;
}
</script>

</body>
</html>
