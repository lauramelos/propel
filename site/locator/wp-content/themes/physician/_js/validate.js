$(document).ready(function(){

$('input, textarea').placeholder();

//global vars
var form = $("#locator");
var name = $("#namenf");
var nameDiv = $("#nameDiv");
var email = $("#emailnf");
var emailDiv = $("#emailDiv");
var zip = $("#zipnf");
var zipDiv = $("#zipDiv");
var comments = $("#comments");

//On blur
	name.blur(validateName);
	email.blur(validateEmail);
	zip.blur(validateZip);


form.submit(function(e){
	if(e.currentTarget.id == 'nf')
	{
	e.preventDefault();
	
	
	if(validateName() & validateEmail() & validateZip()) {
		var dataString = 'name='+ name.val() + '&email=' + email.val() + '&zip=' + zip.val() + '&comments=' + comments.val();

		var sPath = window.location.pathname;
		var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
		
		if (sPage == "media.html" || sPage == "physician.html")
			postURL = "../"+"physicianSend.php";
		else
			postURL = "physicianSend.php";
		
		$.ajax({
			type: "POST",
			url: postURL,
			data: dataString,
			success: openSuccess
		});
		return true;
	}
	else
		return false;
	}
});

function openSuccess(){
	$("#physician_locator").mask();
	setTimeout('$.colorbox({inline:true, href:"#physician_success"})', 750);
	setTimeout('$("#physician_locator").unmask()', 1000);
	setTimeout('$("#locator")[0].reset()', 1250);
}

function validateName(){
	if(name.val().length < 3) {
		name.removeClass("physician_field");
		name.addClass("physician_field_error");
		nameDiv.removeClass("physician_field");
		nameDiv.addClass("physician_field_error");
		return false;
	}
	else {
		name.removeClass("physician_field_error");
		name.addClass("physician_field");
		nameDiv.removeClass("physician_field_error");
		nameDiv.addClass("physician_field");
		return true;
	}
}

function validateEmail(){
	var a = email.val();
	//var filter = /^[a-zA-Z0-9_.-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{1,4}$/;
	//var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
    var filter = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
    if(filter.test(a)){
    	email.removeClass("physician_field_error");
		email.addClass("physician_field");
		emailDiv.removeClass("physician_field_error");
		emailDiv.addClass("physician_field");
        return true;
    }
    else{
        email.removeClass("physician_field");
		email.addClass("physician_field_error");
		emailDiv.removeClass("physician_field");
		emailDiv.addClass("physician_field_error");
        return false;
    }
}

function validateZip(){
	var a = zip.val();
	if (/^\d{5}(-\d{4})?$/.test(a)) {
		zip.removeClass("physician_field_error");
		zip.addClass("physician_field");
		zipDiv.removeClass("physician_field_error");
		zipDiv.addClass("physician_field");
        return true;
	}
	else {
		zip.removeClass("physician_field");
		zip.addClass("physician_field_error");
		zipDiv.removeClass("physician_field");
		zipDiv.addClass("physician_field_error");
        return false;
	}
}

});
