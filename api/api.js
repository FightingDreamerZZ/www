function check(bar){
	var xmlhttp;
	if (window.XMLHttpRequest)
	  {// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	  }
	xmlhttp.onreadystatechange=function()
	  {
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
		document.getElementById("quantity").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","api.php?api_key=a2922ec60c2c56d3f2e0beb5cab1d6e0&bar="+bar,true);
	xmlhttp.send();
}