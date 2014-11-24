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
// open New List Form
echo validation_errors('<p class="error">');
echo form_open('feeds/addFeed');
?>
	<input type="text" name="feedURL" id="feedURL" placeholder="New Feed URL"/>
	<input type="submit" value="Add"/><br/>
<?php 
echo form_close();
// show emails list view
if(!empty($feeds))
{
	foreach($feeds as $feed)
	{
		echo '<div class="email">' . $feed['name'] . ' <a href="/feeds/deleteFeed/' . $feed['id'] . '">[x]</a></div>';
	}
}