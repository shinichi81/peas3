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
<script type="text/javascript">
	_editor_url = "/htmlarea/";
	_editor_lang = "en";
	var editor = null;
</script>
<script type="text/javascript" src="/htmlarea/htmlarea.js"></script>
<script type="text/javascript">
	HTMLArea.loadPlugin("ContextMenu");
	HTMLArea.onload = function() {
		editor = new HTMLArea("ta");
		editor.registerPlugin(ContextMenu);
		editor.generate();
	};
	HTMLArea.init();
</script>
<p>Send Messages To Following Lists:</p>
<div>
<?php
echo form_open('/messages/sendMessage',' id="edit" name="edit" ');
if(!empty($lists))
{
	foreach($lists as $list)
	{
		echo '<span class="toList"><input class="checkbox" type="checkbox" name="to_list[]" value="' . $list->id .
		'" id="tol' . $list->id . '"/> <label for="tol' . $list->id . '">' . $list->name . '</label></span>';
	}
}
?>
</div>
Subject: <input type="text" name="subject" placeholder="Subject" value="<?php if(isset($setMsgTitle)){ echo $setMsgTitle;}?>"/>
<textarea id="ta" name="ta" rows="20" cols="80" style="width: 100%"></textarea>
<input type="hidden" name="msg" id="msg" value=""/>
<br/><input type="button" onclick="javascript:mySubmit()" value="Send Message"/> <input type="reset" value="Reset"/>
<?php
echo form_close();
?>
<script type="text/javascript">
function mySubmit() {
	document.edit.msg.value = editor.getHTML().trim();
	document.edit.onsubmit();
	document.edit.submit();
};
<?php if(isset($setHTML)){ echo 'document.getElementById("ta").innerHTML = \'' . $setHTML . '\';'; }?>
</script>