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
echo form_open('crawl/showList');
// output Lists Select Box
if(!empty($lists))
{
	echo 'List: <select name="list_id" onchange="this.form.submit()">';
	foreach($lists as $row)
	{
		$sel = '';
		if(!empty($list_id))
		{
			$sel = ($row->id == $list_id) ? 'selected="selected"':'';
		}
		echo '<option value="' . $row->id . '" ' . $sel . '>' . $row->name . '</option>';
	}
	echo '</select> <input type="submit" value="Show"/>';
}
echo form_close();
// show Web Crawl Messages
if(isset($msg))
{
	foreach($msg as $mid => $mdata)
	{
		echo '<div class="email">' . $mdata . '</div>';
	}
}
// open New Crawl Form
echo form_open('crawl/start');
?>
	<input type="text" name="url" id="url" placeholder="Enter URL Here"/>
	<input type="hidden" name="list_id" value="<?php if(!empty($list_id)) echo $list_id;?>"/>
	<input type="submit" value="Crawl Web for Emails"/><br/>
<?php 
echo form_close();
?>