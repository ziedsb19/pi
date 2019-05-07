function positif() 
{
var verif=false;
	if (window.document.getElementsByTagName("offre").value)
	{
		verif=true;
	} 
	return verif;
}

function positif_org() 
{
var verif=false;
	if (window.document.getElementsByTagName("prix_original").value>=0)
	{
		verif=true;
	} 
	return verif;
}

function verif() {
	if (positif()==true && positif_org()==true) {
		alert ('fantastic , with all my love , your are the best');
	}
	else 
	{
		alert ('you stupid , go check your code and never came again');
	}
}

function suupp() {
	confirm('veuillez vous suprimer le produit !!');
	}