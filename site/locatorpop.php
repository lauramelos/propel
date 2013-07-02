<form method="get" action="http://propelopens.com/locator/find-propel-physician/" id="locator" >
<div style="float:left;"><div class="physician_field" id="emailDiv"><input type="text" name="zipcode" id="zipcode" class="physician_field" style="color:#949590;" placeholder="Zip code*"></div><div class="physician_field" id="zipDiv">
<select name="distance" id="distance" class="physician_field chzn-select-no-single" tabindex="5">
<option value="" disabled="" selected="" style="display:none;">Distance*</option>
<option value="25">25 miles</option>
<option value="50">50 miles</option>
<option value="100">100 miles</option>
<option value="250">250 miles</option>
</select></div></div>
<div style="clear:both;padding-top:5px;">
<input id="physicianSubmit" name="submit" type="submit" style="display:block;width:180px;" />
<span style="float:left; margin-top:45px; display:block; margin-bottom:20px;"><i>* Required fields</i></span></div>
  
<script src="chosen/chosen.jquery.js" type="text/javascript"></script>
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

<script type="text/javascript" src="_js/_jquery/jquery.validate.min.js"></script>
<script type="text/javascript">
function isValidUSZip(sZip) {return /^\d{5}(-\d{4})?$/.test(sZip);}
$('.chzn-results li').click(function() { 
document.getElementById('distance_chzn').children[0].style.backgroundColor = '#eeeeee';
.getElementById('distance_chzn').children[0].style.color = '#9b9b99';
});
	
$('#locator').submit(function() {
alert ("toma");
var result = true;
document.getElementById('distance_chzn').children[0].style.backgroundColor = '#eeeeee';
document.getElementById('distance_chzn').children[0].style.color = '#9b9b99';

if(document.getElementById('distance').value == '')
{
document.getElementById('distance_chzn').children[0].style.backgroundColor = 'red';
document.getElementById('distance_chzn').children[0].style.color = 'white';
result = false;
}
		
return result;});
	
	
$(document).ready(){

$('#locator').validate({
rules: {
zipcode: {
minlength: 5,
required:  true,				
number: true			
},
distance: {
required:true			
} 
},
messages: {
zipcode: ""
}
});
});
</script>

</form>
