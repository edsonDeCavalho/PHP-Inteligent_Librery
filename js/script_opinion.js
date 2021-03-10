document.querySelector('.close-opinion').addEventListener('click',
	function(){
		document.querySelector('.opinion-modal').style.display='none';
	}
);

if(document.getElementById('.close-error')!=null){		
	document.querySelector('.close-error').addEventListener('click',
		function(){
			document.querySelector('.error-modal').style.display='none';
		}
	);
}

if(document.getElementById('button-opinion')!=null){
	document.getElementById('button-opinion').addEventListener('click',
	function(){
		document.querySelector('.opinion-modal').style.display='flex';
	});
}
	
if(document.getElementById('button-opinion-need-connexion')!=null){
	document.getElementById('button-opinion-need-connexion').addEventListener('click',
	function(){
		document.querySelector('.connexion-modal').style.display='flex';
	});
}

if(document.getElementById('button-register-need-connexion')!=null){
	document.getElementById('button-register-need-connexion').addEventListener('click',
	function(){
		document.querySelector('.connexion-modal').style.display='flex';
	});
}
