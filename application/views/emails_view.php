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
echo form_open('emails/showList');
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
// show emails list view
if(!empty($emails))
{
	foreach($emails as $email)
	{
		echo '<div class="email">' . $email['email'] . ' <a href="/emails/deleteEmail/' . $email['id'] . '">[x]</a></div>';
	}
}
// open New List Form
echo validation_errors('<p class="error">');
echo form_open('emails/addEmail');
?>
	<input type="text" name="email" id="email" placeholder="New Email Address"/>
	<input type="hidden" name="list_id" value="<?php if(!empty($list_id)) echo $list_id;?>"/>
	<input type="submit" value="Add"/><br/>
<?php 
echo form_close();
echo form_open_multipart('emails/uploadFile');
?>
	<input type="file" name="userfile" size="20" />
	<input type="hidden" name="list_id" value="<?php if(!empty($list_id)) echo $list_id;?>"/>
	<input type="submit" value="Upload Emails Text file"/>
<?php
echo form_close();
?>