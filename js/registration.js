function civilityValidation(){
	var errorCivility=document.getElementById('error-civility');
	var civility=document.getElementById('civility-select').value;
	
	if((civility != "Mr") && (civility != "Mme")){
		errorCivility.innerHTML="Please choose a civility.";
		return "false";
	}
	else{
		errorCivility.innerHTML="";
		return "true";
	}
}
	
function passwordValidation(){
    var errorPassword = document.getElementById('error-password-confirm');
	var passwordConfirm=document.getElementById('password-confirm').value;
	 
	if ((passwordConfirm != document.getElementById("password").value) && (passwordConfirm !="")) {
		errorPassword.innerHTML = "Incorrect password.";
		return "false";
	}	
	else{
		return "true";
	}
}
	
function mailValidation(){
	var email=document.getElementById('email').value;
	var errorMail=document.getElementById('error-email');
	const pattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
		
	if((!email.match(pattern)) && (email!="")){
		errorMail.innerHTML="Your email is invalid.";
		return "false";
	}
	else{
		return "true";
	}
}
	
function enterElement(element){
	error=document.getElementById(element);
	if(error.value!=""){
		error.innerHTML="";
	}
}

document.getElementById('inscription-form').addEventListener("submit",function(e){
	var errorMail=mailValidation();
	var errorPassword=passwordValidation();
	var errorCivility=civilityValidation();

	if((errorMail != "true" ) || (errorPassword != "true") || (errorCivility != "true")){
		e.preventDefault();
		return false;
	}		
});
	
if(document.getElementById('error-sentence')!=null){
	document.getElementById('inscription-container').style.height="69em";
}
else{
	document.getElementById('inscription-container').style.height="66em";
}

