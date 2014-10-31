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
if(isset($menu))
{
	echo '<div class="topMenu">';
	foreach($menu as $href => $label){
		echo anchor($href, $label, 'class="topMenuLink"');
	}
	echo '</div>';
}