<?PHP
/*
* Copyright © 2013 Elaine CRM
* File: index.php
* This file display a user panel to access all frequently used functions.
*/
//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
include('lib/sql.php');
include('lib/user_lib.php');
check_user_cookie();

/* draws a calendar */
function draw_calendar($month,$year){

	/* draw table */
	$calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

	/* table headings */
	$headings = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
	$calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

	/* days and weeks vars now ... */
	$running_day = date('w',mktime(0,0,0,$month,1,$year));
	$days_in_month = date('t',mktime(0,0,0,$month,1,$year));
	$days_in_this_week = 1;
	$day_counter = 0;
	$dates_array = array();

	/* row for week one */
	$calendar.= '<tr class="calendar-row">';

	/* print "blank" days until the first of the current week */
	for($x = 0; $x < $running_day; $x++):
		$calendar.= '<td class="calendar-day-np"> </td>';
		$days_in_this_week++;
	endfor;

	/* keep going with days.... */
	for($list_day = 1; $list_day <= $days_in_month; $list_day++):
		$this_today = sprintf("%04d-%02d-%02d", $year, $month, $list_day);
		if($this_today == date("Y-m-d")){
			$tag = "style=\"background-color:#C0C0C0;\" ";
		}else{
			$tag = "";
		}
		$calendar.= '<td class="calendar-day" '.$tag.'onclick="load_day(\''.$this_today.'\')">';
			/* add in the day number */
			$calendar.= '<div class="day-number">'.$list_day.'</div>';

			/** QUERY THE DATABASE FOR AN ENTRY FOR THIS DAY !!  IF MATCHES FOUND, PRINT THEM !! **/
			$calendar.= '<p>&nbsp;</p>';
			if($_GET[display] == "all"){			
				if(check_event_all($this_today)){
					$calendar.= '<p style="color:#FF0000">[EVENT]</p>';
				}else{
					$calendar.= '<p>&nbsp;</p>';
				}
			}else{			
				if(check_event($this_today,$_COOKIE['ec_user_name'])){
					$calendar.= '<p style="color:#FF0000">[EVENT]</p>';
				}else{
					$calendar.= '<p>&nbsp;</p>';
				}
			}
		$calendar.= '</td>';
		if($running_day == 6):
			$calendar.= '</tr>';
			if(($day_counter+1) != $days_in_month):
				$calendar.= '<tr class="calendar-row">';
			endif;
			$running_day = -1;
			$days_in_this_week = 0;
		endif;
		$days_in_this_week++; $running_day++; $day_counter++;
	endfor;

	/* finish the rest of the days in the week */
	if($days_in_this_week < 8):
		for($x = 1; $x <= (8 - $days_in_this_week); $x++):
			$calendar.= '<td class="calendar-day-np"> </td>';
		endfor;
	endif;

	/* final row */
	$calendar.= '</tr>';

	/* end the table */
	$calendar.= '</table>';
	
	/* all done, return result */
	return $calendar;
}
//$load = " onload=\"load()\"";
include('header.php');

?>
<script type="text/javascript">
function load_day(day_num)
	{
	document.getElementById("event_detail").innerHTML="";
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
		document.getElementById("event_list").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/event_list.php?date="+day_num,true);
	xmlhttp.send();
	}
	
function load_event(lid)
	{
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
		document.getElementById("event_detail").innerHTML=xmlhttp.responseText;
		}
	  }
	xmlhttp.open("GET","ajax/event_detail.php?lid="+lid,true);
	xmlhttp.send();
	}
	
</script>

<style>
/* calendar */
table.calendar { border-left:1px solid #999; }
tr.calendar-row	{ }
td.calendar-day	{ min-height:180px; font-size:11px; position:relative; } * html div.calendar-day { height:180px; }
td.calendar-day:hover	{ background:#eceff5; }
td.calendar-day-np	{ background:#eee; min-height:180px; } * html div.calendar-day-np { height:180px; }
td.calendar-day-head { background:#ccc; font-weight:bold; text-align:center; width:120px; padding:5px; border-bottom:1px solid #999; border-top:1px solid #999; border-right:1px solid #999; }
div.day-number		{ background:#999; padding:5px; color:#fff; font-weight:bold; float:right; margin:-5px -5px 0 0; width:20px; text-align:center; }
/* shared */
td.calendar-day, td.calendar-day-np { width:120px; padding:5px; border-bottom:1px solid #999; border-right:1px solid #999; }
</style>

    
<div id="main"><span class="mf mft"></span><span class="mf mfb"></span>
<div id="content" class="col col_32">
<?php 
echo '<h2>This Month ('.date("M Y").')</h2>';
echo draw_calendar(date("m"),date("Y"));
echo "<br/><br/>";

$next = date("Y-m-01",strtotime("+1 months"));
$datepieces = explode("-", $next);
echo '<h2>Next Month ('.date("M Y",strtotime(date("M Y")."+1 months")).')</h2>';
echo draw_calendar($datepieces[1],$datepieces[0]);
echo "<br/><br/>";

$next = date("Y-m-01",strtotime("-1 months"));
$datepieces = explode("-", $next);
echo '<h2>Last Month ('.date("M Y",strtotime(date("M Y")."-1 months")).')</h2>';
echo draw_calendar($datepieces[1],$datepieces[0]);
?>
</div>

<div id="sidebar" class="col col_3">
<h3>Event List</h3>
<div id = "event_list"></div>

<br/>
<h3>Event Detail</h3>
<div id = "event_detail"></div>
</div>

<div class="clear"></div>
</div> <!-- END of main -->
    
 
    

<?PHP
include('footer.php');
?>