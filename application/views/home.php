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
<h1>Hello, <?php echo $username;?>!</h1>
<p>
You have <?php echo $listsCount;?> Lists,<br/>
containing <?php echo $emailsCount;?> Emails.<br/>
</p>
<p>
Your From Email Address is:<br/>
<div class="email"><?php echo $username;?> &lt;<?php echo $fromEmail;?>&gt; </div>
</p>