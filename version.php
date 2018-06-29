<?PHP
/*
* Copyright © 2013 Elaine Warehouse
* File: version.php
* Developing log
*/
error_reporting(E_ALL ^ E_NOTICE);
include('lib/sql.php');
include('lib/user_lib.php');

check_user_cookie();


include('header.php');
?>

<div id="main">
     
<div class="content_box_top"></div>
<div class="content_box">



<h2>DEVELOPING LOG</h2>

<h4>Current Version: Elaine Warehouse 1.50</h4>
<ol>
	<li>[2013.11.04]Requirements Interview ↓</li>
	<li>[2013.11.05]Software&Database Design ↓</li>
	<li>[2013.11.06]User Management(Beta 0.10) ↓</li>
	<li>[2013.11.07]Add,View&Edit Car/Part (Beta 0.20) ↓</li>
	<li>[2013.11.12]Car Templates (Beta 0.25) ↓</li>
	<li>[2013.11.13]Smart Search Engine(Beta 0.30) ↓</li>
	<li>[2013.11.16]Barcode Enter/Depart (Beta 0.40) ↓</li>
	<li>[2013.11.18]Graphic UI Embedded (Beta 0.50) ↓</li>
	<li>[2013.11.20]Year&Vin Trace (Beta 0.60) ↓</li>
	<li>[2013.11.21]Assiciate Parts (Beta 0.70) ↓</li>
	<li>[2013.11.23]Cart via Message Center (Beta 0.80) ↓</li>
	<li>[2013.11.25]Pending Pool (Beta 0.90) ↓</li>	
	<li>[2013.11.26]Online Testing & Fix Bugs (Beta 0.95) ↓</li>	
	<li>[2013.11.27]Elaine Warehouse 1.00 (Formal Release)</li>	
	<li>[2013.11.27]Serial Barcode Input (Version 1.10)</li>	
	<li>[2013.11.29]Search Enhance Patch: Suggestion(Version 1.20)</li>	
	<li>[2013.12.01]Cart Enhance Patch: Edit Amount(Version 1.30)</li>	
	<li>[2013.12.02]Associate Part Enhance Patch(Version 1.40)</li>	
	<li>[2013.12.03]Fix Minor Bugs (Final Version 1.50)</li>	
</ol>


<div class="cleaner h30"></div>
<div class="cleaner"></div>
<div class="cleaner"></div>
</div> <!-- end of a content box -->
<div class="content_box_bottom"></div>
</div> <!-- end of main -->
<?PHP
include('footer.php');
?>