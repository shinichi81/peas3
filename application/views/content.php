<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
////////////////////////
//      pEAS v3.0     //
// by Kristaps Ledins //
// ------------------ //
// twitter.com/krysits//
// github.com/krysits //
// krysits@gmail.com  //
// ------------------ //
//(c)2014 krysits.COM //
////////////////////////
?>
<h2><?php echo $title;?></h2>
<?php
echo form_open('messages');
?>
	<input type="hidden" name="html" id="html" value="<h1>Error No. 100</h1>"/>
	<input type="hidden" name="msgTitle" id="msgTitle" value="<h1>Error No. 100</h1>"/>
	<script type="text/javascript">
	function sh(objValue, msgObjValue)
	{
		document.getElementById('html').value = objValue;
		document.getElementById('msgTitle').value = msgObjValue;
		document.getElementById('html').form.submit();
	}
	</script>
<?php 
// helper function
function stripIt($str="")
{
	$str = trim(strip_tags($str));
	$str = str_replace(array("\r\n","\n\r","\r", "\n"), '
', addslashes($str));
	$str = substr($str,0,255);
	$str = substr($str,0, strrpos($str, " "));
	return $str . "..";
}
// show Feed Items list view
if(!empty($feeds))
{
	//var_dump($feeds);
	foreach($feeds as $fid => $feed)
	{
		echo '
		<div style="display:none;" id="ih' . $fid . '"><h1>' . addslashes($feed['itemTitle']) .
		'</h1><h2>' . addslashes($feed['feedName']) .
		'</h2>' . stripIt($feed['itemDescr']) . 
		'<p><a href="' . $feed['itemId'] . 
		'">Read More</a></p></div>
		<div class="email"><span style="padding:4px;background-color:#eee;border:1px #ccc solid;">' .
		$feed['feedName'] . '</span> ' . $feed['itemTitle']. 
		'&nbsp;<a href="javascript:sh(ih' . $fid . '.innerHTML,\'' .
		addslashes($feed['itemTitle']) . '\')">[Add]</a></div>';
	}	
}
echo form_close();