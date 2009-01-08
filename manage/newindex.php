<?php
/**
* My Handy Restaurant
*
* http://www.myhandyrestaurant.org
*
* My Handy Restaurant is a restaurant complete management tool.
* Visit {@link http://www.myhandyrestaurant.org} for more info.
* Copyright (C) 2003-2005 Fabio De Pascale
* 
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation; either version 2 of the License, or
* (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*
* @author		Fabio 'Kilyerd' De Pascale <public@fabiolinux.com>
* @package		MyHandyRestaurant
* @copyright		Copyright 2003-2005, Fabio De Pascale
*/

$inizio=microtime();

define('ROOTDIR','..');
require(ROOTDIR."/manage/mgmt_funs.php");
require(ROOTDIR."/manage/mgmt_start.php");

unset($_SESSION['who']);

if(isset($_GET['orderby'])){
	$orderby=$_GET['orderby'];
} elseif(isset($_POST['orderby'])){
	$orderby=$_POST['orderby'];
}

$tpl -> set_admin_template_file ('standard');

switch($class) {
	case 'accounting':
		if(!access_allowed(USER_BIT_ACCOUNTING)) $command='access_denied';
		$tmp = head_line('Accounting');
		$tpl -> assign("head", $tmp);

		break;
	default:
		$command='access_denied';
}

switch($command) {
	case 'access_denied':
		access_denied_template();
		break;
	default:
		main_header();
		// next is the general report table creator
		table_general($orderby,"default");
		unset($_SESSION["delete"]);
		break;
}

// prints page generation time
$tmp = generating_time($inizio);
$tpl -> assign ('generating_time',$tmp);

if($err=$tpl->parse()) return $err; 

$tpl -> clean();
$output = $tpl->getOutput();

// prints everything to screen
echo $output;
if(CONF_DEBUG_PRINT_PAGE_SIZE) echo $tpl -> print_size();
?>
