<?php

/**
*
*  +-+-+ +-+-+-+-+ +-+-+-+-+-+-+
*  |M|Y| |K|X|L|U| |P|L|A|Y|E|R| by al shaw v0.01
*  +-+-+ +-+-+-+-+ +-+-+-+-+-+-+
*   8 8 . 9   f m   L A     C A  
*
**/

//error_reporting(0);

$version = '0.02';

include 'topmatter.html';

//pagination
if(!isset($_GET['p'])) {
	$p = 1;
	$nextpage = 1;
	
	} else {
	
	$p = $_GET['p'];
	$nextpage = ($p + 1);
	}




//$q = $_GET['q']; //for manual queries

//query kxlu's twitter account

$kxlu = file_get_contents("http://twitter.com/statuses/user_timeline/kxlu.atom?page=$nextpage");

$kxlu = new SimpleXMLelement($kxlu);

$count = 0; //initialize counter

foreach ($kxlu->entry as $stream) {
	
	$count++; //increment counter
	
	echo '<tr>';
	
	//the tweet itself
	
	$song = $stream->content;
	
	
	//regexing out the crap

	$username = "/^KXLU\:/";
	$killusername = "";
	
	$song = preg_replace($username,$killusername,$song);
	
	$url = "/http\:\/\/.*$/";
	$killurl = "";
	
	$song = preg_replace($url,$killurl,$song);
	
	echo "<td class=\"title\">";
	
	echo "<h2>" . $song . "</h2>";
	
	/*
	$date = strtotime($stream->published);
	
	$date = date('d/m/y h:m',$date);
	*/
	
	echo "<p class=\"date\">" . $stream->published . "</p>";
	
	echo "</td>";
	
	$song = urlencode($song);
	
	echo "<td class=\"embed\">";

	
	//get the youtube vid associated with song
	
	$yt = file_get_contents("http://gdata.youtube.com/feeds/api/videos?q=$song&orderby=relevance&max-results=1&v=2&alt=atom");
	$yt = new simpleXMLelement($yt);
	
	//autoplay?
	if ($count == 1) {
		$autoplay = "&autoplay=1";
		} else {
		$autoplay = "";
		}
	
	foreach ($yt->entry->content as $content) {
	
		$url = $content['src'];
		echo "<embed src=\"$url&enablejsapi=1&playerapiid=player$count$autoplay\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"212\" height=\"172\" id=\"player$count\"></embed>";
 
		}
	
	echo '</td>';
	
	echo '</tr>';
	
	
	}

include 'bottommatter.html'; //js

?>
