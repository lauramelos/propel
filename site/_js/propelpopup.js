function drawSearchPopUp()
{
$("#propelPopUp").html('<div id="inline_image1" class="inline-content" style="width:780px;"><a href="#" class="cbox-close"></a><img src="_img/_content/illustration1.jpg" alt=""><div class="caption"><h1>Sinus Illustration</h1><p>The sinuses are air-filled cavities located within the bones around the nose and eyes that allow for natural ventilation and drainage.</p></div></div><div id="physician_locator" class="inline-content" style="width:400px;padding:0px;"><div style="background-color:#faac23;height:70px;width:400px;color:#fff; padding-top:34px;"><a href="#" class="cbox-close-orange"></a><p id="physicianHeader" class="find-title">FIND A PROPEL<br /> PHYSICIAN NEAR YOU</p></div><div style="background-color:#fff;height:320px;width:400px;color:#989794;font-family:Arial;font-size:15px;line-height:19px;"><p id="physicianInstructions" style="padding:30px 50px 17px 50px;">Please fill in your zip code and select a distance to find a physician nearest to you.</p><div id="locatorWrapper" style="padding:0px 50px 45px 50px;"><form method="get" action="http://67.222.18.91/~propel/locator/find-propel-physician/" id="locator" onsubmit="return validateForm()"><div style="float:left;"><div class="physician_field" id="emailDiv"><input type="text" name="zipcode" id="zipcode" class="physician_field" style="color:#949590;" value="Zip code*" onchange="validateTextZip()"></div><div class="physician_field" id="zipDiv"><select name="distance" id="distance" class="physician_field chzn-select-no-single chzn-done" tabindex="-1" onchange="validateComboDistance()" style="display: none;"><option value="" disabled="" selected="" style="display:none;">Distance*</option><option value="25">25 miles</option><option value="50">50 miles</option><option value="100">100 miles</option><option value="250">250 miles</option></select><div id="distance_chzn" style="width:300px;" class="chzn-container chzn-container-single chzn-container-single-nosearch chzn-with-drop chzn-container-active" style="width: 296px;" title=""><a href="javascript:void(0)" class="chzn-single" tabindex="-1"><span>Distance*</span><div><b></b></div></a><div class="chzn-drop"><div class="chzn-search"><input type="text" autocomplete="off" tabindex="5"></div><ul class="chzn-results"><li id="distance_chzn_o_1" class="active-result highlighted" style="">25 miles</li><li id="distance_chzn_o_2" class="active-result" style="">50 miles</li><li id="distance_chzn_o_3" class="active-result" style="">100 miles</li><li id="distance_chzn_o_4" class="active-result" style="">250 miles</li></ul></div></div></div></div><div style="clear:both;padding-top:5px;"><input id="physicianSubmit" name="submit" type="submit" style="display:block;width:180px;" /><span style="float:left; margin-top:45px; display:block; margin-bottom:20px;"><i>* Required fields</i></span></div></form></div></div></div><div id="physician_success" class="inline-content" style="width:400px;padding:0px;"><div style="background-color:#faac23;height:105px;width:400px;color:#fff;font-family:"UrbanoBoldExpanded";font-size:25px;"><a href="#" class="cbox-close-orange"></a><p style="padding:60px 50px 25px 50px;">YOUR REQUEST HAS BEEN SUBMITTED.</p></div><div style="background-color:#fff;height:320px;width:400px;color:#989794;font-family:Arial;font-size:15px;line-height:19px;"><p style="padding:20px 50px 75px 50px;">An Intersect ENT representative will respond within 2-3 business days.<br /><br /><a href="#" class="cbox-close-link">CLOSE THIS WINDOW</a></p></div></div> <script> var config = {".chzn-select": {},".chzn-select-deselect" : {allow_single_deselect:true},".chzn-select-no-single" : {disable_search_threshold:10}, ".chzn-select-no-results": {no_results_text:"Oops, nothing found!"},".chzn-select-width" : {width:"289px"}} </script> <div id="inline_video1" class="inline-content"><a href="#" class="cbox-close"></a><object type="application/x-shockwave-flash" id="vid1" name="vid1" menu="false" data="_flash/player.swf" width="654" height="418" style="visibility: visible;"><param name="allowfullscreen" value="true"><param name="allowscriptaccess" value="always"><param name="bgcolor" value="#fff"><param name="wmode" value="transparent"><param name="flashvars" value="repeat=single&amp;volume=75&amp;bufferlength=5&amp;controlbar.position=bottom&amp;autostart=true&amp;stretching=stretch&amp;file=http://www.propelopens.com/_img/_content/intersect-propel.mov&amp;skin=_flash/skin.swf"></object></div>');
}



/*
//var flashvars = {
//        repeat: 'single',
//        volume: 75,
//        bufferlength: 5,
//        'controlbar.position': 'bottom',
//        autostart: 'true',
//        stretching: 'stretch',
//        file:'http://www.propelopens.com/_img/_content/intersect-propel.mov',
//        skin:'_flash/skin.swf'
//};
//var params = {
//        'allowfullscreen':    'true',
//        'allowscriptaccess':  'always',
//        'bgcolor':            '#fff',
//        'wmode':              'transparent' 
//};
//var attributes = {
//        'id':         'vid1',
//        'name':       'vid1',
//        'menu':                         'false'
//};
//swfobject.embedSWF('_flash/player.swf','vid1',654,418,'10.0.0','false',
//flashvars, params, attributes);
//
 */

$(document).ready(function() 
{ 
drawSearchPopUp(); 
for (var selector in config) 
	{
	$(selector).chosen(config[selector]);
	}	
});

function isValidUSZip(sZip) 
{
	return /^\d{5}(-\d{4})?$/.test(sZip);
}

function validateTextZip()
{
document.getElementById('zipcode').style.backgroundColor = '#eeeeee';
document.getElementById('zipcode').style.color = '#9b9b99';
document.getElementById('emailDiv').style.backgroundColor = '#eeeeee';
document.getElementById('emailDiv').style.color = '#9b9b99';

		if(!isValidUSZip(document.getElementById('zipcode').value))
		{
			document.getElementById('zipcode').style.backgroundColor = 'red';
			document.getElementById('zipcode').style.color = '#ffffff';
			document.getElementById('emailDiv').style.backgroundColor = 'red';
			document.getElementById('emailDiv').style.color = '#ffffff';
			return false;
		}
return true;
}

function validateComboDistance()
{
	document.getElementById('distance_chzn').children[0].style.backgroundColor = '#eeeeee';
	document.getElementById('distance_chzn').children[0].style.color = '#9b9b99';
	document.getElementById('zipDiv').style.backgroundColor = '#eeeeee';
	document.getElementById('zipDiv').style.color = '#9b9b99';
	
	if(document.getElementById('distance').value == '')
	{
		document.getElementById('distance_chzn').children[0].style.backgroundColor = 'red';
		document.getElementById('distance_chzn').children[0].style.color = 'white';
		document.getElementById('zipDiv').style.backgroundColor = 'red';
		document.getElementById('zipDiv').style.color = '#ffffff';
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
