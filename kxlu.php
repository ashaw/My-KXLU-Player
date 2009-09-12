<!--
<html>
<form method="get" action="kxlu.php">
<input type="text" name="q">
<input type="submit" value="q">
</form>
-->
<style>
body {
font-family:"Helvetica Neue";helvetica;
}
table tr {
vertical-align:top;
}
h2 {
font-size:14px;
font-weight:bold;
padding:0;
padding-bottom:2px;
}
table tr td {
background-color:#f0f0f0;
}

td.title {
width:230px;
padding:15px;
}
td.embed {
width:230px;
padding:15px;
}
p.date {
font-size:10px;
padding:0;
margin:0;
}
</style>
<title>My KXLU Player</title>
<h1>My KXLU Player</h1>

<table>

<?php

//error_reporting(0);

/**
*
*  +-+-+ +-+-+-+-+ +-+-+-+-+-+-+
*  |M|Y| |K|X|L|U| |P|L|A|Y|E|R| by al shaw
*  +-+-+ +-+-+-+-+ +-+-+-+-+-+-+
*   8 8 . 9   f m   L A     C A  
*
**/

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
	$date = mktime($stream->published);
	
	$date = date('d/m/y h:m',$date);
	*/
	
	echo "<p class=\"date\">" . $stream->published . "</p>";
	
	echo "</td>";
	
	$song = urlencode($song);
	
	echo "<td class=\"embed\">";

	
	//get the youtube vid associated with song
	
	$yt = file_get_contents("http://gdata.youtube.com/feeds/api/videos?q=$song&orderby=relevance&max-results=1&v=2&alt=atom&prettyprint=true");
	$yt = new simpleXMLelement($yt);
	
	//autoplay?
	if ($count == 1) {
		$autoplay = "&autoplay=1";
		} else {
		$autoplay = "";
		}
	
	foreach ($yt->entry->content as $content) {
	
		$url = $content['src'];
		echo "<embed src=\"$url&enablejsapi=1$autoplay\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"212\" height=\"172\" id=\"player$count\"></embed>";
 
		}
	
	echo '</td>';
	
	echo '</tr>';
	
	}


?>
</table>


<h2><a href="kxlu.php?p=<?php echo "$nextpage"; ?>">Next Page</a></h2>

<script type="text/javascript">
//yt js api

var i = 1;
var currentPlayer = "player" + i;
//var nextPlayer = "player" + (i + 1);

function onYouTubePlayerReady(playerId) {
  ytplayer = document.getElementById(currentPlayer);
  ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
}

function incrementAndPlayVideo() {
	currentPlayer = "player" + (i + 1);
	ytplayer.playVideo();
}

function onytplayerStateChange(state) {
  
   if (state == 0) {
   ytplayer = document.getElementById(currentPlayer);
   incrementAndPlayVideo();
   }
}




</script>