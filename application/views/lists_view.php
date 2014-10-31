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
// output List View
if(!empty($lists))
{
	echo '<ul>';
	foreach($lists as $row){
		echo '<li>' . $row->name . '
			<a href="/lists/deleteList/' . $row->id . '">[x]</a>
			<a href="/emails/showList/' . $row->id . '">[->]</a>			
			</li>';
	}
	echo '</ul>';
}
// open New List Form
echo validation_errors('<p class="error">');
echo form_open('lists/add_list');
?>
	<input type="text" name="name" id="name" placeholder="New List Name"/>
	<input type="submit" value="Add List"/>
<?php
echo form_close();
?>