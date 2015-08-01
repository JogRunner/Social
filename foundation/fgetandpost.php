<?php
//¶Ô±íµ¥µÄÍ³Ò»´¦Àí
function get_args($name)
{
	if(isset($_POST[$name]))return $_POST[$name];
	if(isset($_GET[$name]))return $_GET[$name];
	return null;
}

//get²ÎÊý´¦Àí
function get_argg($name){
	if(isset($_GET[$name]))return $_GET[$name];
	return null;
}

//post²ÎÊý´¦Àí
function get_argp($name){
	if(isset($_POST[$name]))return $_POST[$name];
	return null;
}
?>
