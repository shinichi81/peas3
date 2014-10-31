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
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo $title;?></title>
	<link href="/style.css" type="text/css" rel="stylesheet"/>
	<link rel="icon" href="/favicon.ico"/>
</head>
<body>

<div id="container">
	<h1><a href="/"><img src="/img/cloud.png" alt="pEAS3"/></a> <?php if(isset($title)){ echo $title;}?></h1>
	<div id="body">
<?php
if(isset($menu))
{
	$data['menu'] = $menu;
	$this->load->view('top_menu',$data);
}
?>