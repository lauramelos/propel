	document.writeln('<div id="locatorWrapper" style="padding:0px 50px 45px 50px;">' + 
				   '<form method="post" action="http://67.222.18.91/~propel/locator/?page_id=4" id="locator">' + 
				   '<div style="float:left;">' +
				   '<div class="physician_field" id="emailDiv">' + 
				   '<input type="text" name="czip" id="czip" class="physician_field" style="color:#949590;" value="Zip code*" onclick="cargar()">' + '</div>' +
				   '<div class="physician_field" id="zipDiv">' +
				   '<select name="distance" id="distance" class="physician_field chzn-select-no-single" tabindex="5">' +
				   '<option value="" disabled="" selected="" style="display:none;">Distance*</option>' + 
				   '<option value="25">25 miles</option>' +
				   '<option value="50">50 miles</option>' +
				   '<option value="100">100 miles</option>' +
				   '<option value="250">250 miles</option>' +
				   '</select></div></div>' + 
				   '<div style="clear:both;padding-top:5px;">' +
				   '<input id="physicianSubmit" name="submit" type="submit" style="display:block;width:180px;" />' +
				   '<span style="float:left; margin-top:45px; display:block; margin-bottom:20px;"><i>* Required fields</i></span></div>');
  
  document.writeln('<script src="chosen/chosen.jquery.js" type="text/javascript"></script>');
 
 
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
  
	document.writeln('</form></div>');