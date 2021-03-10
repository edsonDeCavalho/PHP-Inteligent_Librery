if(document.getElementById('connexion')!=null){
	document.getElementById('connexion').addEventListener('click',
		function(){
			document.querySelector('.connexion-modal').style.display='flex';
		}
	);
}


document.querySelector('.close').addEventListener('click',
	function(){
		document.querySelector('.connexion-modal').style.display='none';
	}
);