function mostraMenuUtente(){
    html="<div id=\"menuUtente\">\n\
    <a href=\"../index.php?comando=logout\">Esci</a><br>\n\
    <a href=\"../index.php?comando=gestione-account\">Gestione Account</a>\n\
    </div>";
    document.getElementById("utente").innerHTML = html;
}

function controlloCorrispondezaPassword(form) { 
	password1 = form.password.value;
	password2 = form.passwordConferma.value; 
	   
	if (password1 === password2) { 
		return true;
	}
	alert ("Le password non corrispondono");
	return false;
} 