
function load_color(color){
	if(color == "default"){
		load_default();
	}else if(color == "star"){
		load_star();
	}else{
		document.getElementById("demo").innerHTML="<style>body {background-color: "+color+";background-image: none;}</style>";
		setCookie("user_theme",color,1);
	}
}

function load_star(){
setCookie("user_theme","star",1);
var i=0;
var t = 100;
while(i<1000){
	setTimeout(function(){document.getElementById("demo").innerHTML="<style>body {background-color: #FF0000;background-image: none;}</style>"},i*t*7+t);
	setTimeout(function(){document.getElementById("demo").innerHTML="<style>body {background-color: #FF6600;background-image: none;}</style>"},i*t*7+t*2);
	setTimeout(function(){document.getElementById("demo").innerHTML="<style>body {background-color: #FFFF00;background-image: none;}</style>"},i*t*7+t*3);
	setTimeout(function(){document.getElementById("demo").innerHTML="<style>body {background-color: #00FF00;background-image: none;}</style>"},i*t*7+t*4);
	setTimeout(function(){document.getElementById("demo").innerHTML="<style>body {background-color: #00FFFF;background-image: none;}</style>"},i*t*7+t*5);
	setTimeout(function(){document.getElementById("demo").innerHTML="<style>body {background-color: #0000FF;background-image: none;}</style>"},i*t*7+t*6);
	setTimeout(function(){document.getElementById("demo").innerHTML="<style>body {background-color: #FF00FF;background-image: none;}</style>"},i*t*7+t*7);
	i++;
	}
	setTimeout(function(){document.getElementById("demo").innerHTML=""},(i-1)*t*7+t*8);
}

function load_default(){
	document.getElementById("demo").innerHTML="";
	setCookie("user_theme","default",1);
}

function setCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value;
}