function passwordValidation(){
    var errorPassword = document.getElementById("error-password-confirm");
	var passwordConfirm=document.getElementById("password-confirm").value;
	 
	if ((passwordConfirm != document.getElementById("password").value) && (passwordConfirm !="")) {
		errorPassword.innerHTML = "Incorrect password";
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

document.getElementById('profil-form').addEventListener("submit",function(e){
	if(document.getElementById('password-confirm').disabled==false && document.getElementById('password').disabled==false){
		var errorPassword=passwordValidation();
		if(errorPassword != "true"){
			e.preventDefault();
			return false;
		}
	}	
});

function passwordBlock(){
	if(document.getElementById('change-password-block').style.display=="block"){
		document.getElementById('change-password-block').style.display="none";
		document.getElementById('modified-password-link').style.color="blue";
		document.getElementById('modified-password-link').innerHTML="edit";
		document.getElementById('password-confirm').disabled=true;
		document.getElementById('password').disabled=true;
		
	}
	else{
		document.getElementById('change-password-block').style.display="block";
		document.getElementById('modified-password-link').style.color="red";
		document.getElementById('modified-password-link').innerHTML="cancel";
		document.getElementById('password-confirm').disabled=false;
		document.getElementById('password').disabled=false;
	}
}
